<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $webpage->meta_description }}">
    <meta name="keywords" content="{{ $webpage->meta_keywords }}">
    <title>{{ $webpage->title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6366f1;
            --secondary-color: #8b5cf6;
            --accent-color: #ec4899;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-light: #f9fafb;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            color: #000;
            line-height: 1.6;
            overflow-x: hidden;
            background-color: #fff;
        }
        
        .navbar {
            background-color: #fff;
            padding: 1.5rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark) !important;
            letter-spacing: -0.5px;
        }
        
        .nav-link {
            color: var(--text-dark) !important;
            font-weight: 500;
            margin: 0 0.5rem;
            transition: color 0.3s ease;
        }
        
        .nav-link:hover {
            color: var(--primary-color) !important;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            padding: 0.75rem 2rem;
            font-weight: 600;
            border-radius: 8px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
        }
        
        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            background: transparent;
            padding: 0.75rem 2rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background-color: #6b7280;
            border: none;
            padding: 0.75rem 2rem;
            font-weight: 600;
            border-radius: 8px;
        }
        
        section {
            position: relative;
        }
        
        .hero-section {
            min-height: 90vh;
            display: flex;
            align-items: center;
            padding: 4rem 0;
        }
        
        .display-3 {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -2px;
        }
        
        .content-section {
            padding: 5rem 0;
        }
        
        .features-section {
            padding: 5rem 0;
        }
        
        .testimonial-section {
            padding: 5rem 0;
        }
        
        .cta-section {
            padding: 5rem 0;
            border-radius: 20px;
            margin: 3rem 0;
        }
        
        .image-text-section img {
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        }
        
        .gallery-section .row img {
            border-radius: 12px;
            transition: transform 0.3s ease;
        }
        
        .gallery-section .row img:hover {
            transform: scale(1.05);
        }
        
        @media (max-width: 768px) {
            .display-3 {
                font-size: 2.5rem;
            }
            .hero-section {
                min-height: 70vh;
                padding: 2rem 0;
            }
        }
        
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Animation on scroll */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        section {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>
</head>
<body>
    <!-- Navigation Header -->
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #fff; padding: 1.5rem 0;">
        <div class="container">
            <a class="navbar-brand" href="/" style="font-size: 1rem; font-weight: 400; color: #000;">{{ $webpage->title ?? 'Site name' }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="#" style="color: #000; font-size: 0.9rem; margin-right: 1.5rem;">Page</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" style="color: #000; font-size: 0.9rem; margin-right: 1.5rem;">Page</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" style="color: #000; font-size: 0.9rem; margin-right: 1.5rem;">Page</a>
                    </li>
                    <li class="nav-item">
                        @auth
                            <a class="btn btn-dark ms-2" href="{{ route('dashboard') }}" style="background-color: #000; border: none; border-radius: 0; padding: 0.5rem 1.5rem; font-size: 0.9rem;">Dashboard</a>
                        @else
                            <a class="btn btn-dark ms-2" href="{{ route('login') }}" style="background-color: #000; border: none; border-radius: 0; padding: 0.5rem 1.5rem; font-size: 0.9rem;">Login</a>
                        @endauth
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main>
        @if($webpage->type == 'landing')
            @include('webpages.types.landing')
        @elseif($webpage->type == 'article')
            @include('webpages.types.article')
        @elseif($webpage->type == 'shop')
            @include('webpages.types.shop')
        @endif
    </main>

    <!-- Footer -->
    <footer class="py-5 mt-5" style="background-color: #fff;">
        <div class="container">
            @php
                // Get footer sections
                $footerSections = $webpage->sections->where('type', 'footer_cta')->sortBy('order');
            @endphp
            @if($footerSections->count() > 0)
                @foreach($footerSections as $footerSection)
                    @php
                        $footerMetadata = $footerSection->metadata ?? [];
                        $footerTextColor = $footerMetadata['text_color'] ?? '#000000';
                    @endphp
                    @if($footerSection->title)
                        <h2 class="fw-bold mb-4 text-center" style="color: {{ $footerTextColor }}; font-size: 2.5rem;">{{ $footerSection->title }}</h2>
                    @endif
                    <div class="d-flex justify-content-center gap-3 mb-5">
                        @if($footerSection->button_text)
                            <a href="{{ $footerSection->button_link ?? '#' }}" class="btn btn-dark px-4 py-2" style="background-color: #000; border: none; border-radius: 0;">
                                {{ $footerSection->button_text }}
                            </a>
                        @endif
                        @if(isset($footerMetadata['secondary_button_text']))
                            <a href="{{ $footerMetadata['secondary_button_link'] ?? '#' }}" class="btn btn-light px-4 py-2" style="background-color: #e5e7eb; color: #000; border: none; border-radius: 0;">
                                {{ $footerMetadata['secondary_button_text'] }}
                            </a>
                        @endif
                    </div>
                @endforeach
            @endif
            
            <div class="row align-items-center py-4" style="border-top: 1px solid #e5e7eb;">
                <div class="col-md-6 mb-3 mb-md-0">
                    <p class="mb-0" style="color: #000; font-size: 0.9rem;">{{ $webpage->title ?? 'Site name' }}</p>
                </div>
                <div class="col-md-6 text-md-end">
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-4 mb-3">
                    <h6 class="fw-bold mb-3" style="color: #000; font-size: 0.9rem;">Topic</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" style="color: #6b7280; text-decoration: none; font-size: 0.9rem;">Page</a></li>
                        <li><a href="#" style="color: #6b7280; text-decoration: none; font-size: 0.9rem;">Page</a></li>
                        <li><a href="#" style="color: #6b7280; text-decoration: none; font-size: 0.9rem;">Page</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-3">
                    <h6 class="fw-bold mb-3" style="color: #000; font-size: 0.9rem;">Topic</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" style="color: #6b7280; text-decoration: none; font-size: 0.9rem;">Page</a></li>
                        <li><a href="#" style="color: #6b7280; text-decoration: none; font-size: 0.9rem;">Page</a></li>
                        <li><a href="#" style="color: #6b7280; text-decoration: none; font-size: 0.9rem;">Page</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-3">
                    <h6 class="fw-bold mb-3" style="color: #000; font-size: 0.9rem;">Topic</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" style="color: #6b7280; text-decoration: none; font-size: 0.9rem;">Page</a></li>
                        <li><a href="#" style="color: #6b7280; text-decoration: none; font-size: 0.9rem;">Page</a></li>
                        <li><a href="#" style="color: #6b7280; text-decoration: none; font-size: 0.9rem;">Page</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>

