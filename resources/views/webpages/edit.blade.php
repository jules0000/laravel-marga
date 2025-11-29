@extends('layouts.app')

@section('title', 'Edit Webpage')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h3>Edit Webpage: {{ $webpage->title }}</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('webpages.update', $webpage) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $webpage->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug</label>
                        <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $webpage->slug) }}" required>
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Page Type</label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="landing" {{ old('type', $webpage->type) == 'landing' ? 'selected' : '' }}>Landing Page</option>
                            <option value="article" {{ old('type', $webpage->type) == 'article' ? 'selected' : '' }}>Article</option>
                            <option value="shop" {{ old('type', $webpage->type) == 'shop' ? 'selected' : '' }}>Shop</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description" name="meta_description">{{ old('meta_description', $webpage->meta_description) }}</textarea>
                        @error('meta_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="meta_keywords" class="form-label">Meta Keywords</label>
                        <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror" id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $webpage->meta_keywords) }}">
                        @error('meta_keywords')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_published" name="is_published" {{ old('is_published', $webpage->is_published) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_published">Published</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Webpage</button>
                    <a href="{{ route('webpages.index') }}" class="btn btn-secondary">Back to List</a>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Sections</h4>
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addSectionModal">Add Section</button>
            </div>
            <div class="card-body">
                <div id="sections-list">
                    @foreach($webpage->sections as $section)
                    <div class="card mb-3 section-item" data-section-id="{{ $section->id }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h5>{{ $section->type }} Section</h5>
                                    @if($section->title)
                                        <p><strong>Title:</strong> {{ $section->title }}</p>
                                    @endif
                                    @if($section->content)
                                        <p><strong>Content:</strong> {{ \Illuminate\Support\Str::limit($section->content, 100) }}</p>
                                    @endif
                                    @if($section->image_path)
                                        <img src="{{ asset('storage/' . $section->image_path) }}" alt="Section image" class="img-thumbnail" style="max-width: 200px;">
                                    @endif
                                    @if($section->button_text)
                                        <p><strong>Button:</strong> {{ $section->button_text }} ({{ $section->button_style }})</p>
                                    @endif
                                </div>
                                <div>
                                    <button type="button" class="btn btn-sm btn-warning edit-section-btn" data-section-id="{{ $section->id }}" data-bs-toggle="modal" data-bs-target="#editSectionModal">Edit</button>
                                    <form action="{{ route('webpages.sections.destroy', $section) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Preview</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('webpages.show', $webpage) }}" class="btn btn-primary" target="_blank">View Public Page</a>
            </div>
        </div>
    </div>
</div>

<!-- Add Section Modal -->
<div class="modal fade" id="addSectionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Section</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('webpages.sections.store', $webpage) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="section_type" class="form-label">Section Type</label>
                        <select class="form-select" id="section_type" name="type" required>
                            <option value="hero">Hero Section (Title, Subheading, Button, Large Image)</option>
                            <option value="heading">Section Heading</option>
                            <option value="features_grid">3-Column Grid (Butterfly Grid)</option>
                            <option value="grid_item">Grid Item (Card inside 3-Column Grid)</option>
                            <option value="text_image_split">Text + Large Image (Left: Text/Buttons, Right: Image)</option>
                            <option value="two_column">2-Column Layout</option>
                            <option value="column_item">Column Item (Card inside 2-Column Layout)</option>
                            <option value="testimonials_grid">Testimonials Grid (3 Cards)</option>
                            <option value="footer_cta">Footer CTA (Buttons Section)</option>
                            <option value="content">Content Block</option>
                            <option value="image">Image</option>
                            <option value="button">Button</option>
                            <option value="cta">Call to Action</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="section_title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="section_title" name="title" placeholder="Main heading or title">
                    </div>
                    <div class="mb-3">
                        <label for="section_subtitle" class="form-label">Subtitle</label>
                        <input type="text" class="form-control" id="section_subtitle" name="subtitle" placeholder="Secondary text or subtitle">
                    </div>
                    <div class="mb-3">
                        <label for="section_content" class="form-label">Content</label>
                        <textarea class="form-control" id="section_content" name="content" rows="4" placeholder="Main content text"></textarea>
                    </div>
                    <div class="border rounded p-3 mb-3 bg-light d-none" id="add_extra_text_blocks">
                        <h6>Extra Text Blocks (for \"Text + Large Image\" section)</h6>
                        <small class="text-muted d-block mb-2">Used to create the three \"Subheading / Body text\" rows on the left.</small>
                        <div class="mb-3">
                            <label class="form-label">Block 1 Subheading</label>
                            <input type="text" class="form-control" name="block1_subheading">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Block 1 Body Text</label>
                            <textarea class="form-control" name="block1_text" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Block 2 Subheading</label>
                            <input type="text" class="form-control" name="block2_subheading">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Block 2 Body Text</label>
                            <textarea class="form-control" name="block2_text" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Block 3 Subheading</label>
                            <input type="text" class="form-control" name="block3_subheading">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Block 3 Body Text</label>
                            <textarea class="form-control" name="block3_text" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="border rounded p-3 mb-3 bg-light d-none" id="add_testimonials">
                        <h6>Testimonials (for \"Testimonials Grid\" section)</h6>
                        <small class="text-muted d-block mb-3">Add 3 testimonials with profile image, name, description, and testimonial text.</small>
                        
                        <div class="mb-4 pb-3 border-bottom">
                            <h6 class="small fw-bold">Testimonial 1</h6>
                            <div class="mb-3">
                                <label class="form-label">Profile Image</label>
                                <input type="file" class="form-control" name="testimonial1_image" accept="image/*">
                                <small class="text-muted">Upload profile picture (max 5MB)</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="testimonial1_name" placeholder="Name">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <input type="text" class="form-control" name="testimonial1_description" placeholder="Description">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Testimonial Text</label>
                                <textarea class="form-control" name="testimonial1_text" rows="2" placeholder="A terrific piece of praise"></textarea>
                            </div>
                        </div>
                        
                        <div class="mb-4 pb-3 border-bottom">
                            <h6 class="small fw-bold">Testimonial 2</h6>
                            <div class="mb-3">
                                <label class="form-label">Profile Image</label>
                                <input type="file" class="form-control" name="testimonial2_image" accept="image/*">
                                <small class="text-muted">Upload profile picture (max 5MB)</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="testimonial2_name" placeholder="Name">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <input type="text" class="form-control" name="testimonial2_description" placeholder="Description">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Testimonial Text</label>
                                <textarea class="form-control" name="testimonial2_text" rows="2" placeholder="A fantastic bit of feedback"></textarea>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <h6 class="small fw-bold">Testimonial 3</h6>
                            <div class="mb-3">
                                <label class="form-label">Profile Image</label>
                                <input type="file" class="form-control" name="testimonial3_image" accept="image/*">
                                <small class="text-muted">Upload profile picture (max 5MB)</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="testimonial3_name" placeholder="Name">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <input type="text" class="form-control" name="testimonial3_description" placeholder="Description">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Testimonial Text</label>
                                <textarea class="form-control" name="testimonial3_text" rows="2" placeholder="A genuinely glowing review"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="section_image" class="form-label">Main Image</label>
                        <input type="file" class="form-control" id="section_image" name="image" accept="image/*">
                        <small class="text-muted">Upload main image for this section (max 5MB)</small>
                    </div>
                    <div class="mb-3">
                        <label for="section_background_image" class="form-label">Background Image</label>
                        <input type="file" class="form-control" id="section_background_image" name="background_image" accept="image/*">
                        <small class="text-muted">Upload background image (max 5MB)</small>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="section_alignment" class="form-label">Text Alignment</label>
                            <select class="form-select" id="section_alignment" name="alignment">
                                <option value="left">Left</option>
                                <option value="center" selected>Center</option>
                                <option value="right">Right</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="section_background_color" class="form-label">Background Color</label>
                            <input type="color" class="form-control form-control-color" id="section_background_color" name="background_color" value="#ffffff">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="section_text_color" class="form-label">Text Color</label>
                        <input type="color" class="form-control form-control-color" id="section_text_color" name="text_color" value="#000000">
                    </div>
                    <div class="mb-3">
                        <label for="section_button_text" class="form-label">Button Text</label>
                        <input type="text" class="form-control" id="section_button_text" name="button_text">
                    </div>
                    <div class="mb-3">
                        <label for="section_button_link" class="form-label">Button Link</label>
                        <input type="text" class="form-control" id="section_button_link" name="button_link">
                    </div>
                    <div class="mb-3">
                        <label for="section_button_style" class="form-label">Button Style</label>
                        <select class="form-select" id="section_button_style" name="button_style">
                            <option value="primary">Primary (Black)</option>
                            <option value="secondary">Secondary (Light Gray)</option>
                            <option value="outline">Outline</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="section_secondary_button_text" class="form-label">Secondary Button Text (Optional)</label>
                        <input type="text" class="form-control" id="section_secondary_button_text" name="secondary_button_text" placeholder="For sections with 2 buttons">
                    </div>
                    <div class="mb-3">
                        <label for="section_secondary_button_link" class="form-label">Secondary Button Link</label>
                        <input type="text" class="form-control" id="section_secondary_button_link" name="secondary_button_link" placeholder="URL for secondary button">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Section</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Section Modal -->
<div class="modal fade" id="editSectionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Section</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editSectionForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_section_type" class="form-label">Section Type</label>
                        <select class="form-select" id="edit_section_type" name="type" required>
                            <option value="hero">Hero Section (Title, Subheading, Button, Large Image)</option>
                            <option value="heading">Section Heading</option>
                            <option value="features_grid">3-Column Grid (Butterfly Grid)</option>
                            <option value="grid_item">Grid Item (Card inside 3-Column Grid)</option>
                            <option value="text_image_split">Text + Large Image (Left: Text/Buttons, Right: Image)</option>
                            <option value="two_column">2-Column Layout</option>
                            <option value="column_item">Column Item (Card inside 2-Column Layout)</option>
                            <option value="testimonials_grid">Testimonials Grid (3 Cards)</option>
                            <option value="footer_cta">Footer CTA (Buttons Section)</option>
                            <option value="content">Content Block</option>
                            <option value="image">Image</option>
                            <option value="button">Button</option>
                            <option value="cta">Call to Action</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_section_title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="edit_section_title" name="title">
                    </div>
                    <div class="mb-3">
                        <label for="edit_section_subtitle" class="form-label">Subtitle</label>
                        <input type="text" class="form-control" id="edit_section_subtitle" name="subtitle">
                    </div>
                    <div class="mb-3">
                        <label for="edit_section_content" class="form-label">Content</label>
                        <textarea class="form-control" id="edit_section_content" name="content" rows="4"></textarea>
                    </div>
                    <div class="border rounded p-3 mb-3 bg-light d-none" id="edit_extra_text_blocks">
                        <h6>Extra Text Blocks (for \"Text + Large Image\" section)</h6>
                        <small class="text-muted d-block mb-2">Used to create the three \"Subheading / Body text\" rows on the left.</small>
                        <div class="mb-3">
                            <label class="form-label">Block 1 Subheading</label>
                            <input type="text" class="form-control" id="edit_block1_subheading" name="block1_subheading">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Block 1 Body Text</label>
                            <textarea class="form-control" id="edit_block1_text" name="block1_text" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Block 2 Subheading</label>
                            <input type="text" class="form-control" id="edit_block2_subheading" name="block2_subheading">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Block 2 Body Text</label>
                            <textarea class="form-control" id="edit_block2_text" name="block2_text" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Block 3 Subheading</label>
                            <input type="text" class="form-control" id="edit_block3_subheading" name="block3_subheading">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Block 3 Body Text</label>
                            <textarea class="form-control" id="edit_block3_text" name="block3_text" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="border rounded p-3 mb-3 bg-light d-none" id="edit_testimonials">
                        <h6>Testimonials (for \"Testimonials Grid\" section)</h6>
                        <small class="text-muted d-block mb-3">Add 3 testimonials with profile image, name, description, and testimonial text.</small>
                        
                        <div class="mb-4 pb-3 border-bottom">
                            <h6 class="small fw-bold">Testimonial 1</h6>
                            <div class="mb-3">
                                <label class="form-label">Profile Image</label>
                                <input type="file" class="form-control" id="edit_testimonial1_image" name="testimonial1_image" accept="image/*">
                                <div id="current-testimonial1-image" class="mt-2"></div>
                                <small class="text-muted">Upload profile picture (max 5MB)</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" id="edit_testimonial1_name" name="testimonial1_name" placeholder="Name">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <input type="text" class="form-control" id="edit_testimonial1_description" name="testimonial1_description" placeholder="Description">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Testimonial Text</label>
                                <textarea class="form-control" id="edit_testimonial1_text" name="testimonial1_text" rows="2" placeholder="A terrific piece of praise"></textarea>
                            </div>
                        </div>
                        
                        <div class="mb-4 pb-3 border-bottom">
                            <h6 class="small fw-bold">Testimonial 2</h6>
                            <div class="mb-3">
                                <label class="form-label">Profile Image</label>
                                <input type="file" class="form-control" id="edit_testimonial2_image" name="testimonial2_image" accept="image/*">
                                <div id="current-testimonial2-image" class="mt-2"></div>
                                <small class="text-muted">Upload profile picture (max 5MB)</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" id="edit_testimonial2_name" name="testimonial2_name" placeholder="Name">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <input type="text" class="form-control" id="edit_testimonial2_description" name="testimonial2_description" placeholder="Description">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Testimonial Text</label>
                                <textarea class="form-control" id="edit_testimonial2_text" name="testimonial2_text" rows="2" placeholder="A fantastic bit of feedback"></textarea>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <h6 class="small fw-bold">Testimonial 3</h6>
                            <div class="mb-3">
                                <label class="form-label">Profile Image</label>
                                <input type="file" class="form-control" id="edit_testimonial3_image" name="testimonial3_image" accept="image/*">
                                <div id="current-testimonial3-image" class="mt-2"></div>
                                <small class="text-muted">Upload profile picture (max 5MB)</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" id="edit_testimonial3_name" name="testimonial3_name" placeholder="Name">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <input type="text" class="form-control" id="edit_testimonial3_description" name="testimonial3_description" placeholder="Description">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Testimonial Text</label>
                                <textarea class="form-control" id="edit_testimonial3_text" name="testimonial3_text" rows="2" placeholder="A genuinely glowing review"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_section_image" class="form-label">Main Image</label>
                        <input type="file" class="form-control" id="edit_section_image" name="image" accept="image/*">
                        <div id="current-image" class="mt-2"></div>
                        <small class="text-muted d-block mb-1">Upload new image to replace current (max 5MB)</small>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="edit_remove_image" name="remove_image">
                            <label class="form-check-label" for="edit_remove_image">
                                Remove main image
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_section_background_image" class="form-label">Background Image</label>
                        <input type="file" class="form-control" id="edit_section_background_image" name="background_image" accept="image/*">
                        <div id="current-background-image" class="mt-2"></div>
                        <small class="text-muted d-block mb-1">Upload new background image to replace current (max 5MB)</small>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="edit_remove_background_image" name="remove_background_image">
                            <label class="form-check-label" for="edit_remove_background_image">
                                Remove background image
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_section_alignment" class="form-label">Text Alignment</label>
                            <select class="form-select" id="edit_section_alignment" name="alignment">
                                <option value="left">Left</option>
                                <option value="center" selected>Center</option>
                                <option value="right">Right</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_section_background_color" class="form-label">Background Color</label>
                            <input type="color" class="form-control form-control-color" id="edit_section_background_color" name="background_color" value="#ffffff">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_section_text_color" class="form-label">Text Color</label>
                        <input type="color" class="form-control form-control-color" id="edit_section_text_color" name="text_color" value="#000000">
                    </div>
                    <div class="mb-3">
                        <label for="edit_section_button_text" class="form-label">Button Text</label>
                        <input type="text" class="form-control" id="edit_section_button_text" name="button_text">
                    </div>
                    <div class="mb-3">
                        <label for="edit_section_button_link" class="form-label">Button Link</label>
                        <input type="text" class="form-control" id="edit_section_button_link" name="button_link">
                    </div>
                    <div class="mb-3">
                        <label for="edit_section_button_style" class="form-label">Button Style</label>
                        <select class="form-select" id="edit_section_button_style" name="button_style">
                            <option value="primary">Primary (Black)</option>
                            <option value="secondary">Secondary (Light Gray)</option>
                            <option value="outline">Outline</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_section_secondary_button_text" class="form-label">Secondary Button Text (Optional)</label>
                        <input type="text" class="form-control" id="edit_section_secondary_button_text" name="secondary_button_text">
                    </div>
                    <div class="mb-3">
                        <label for="edit_section_secondary_button_link" class="form-label">Secondary Button Link</label>
                        <input type="text" class="form-control" id="edit_section_secondary_button_link" name="secondary_button_link">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Section</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Helper: toggle extra text blocks visibility based on type
    function toggleExtraBlocks(selectEl, containerId) {
        const container = document.getElementById(containerId);
        if (!container) return;
        if (selectEl.value === 'text_image_split') {
            container.classList.remove('d-none');
        } else {
            container.classList.add('d-none');
        }
    }

    // Helper: toggle testimonials visibility based on type
    function toggleTestimonials(selectEl, containerId) {
        const container = document.getElementById(containerId);
        if (!container) return;
        if (selectEl.value === 'testimonials_grid' || selectEl.value === 'testimonial') {
            container.classList.remove('d-none');
        } else {
            container.classList.add('d-none');
        }
    }

    // Add Section modal: show extra blocks only for text_image_split
    const addTypeSelect = document.getElementById('section_type');
    if (addTypeSelect) {
        toggleExtraBlocks(addTypeSelect, 'add_extra_text_blocks');
        toggleTestimonials(addTypeSelect, 'add_testimonials');
        addTypeSelect.addEventListener('change', function () {
            toggleExtraBlocks(addTypeSelect, 'add_extra_text_blocks');
            toggleTestimonials(addTypeSelect, 'add_testimonials');
        });
    }

    const editButtons = document.querySelectorAll('.edit-section-btn');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const sectionId = this.getAttribute('data-section-id');
            fetch(`{{ url('/webpages/sections') }}/${sectionId}`)
                .then(response => response.json())
                .then(section => {
                    const typeSelect = document.getElementById('edit_section_type');
                    typeSelect.value = section.type || 'content';
                    document.getElementById('edit_section_title').value = section.title || '';
                    document.getElementById('edit_section_subtitle').value = (section.metadata && section.metadata.subtitle) || '';
                    document.getElementById('edit_section_content').value = section.content || '';
                    document.getElementById('edit_section_button_text').value = section.button_text || '';
                    document.getElementById('edit_section_button_link').value = section.button_link || '';
                    document.getElementById('edit_section_button_style').value = section.button_style || 'primary';
                    document.getElementById('edit_section_secondary_button_text').value = (section.metadata && section.metadata.secondary_button_text) || '';
                    document.getElementById('edit_section_secondary_button_link').value = (section.metadata && section.metadata.secondary_button_link) || '';
                    document.getElementById('edit_section_alignment').value = (section.metadata && section.metadata.alignment) || 'left';
                    document.getElementById('edit_section_background_color').value = (section.metadata && section.metadata.background_color) || '#ffffff';
                    document.getElementById('edit_section_text_color').value = (section.metadata && section.metadata.text_color) || '#000000';

                    // Extra text blocks for text_image_split
                    const textBlocks = (section.metadata && section.metadata.text_blocks) || [];
                    document.getElementById('edit_block1_subheading').value = textBlocks[0]?.subheading || '';
                    document.getElementById('edit_block1_text').value = textBlocks[0]?.text || '';
                    document.getElementById('edit_block2_subheading').value = textBlocks[1]?.subheading || '';
                    document.getElementById('edit_block2_text').value = textBlocks[1]?.text || '';
                    document.getElementById('edit_block3_subheading').value = textBlocks[2]?.subheading || '';
                    document.getElementById('edit_block3_text').value = textBlocks[2]?.text || '';

                    // Toggle visibility of extra text blocks in edit modal
                    toggleExtraBlocks(typeSelect, 'edit_extra_text_blocks');
                    toggleTestimonials(typeSelect, 'edit_testimonials');
                    
                    // Populate testimonials if they exist
                    const testimonials = (section.metadata && section.metadata.testimonials) || [];
                    if (testimonials.length > 0) {
                        document.getElementById('edit_testimonial1_name').value = testimonials[0]?.name || '';
                        document.getElementById('edit_testimonial1_description').value = testimonials[0]?.description || '';
                        document.getElementById('edit_testimonial1_text').value = testimonials[0]?.text || '';
                        if (testimonials[0]?.image) {
                            document.getElementById('current-testimonial1-image').innerHTML = `<img src="/storage/${testimonials[0].image}" alt="Profile" class="img-thumbnail" style="max-width: 100px;"><p class="small text-muted mt-2">Current profile image</p>`;
                        }
                        
                        document.getElementById('edit_testimonial2_name').value = testimonials[1]?.name || '';
                        document.getElementById('edit_testimonial2_description').value = testimonials[1]?.description || '';
                        document.getElementById('edit_testimonial2_text').value = testimonials[1]?.text || '';
                        if (testimonials[1]?.image) {
                            document.getElementById('current-testimonial2-image').innerHTML = `<img src="/storage/${testimonials[1].image}" alt="Profile" class="img-thumbnail" style="max-width: 100px;"><p class="small text-muted mt-2">Current profile image</p>`;
                        }
                        
                        document.getElementById('edit_testimonial3_name').value = testimonials[2]?.name || '';
                        document.getElementById('edit_testimonial3_description').value = testimonials[2]?.description || '';
                        document.getElementById('edit_testimonial3_text').value = testimonials[2]?.text || '';
                        if (testimonials[2]?.image) {
                            document.getElementById('current-testimonial3-image').innerHTML = `<img src="/storage/${testimonials[2].image}" alt="Profile" class="img-thumbnail" style="max-width: 100px;"><p class="small text-muted mt-2">Current profile image</p>`;
                        }
                    }
                    
                    const currentImageDiv = document.getElementById('current-image');
                    if (section.image_path) {
                        currentImageDiv.innerHTML = `<img src="/storage/${section.image_path}" alt="Current image" class="img-thumbnail" style="max-width: 200px;"><p class="small text-muted mt-2">Current main image</p>`;
                    } else {
                        currentImageDiv.innerHTML = '';
                    }
                    
                    const currentBgImageDiv = document.getElementById('current-background-image');
                    if (section.metadata && section.metadata.background_image) {
                        currentBgImageDiv.innerHTML = `<img src="/storage/${section.metadata.background_image}" alt="Current background" class="img-thumbnail" style="max-width: 200px;"><p class="small text-muted mt-2">Current background image</p>`;
                    } else {
                        currentBgImageDiv.innerHTML = '';
                    }
                    
                    document.getElementById('editSectionForm').action = `/webpages/sections/${sectionId}`;
                });
        });
    });

    // Edit modal: toggle testimonials when type changes
    const editTypeSelect = document.getElementById('edit_section_type');
    if (editTypeSelect) {
        editTypeSelect.addEventListener('change', function() {
            toggleExtraBlocks(this, 'edit_extra_text_blocks');
            toggleTestimonials(this, 'edit_testimonials');
        });
    }
});
</script>
@endsection

