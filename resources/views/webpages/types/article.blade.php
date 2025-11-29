<div class="container my-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            @foreach($webpage->sections as $section)
                @if($section->type == 'heading')
                    <h1 class="display-4 mb-4">{{ $section->title ?? $section->content }}</h1>
                @elseif($section->type == 'content')
                    <div class="mb-4">
                        @if($section->title)
                            <h2>{{ $section->title }}</h2>
                        @endif
                        @if($section->content)
                            <p>{{ $section->content }}</p>
                        @endif
                    </div>
                @elseif($section->type == 'image')
                    @if($section->image_path)
                        <img src="{{ asset('storage/' . $section->image_path) }}" alt="{{ $section->title ?? 'Image' }}" class="img-fluid mb-4">
                    @endif
                @endif
            @endforeach

            <!-- Related Articles Section -->
            @if($webpage->sections->where('type', 'related')->count() > 0)
                <div class="mt-5">
                    <h3 class="mb-4">Related articles or posts</h3>
                    <div class="row">
                        @foreach($webpage->sections->where('type', 'related') as $related)
                            <div class="col-md-4 mb-4">
                                @if($related->image_path)
                                    <img src="{{ asset('storage/' . $related->image_path) }}" alt="{{ $related->title }}" class="img-fluid mb-2">
                                @endif
                                @if($related->title)
                                    <h5>{{ $related->title }}</h5>
                                @endif
                                @if($related->content)
                                    <p class="small">{{ $related->content }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

