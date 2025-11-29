<?php

namespace App\Http\Controllers;

use App\Models\Webpage;
use App\Models\WebpageSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WebpageController extends Controller
{
    public function __construct()
    {
        // Only require auth for management routes, not for viewing
        $this->middleware('auth')->except(['show']);
    }

    public function index()
    {
        $webpages = Webpage::with('creator')->orderBy('order')->get();
        return view('webpages.index', compact('webpages'));
    }

    public function create()
    {
        return view('webpages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:webpages',
            'type' => 'required|in:landing,article,shop',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'is_published' => 'boolean',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['title']);
        $validated['created_by'] = auth()->id();
        $validated['is_published'] = $request->has('is_published');

        $webpage = Webpage::create($validated);

        return redirect()->route('webpages.edit', $webpage)->with('success', 'Webpage created successfully. Now add sections.');
    }

    public function show(Webpage $webpage)
    {
        if (!$webpage->is_published && !auth()->check()) {
            abort(404);
        }

        $webpage->load('sections');
        return view('webpages.show', compact('webpage'));
    }

    public function edit(Webpage $webpage)
    {
        $webpage->load('sections');
        return view('webpages.edit', compact('webpage'));
    }

    public function update(Request $request, Webpage $webpage)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:webpages,slug,' . $webpage->id,
            'type' => 'required|in:landing,article,shop',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'is_published' => 'boolean',
        ]);

        $validated['is_published'] = $request->has('is_published');

        $webpage->update($validated);

        return redirect()->route('webpages.index')->with('success', 'Webpage updated successfully.');
    }

    public function destroy(Webpage $webpage)
    {
        // Delete associated images
        foreach ($webpage->sections as $section) {
            if ($section->image_path) {
                Storage::disk('public')->delete($section->image_path);
            }
        }

        $webpage->delete();

        return redirect()->route('webpages.index')->with('success', 'Webpage deleted successfully.');
    }

    public function addSection(Request $request, Webpage $webpage)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'image' => 'nullable|image|max:5120', // 5MB max
            'background_image' => 'nullable|image|max:5120',
            'button_text' => 'nullable|string|max:255',
            'button_link' => 'nullable|string|max:255',
            'button_style' => 'nullable|in:primary,secondary,outline',
            'subtitle' => 'nullable|string|max:255',
            'alignment' => 'nullable|in:left,center,right',
            'background_color' => 'nullable|string|max:7',
            'text_color' => 'nullable|string|max:7',
            'secondary_button_text' => 'nullable|string|max:255',
            'secondary_button_link' => 'nullable|string|max:255',
            'metadata' => 'nullable|json',
            // Testimonial fields
            'testimonial1_image' => 'nullable|image|max:5120',
            'testimonial1_name' => 'nullable|string|max:255',
            'testimonial1_description' => 'nullable|string|max:255',
            'testimonial1_text' => 'nullable|string',
            'testimonial2_image' => 'nullable|image|max:5120',
            'testimonial2_name' => 'nullable|string|max:255',
            'testimonial2_description' => 'nullable|string|max:255',
            'testimonial2_text' => 'nullable|string',
            'testimonial3_image' => 'nullable|image|max:5120',
            'testimonial3_name' => 'nullable|string|max:255',
            'testimonial3_description' => 'nullable|string|max:255',
            'testimonial3_text' => 'nullable|string',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('webpages', 'public');
        }

        $backgroundImagePath = null;
        if ($request->hasFile('background_image')) {
            $backgroundImagePath = $request->file('background_image')->store('webpages', 'public');
        }

        $order = ($webpage->sections()->max('order') ?? 0) + 1;

        $metadata = [];
        if ($request->filled('metadata')) {
            $metadata = json_decode($request->metadata, true);
        }
        // Store additional fields in metadata
        if ($request->filled('subtitle')) {
            $metadata['subtitle'] = $request->subtitle;
        }
        if ($request->filled('alignment')) {
            $metadata['alignment'] = $request->alignment;
        }
        if ($request->filled('background_color')) {
            $metadata['background_color'] = $request->background_color;
        }
        if ($request->filled('text_color')) {
            $metadata['text_color'] = $request->text_color;
        }
        if ($backgroundImagePath) {
            $metadata['background_image'] = $backgroundImagePath;
        }
        if ($request->filled('secondary_button_text')) {
            $metadata['secondary_button_text'] = $request->secondary_button_text;
        }
        if ($request->filled('secondary_button_link')) {
            $metadata['secondary_button_link'] = $request->secondary_button_link;
        }

        // Extra text blocks for text_image_split (three subheading/body pairs)
        $textBlocks = [];
        for ($i = 1; $i <= 3; $i++) {
            $sub = $request->input("block{$i}_subheading");
            $txt = $request->input("block{$i}_text");
            if ($sub || $txt) {
                $textBlocks[] = [
                    'subheading' => $sub,
                    'text' => $txt,
                ];
            }
        }
        if (!empty($textBlocks)) {
            $metadata['text_blocks'] = $textBlocks;
        }

        // Testimonials for testimonials_grid (three testimonials with image, name, description, text)
        $testimonials = [];
        for ($i = 1; $i <= 3; $i++) {
            $name = $request->input("testimonial{$i}_name");
            $desc = $request->input("testimonial{$i}_description");
            $text = $request->input("testimonial{$i}_text");
            $testimonialImage = null;
            if ($request->hasFile("testimonial{$i}_image")) {
                $testimonialImage = $request->file("testimonial{$i}_image")->store('webpages', 'public');
            }
            if ($name || $desc || $text || $testimonialImage) {
                $testimonials[] = [
                    'name' => $name,
                    'description' => $desc,
                    'text' => $text,
                    'image' => $testimonialImage,
                ];
            }
        }
        if (!empty($testimonials)) {
            $metadata['testimonials'] = $testimonials;
        }

        WebpageSection::create([
            'webpage_id' => $webpage->id,
            'type' => $validated['type'],
            'title' => $validated['title'] ?? null,
            'content' => $validated['content'] ?? null,
            'image_path' => $imagePath,
            'button_text' => $validated['button_text'] ?? null,
            'button_link' => $validated['button_link'] ?? null,
            'button_style' => $validated['button_style'] ?? 'primary',
            'order' => $order,
            'metadata' => !empty($metadata) ? $metadata : null,
        ]);

        return back()->with('success', 'Section added successfully.');
    }

    public function updateSection(Request $request, WebpageSection $section)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'image' => 'nullable|image|max:5120',
            'background_image' => 'nullable|image|max:5120',
            'button_text' => 'nullable|string|max:255',
            'button_link' => 'nullable|string|max:255',
            'button_style' => 'nullable|in:primary,secondary,outline',
            'order' => 'nullable|integer',
            'subtitle' => 'nullable|string|max:255',
            'alignment' => 'nullable|in:left,center,right',
            'background_color' => 'nullable|string|max:7',
            'text_color' => 'nullable|string|max:7',
            'secondary_button_text' => 'nullable|string|max:255',
            'secondary_button_link' => 'nullable|string|max:255',
            'remove_image' => 'nullable|boolean',
            'remove_background_image' => 'nullable|boolean',
            'metadata' => 'nullable|json',
            // Testimonial fields
            'testimonial1_image' => 'nullable|image|max:5120',
            'testimonial1_name' => 'nullable|string|max:255',
            'testimonial1_description' => 'nullable|string|max:255',
            'testimonial1_text' => 'nullable|string',
            'testimonial2_image' => 'nullable|image|max:5120',
            'testimonial2_name' => 'nullable|string|max:255',
            'testimonial2_description' => 'nullable|string|max:255',
            'testimonial2_text' => 'nullable|string',
            'testimonial3_image' => 'nullable|image|max:5120',
            'testimonial3_name' => 'nullable|string|max:255',
            'testimonial3_description' => 'nullable|string|max:255',
            'testimonial3_text' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($section->image_path) {
                Storage::disk('public')->delete($section->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('webpages', 'public');
        } elseif ($request->boolean('remove_image')) {
            // Remove existing image if requested
            if ($section->image_path) {
                Storage::disk('public')->delete($section->image_path);
            }
            $validated['image_path'] = null;
        }

        $metadata = $section->metadata ?? [];

        if ($request->hasFile('background_image')) {
            // Delete old background image
            if (isset($metadata['background_image'])) {
                Storage::disk('public')->delete($metadata['background_image']);
            }
            $metadata['background_image'] = $request->file('background_image')->store('webpages', 'public');
        } elseif ($request->boolean('remove_background_image')) {
            // Remove existing background image if requested
            if (isset($metadata['background_image'])) {
                Storage::disk('public')->delete($metadata['background_image']);
                unset($metadata['background_image']);
            }
        }

        // Update metadata
        if ($request->filled('subtitle')) {
            $metadata['subtitle'] = $request->subtitle;
        } elseif (isset($metadata['subtitle']) && !$request->filled('subtitle')) {
            unset($metadata['subtitle']);
        }
        if ($request->filled('alignment')) {
            $metadata['alignment'] = $request->alignment;
        }
        if ($request->filled('background_color')) {
            $metadata['background_color'] = $request->background_color;
        }
        if ($request->filled('text_color')) {
            $metadata['text_color'] = $request->text_color;
        }
        if ($request->filled('secondary_button_text')) {
            $metadata['secondary_button_text'] = $request->secondary_button_text;
        }
        if ($request->filled('secondary_button_link')) {
            $metadata['secondary_button_link'] = $request->secondary_button_link;
        }

        // Update extra text blocks for text_image_split
        $textBlocks = [];
        for ($i = 1; $i <= 3; $i++) {
            $sub = $request->input("block{$i}_subheading");
            $txt = $request->input("block{$i}_text");
            if ($sub || $txt) {
                $textBlocks[] = [
                    'subheading' => $sub,
                    'text' => $txt,
                ];
            }
        }
        if (!empty($textBlocks)) {
            $metadata['text_blocks'] = $textBlocks;
        } elseif (isset($metadata['text_blocks'])) {
            unset($metadata['text_blocks']);
        }

        // Update testimonials for testimonials_grid
        $testimonials = [];
        for ($i = 1; $i <= 3; $i++) {
            $name = $request->input("testimonial{$i}_name");
            $desc = $request->input("testimonial{$i}_description");
            $text = $request->input("testimonial{$i}_text");
            $testimonialImage = null;
            
            // Get existing image if it exists
            $existingTestimonials = $metadata['testimonials'] ?? [];
            $existingImage = $existingTestimonials[$i - 1]['image'] ?? null;
            
            if ($request->hasFile("testimonial{$i}_image")) {
                // Delete old image if exists
                if ($existingImage) {
                    Storage::disk('public')->delete($existingImage);
                }
                $testimonialImage = $request->file("testimonial{$i}_image")->store('webpages', 'public');
            } elseif ($existingImage) {
                // Keep existing image if no new upload
                $testimonialImage = $existingImage;
            }
            
            if ($name || $desc || $text || $testimonialImage) {
                $testimonials[] = [
                    'name' => $name,
                    'description' => $desc,
                    'text' => $text,
                    'image' => $testimonialImage,
                ];
            }
        }
        if (!empty($testimonials)) {
            $metadata['testimonials'] = $testimonials;
        } elseif (isset($metadata['testimonials'])) {
            // Delete testimonial images if testimonials are removed
            foreach ($metadata['testimonials'] as $testimonial) {
                if (isset($testimonial['image'])) {
                    Storage::disk('public')->delete($testimonial['image']);
                }
            }
            unset($metadata['testimonials']);
        }

        if (!empty($metadata)) {
            $validated['metadata'] = $metadata;
        }

        $section->update($validated);

        return back()->with('success', 'Section updated successfully.');
    }

    public function deleteSection(WebpageSection $section)
    {
        if ($section->image_path) {
            Storage::disk('public')->delete($section->image_path);
        }

        $section->delete();

        return back()->with('success', 'Section deleted successfully.');
    }

    public function getSection(WebpageSection $section)
    {
        return response()->json([
            'id' => $section->id,
            'type' => $section->type,
            'title' => $section->title,
            'content' => $section->content,
            'image_path' => $section->image_path,
            'button_text' => $section->button_text,
            'button_link' => $section->button_link,
            'button_style' => $section->button_style,
            'metadata' => $section->metadata,
        ]);
    }

    public function reorderSections(Request $request, Webpage $webpage)
    {
        $request->validate([
            'sections' => 'required|array',
            'sections.*' => 'required|integer|exists:webpage_sections,id',
        ]);

        foreach ($request->sections as $index => $sectionId) {
            WebpageSection::where('id', $sectionId)
                ->where('webpage_id', $webpage->id)
                ->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}

