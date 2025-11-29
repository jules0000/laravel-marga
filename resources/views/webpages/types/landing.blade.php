@foreach($webpage->sections->sortBy('order') as $section)
    @php
        $metadata = $section->metadata ?? [];
        $alignment = $metadata['alignment'] ?? 'left';
        $bgColor = $metadata['background_color'] ?? '#ffffff';
        $textColor = $metadata['text_color'] ?? '#000000';
        $bgImage = isset($metadata['background_image']) ? asset('storage/' . $metadata['background_image']) : null;
        $subtitle = $metadata['subtitle'] ?? null;
        $style = 'color: ' . $textColor . ';';
        if ($bgImage) {
            $style .= ' background-image: url(' . $bgImage . '); background-size: cover; background-position: center; background-repeat: no-repeat;';
        } else {
            $style .= ' background-color: ' . $bgColor . ';';
        }
    @endphp

    @if($section->type == 'hero')
        <!-- Hero Section - Title, Subheading, Button, Large Image -->
        <section class="hero-section py-5" style="{{ $style }}">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-{{ $alignment }} mb-4">
                        @if($section->title)
                            <h1 class="display-2 fw-bold mb-4" style="color: {{ $textColor }}; font-size: 3.5rem;">{{ $section->title }}</h1>
                        @endif
                        @if($subtitle)
                            @php
                                $subtitleMargin = $alignment === 'center' ? '0 auto' : '0';
                            @endphp
                            <p class="lead mb-4" style="color: {{ $textColor }}; font-size: 1.25rem; max-width: 800px; margin: {{ $subtitleMargin }};">{{ $subtitle }}</p>
                        @endif
                        @if($section->content)
                            @php
                                $contentMargin = $alignment === 'center' ? '0 auto' : '0';
                            @endphp
                            <p class="mb-4" style="color: {{ $textColor }}; max-width: 800px; margin: {{ $contentMargin }};">{{ $section->content }}</p>
                        @endif
                        @if($section->button_text)
                            <a href="{{ $section->button_link ?? '#' }}" class="btn btn-lg btn-dark px-4 py-2 mb-5" style="background-color: #000; border: none; border-radius: 0;">
                                {{ $section->button_text }}
                            </a>
                        @endif
                    </div>
                    @if($section->image_path)
                        <div class="col-12">
                            <img src="{{ asset('storage/' . $section->image_path) }}" alt="{{ $section->title ?? 'Hero Image' }}" class="img-fluid w-100" style="border-radius: 0;">
                        </div>
                    @endif
                </div>
            </div>
        </section>

    @elseif($section->type == 'features_grid' || $section->type == 'features')
        <!-- 3-Column Grid Section -->
        <section class="features-grid-section py-5" style="{{ $style }}">
            <div class="container">
                @if($section->title)
                    <div class="mb-5 text-{{ $alignment }}">
                        <h2 class="fw-bold" style="color: {{ $textColor }}; font-size: 2.5rem;">{{ $section->title }}</h2>
                    </div>
                @endif
                <div class="row g-4">
                    @php
                        // Get child sections for this grid (sections with same parent or grouped)
                        $gridItems = $webpage->sections->where('type', 'grid_item')->where('order', '>', $section->order)->take(3);
                    @endphp
                    @if($gridItems->count() > 0)
                        @foreach($gridItems as $item)
                            <div class="col-md-4 text-{{ $alignment }}">
                                @if($item->image_path)
                                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title ?? 'Feature' }}" class="img-fluid w-100 mb-3" style="border-radius: 0;">
                                @endif
                                @if($item->title)
                                    <h3 class="h5 fw-semibold mb-2" style="color: {{ $textColor }};">{{ $item->title }}</h3>
                                @endif
                                @if($item->content)
                                    <p style="color: {{ $textColor }};">{{ $item->content }}</p>
                                @endif
                            </div>
                        @endforeach
                    @else
                        {{-- Fallback: Show single section content if no grid items --}}
                        <div class="col-md-4 text-{{ $alignment }}">
                            @if($section->image_path)
                                <img src="{{ asset('storage/' . $section->image_path) }}" alt="Feature" class="img-fluid w-100 mb-3" style="border-radius: 0;">
                            @endif
                            @if($subtitle)
                                <h3 class="h5 fw-semibold mb-2" style="color: {{ $textColor }};">{{ $subtitle }}</h3>
                            @endif
                            @if($section->content)
                                <p style="color: {{ $textColor }};">{{ $section->content }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </section>

    @elseif($section->type == 'text_image_split')
        <!-- Text + Large Image Section (Left: Text blocks + Buttons, Right: Large Image) -->
        <section class="text-image-split-section py-5" style="{{ $style }}">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 text-{{ $alignment }}">
                        @if($section->title)
                            <h2 class="fw-bold mb-3" style="color: {{ $textColor }}; font-size: 2.5rem;">{{ $section->title }}</h2>
                        @endif
                        @if($subtitle)
                            <h3 class="h5 fw-semibold mb-3" style="color: {{ $textColor }};">{{ $subtitle }}</h3>
                        @endif
                        @if($section->content)
                            <p class="mb-4" style="color: {{ $textColor }};">{{ $section->content }}</p>
                        @endif
                        @php
                            // Get additional text blocks from metadata or child sections
                            $textBlocks = $metadata['text_blocks'] ?? [];
                        @endphp
                        @if(!empty($textBlocks))
                            @foreach($textBlocks as $block)
                                <div class="mb-4">
                                    @if(isset($block['subheading']))
                                        <h4 class="h6 fw-semibold mb-2" style="color: {{ $textColor }};">{{ $block['subheading'] }}</h4>
                                    @endif
                                    @if(isset($block['text']))
                                        <p style="color: {{ $textColor }};">{{ $block['text'] }}</p>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                        <div class="d-flex gap-3 mt-4">
                            @if($section->button_text)
                                <a href="{{ $section->button_link ?? '#' }}" class="btn btn-dark px-4 py-2" style="background-color: #000; border: none; border-radius: 0;">
                                    {{ $section->button_text }}
                                </a>
                            @endif
                            @if(isset($metadata['secondary_button_text']))
                                <a href="{{ $metadata['secondary_button_link'] ?? '#' }}" class="btn btn-light px-4 py-2" style="background-color: #e5e7eb; color: #000; border: none; border-radius: 0;">
                                    {{ $metadata['secondary_button_text'] }}
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6 mt-4 mt-lg-0">
                        @if($section->image_path)
                            <img src="{{ asset('storage/' . $section->image_path) }}" alt="{{ $section->title ?? 'Image' }}" class="img-fluid w-100" style="border-radius: 0;">
                        @endif
                    </div>
                </div>
            </div>
        </section>

    @elseif($section->type == 'two_column')
        <!-- 2-Column Section -->
        <section class="two-column-section py-5" style="{{ $style }}">
            <div class="container">
                @if($section->title)
                    <div class="mb-5 text-{{ $alignment }}">
                        <h2 class="fw-bold" style="color: {{ $textColor }}; font-size: 2.5rem;">{{ $section->title }}</h2>
                    </div>
                @endif
                <div class="row g-4">
                    @php
                        $twoColumnItems = $webpage->sections->where('type', 'column_item')->where('order', '>', $section->order)->take(2);
                    @endphp
                    @if($twoColumnItems->count() > 0)
                        @foreach($twoColumnItems as $item)
                            <div class="col-md-6 text-{{ $alignment }}">
                                @if($item->image_path)
                                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title ?? 'Image' }}" class="img-fluid w-100 mb-3" style="border-radius: 0;">
                                @endif
                                @if($item->title)
                                    <h3 class="h5 fw-semibold mb-2" style="color: {{ $textColor }};">{{ $item->title }}</h3>
                                @endif
                                @if($item->content)
                                    <p style="color: {{ $textColor }};">{{ $item->content }}</p>
                                @endif
                            </div>
                        @endforeach
                    @else
                        {{-- Fallback for single section --}}
                        <div class="col-md-6 text-{{ $alignment }}">
                            @if($section->image_path)
                                <img src="{{ asset('storage/' . $section->image_path) }}" alt="Image" class="img-fluid w-100 mb-3" style="border-radius: 0;">
                            @endif
                            @if($subtitle)
                                <h3 class="h5 fw-semibold mb-2" style="color: {{ $textColor }};">{{ $subtitle }}</h3>
                            @endif
                            @if($section->content)
                                <p style="color: {{ $textColor }};">{{ $section->content }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </section>

    @elseif($section->type == 'testimonials_grid' || $section->type == 'testimonial')
        <!-- Testimonials Grid Section -->
        <section class="testimonials-grid-section py-5" style="{{ $style }}">
            <div class="container">
                @if($section->title)
                    <div class="mb-5 text-{{ $alignment }}">
                        <h2 class="fw-bold" style="color: {{ $textColor }}; font-size: 2.5rem;">{{ $section->title }}</h2>
                    </div>
                @endif
                <div class="row g-4">
                    @php
                        $testimonials = $metadata['testimonials'] ?? [];
                    @endphp
                    @if(!empty($testimonials))
                        @foreach($testimonials as $testimonial)
                            <div class="col-md-4">
                                <div class="card h-100 p-4" style="background-color: #f3f4f6; border: none; border-radius: 8px;">
                                    @if(isset($testimonial['text']) && $testimonial['text'])
                                        <p class="mb-3" style="color: {{ $textColor }};">"{{ $testimonial['text'] }}"</p>
                                    @endif
                                    <div class="d-flex align-items-center">
                                        @if(isset($testimonial['image']) && $testimonial['image'])
                                            <img src="{{ asset('storage/' . $testimonial['image']) }}" alt="Profile" class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle me-3" style="width: 50px; height: 50px; background-color: #9ca3af;"></div>
                                        @endif
                                        <div>
                                            @if(isset($testimonial['name']) && $testimonial['name'])
                                                <strong style="color: {{ $textColor }};">{{ $testimonial['name'] }}</strong>
                                            @endif
                                            @if(isset($testimonial['description']) && $testimonial['description'])
                                                <p class="mb-0 small" style="color: {{ $textColor }};">{{ $testimonial['description'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        {{-- Fallback: Show empty cards if no testimonials --}}
                        @for($i = 0; $i < 3; $i++)
                            <div class="col-md-4">
                                <div class="card h-100 p-4" style="background-color: #f3f4f6; border: none; border-radius: 8px;">
                                    <p class="mb-3" style="color: {{ $textColor }};">"A terrific piece of praise"</p>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle me-3" style="width: 50px; height: 50px; background-color: #9ca3af;"></div>
                                        <div>
                                            <strong style="color: {{ $textColor }};">Name</strong>
                                            <p class="mb-0 small" style="color: {{ $textColor }};">Description</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    @endif
                </div>
            </div>
        </section>

    @elseif($section->type == 'heading')
        <!-- Section Heading -->
        <section class="heading-section py-4" style="{{ $style }}">
            <div class="container">
                <div class="text-{{ $alignment }}">
                    <h2 class="fw-bold" style="color: {{ $textColor }}; font-size: 2.5rem;">{{ $section->title ?? $section->content }}</h2>
                </div>
            </div>
        </section>

    @elseif($section->type == 'content')
        <!-- Content Block -->
        <section class="content-section py-5" style="{{ $style }}">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 mx-auto text-{{ $alignment }}">
                        @if($section->title)
                            <h2 class="h3 mb-4" style="color: {{ $textColor }};">{{ $section->title }}</h2>
                        @endif
                        @if($subtitle)
                            <h3 class="h5 mb-3" style="color: {{ $textColor }};">{{ $subtitle }}</h3>
                        @endif
                        @if($section->content)
                            <div class="content-text" style="color: {{ $textColor }};">{!! nl2br(e($section->content)) !!}</div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

    @elseif($section->type == 'image')
        <!-- Image Section -->
        <section class="image-section py-4" style="{{ $style }}">
            <div class="container">
                @if($section->image_path)
                    <div class="text-{{ $alignment }}">
                        <img src="{{ asset('storage/' . $section->image_path) }}" alt="{{ $section->title ?? 'Image' }}" class="img-fluid w-100" style="border-radius: 0;">
                        @if($section->title)
                            <p class="mt-3" style="color: {{ $textColor }};"><strong>{{ $section->title }}</strong></p>
                        @endif
                    </div>
                @endif
            </div>
        </section>

    @elseif($section->type == 'cta' || $section->type == 'footer_cta')
        <!-- Call to Action / Footer CTA Section -->
        <section class="cta-section py-5" style="{{ $style }}">
            <div class="container">
                @if($section->title)
                    <h2 class="fw-bold mb-4 text-center" style="color: {{ $textColor }}; font-size: 2.5rem;">{{ $section->title }}</h2>
                @endif
                <div class="d-flex justify-content-center gap-3">
                    @if($section->button_text)
                        <a href="{{ $section->button_link ?? '#' }}" class="btn btn-dark px-4 py-2" style="background-color: #000; border: none; border-radius: 0;">
                            {{ $section->button_text }}
                        </a>
                    @endif
                    @if(isset($metadata['secondary_button_text']))
                        <a href="{{ $metadata['secondary_button_link'] ?? '#' }}" class="btn btn-light px-4 py-2" style="background-color: #e5e7eb; color: #000; border: none; border-radius: 0;">
                            {{ $metadata['secondary_button_text'] }}
                        </a>
                    @endif
                </div>
            </div>
        </section>

    @elseif($section->type == 'button')
        <!-- Button Section -->
        <section class="button-section py-4" style="{{ $style }}">
            <div class="container">
                <div class="text-{{ $alignment }}">
                    @if($section->button_text)
                        <a href="{{ $section->button_link ?? '#' }}" class="btn btn-lg {{ $section->button_style == 'outline' ? 'btn-outline-primary' : ($section->button_style == 'secondary' ? 'btn-secondary' : 'btn-dark') }}" style="border-radius: 0;">
                            {{ $section->button_text }}
                        </a>
                    @endif
                </div>
            </div>
        </section>

    @endif
@endforeach

<style>
    .hero-section {
        padding: 4rem 0;
    }
    .features-grid-section, .two-column-section, .testimonials-grid-section {
        padding: 5rem 0;
    }
    .text-image-split-section {
        padding: 5rem 0;
    }
    .cta-section {
        padding: 4rem 0;
    }
    section img {
        max-width: 100%;
        height: auto;
    }
    @media (max-width: 768px) {
        .display-2 {
            font-size: 2rem !important;
        }
        section {
            padding: 3rem 0 !important;
        }
    }
</style>
