<div class="container my-5">
    @foreach($webpage->sections as $section)
        @if($section->type == 'heading')
            <h1 class="display-4 mb-4">{{ $section->title ?? $section->content }}</h1>
        @elseif($section->type == 'content')
            <div class="mb-4">
                @if($section->title)
                    <h2 class="section-heading">{{ $section->title }}</h2>
                @endif
                @if($section->content)
                    <p>{{ $section->content }}</p>
                @endif
            </div>
        @elseif($section->type == 'subheading')
            <h3 class="subheading mb-3">{{ $section->title ?? $section->content }}</h3>
        @elseif($section->type == 'description')
            <p class="mb-4">{{ $section->content }}</p>
        @elseif($section->type == 'image')
            @if($section->image_path)
                <img src="{{ asset('storage/' . $section->image_path) }}" alt="{{ $section->title ?? 'Product image' }}" class="section-image">
            @endif
        @elseif($section->type == 'button')
            <div class="my-4">
                @if($section->button_text)
                    <a href="{{ $section->button_link ?? '#' }}" class="btn {{ $section->button_style == 'secondary' ? 'btn-secondary' : 'btn-primary' }}">
                        {{ $section->button_text }}
                    </a>
                @endif
            </div>
        @endif
    @endforeach
</div>

