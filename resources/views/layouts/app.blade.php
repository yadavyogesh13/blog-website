<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    
    <!-- SEO Meta Tags -->
    @yield('meta')
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        accent: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        }
                    },
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                        'serif': ['Playfair Display', 'Georgia', 'serif'],
                    }
                }
            }
        }
    </script>
    
    <style>
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .line-clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
        
        /* Smooth transitions for footer elements */
        footer a, footer button {
            transition: all 0.3s ease;
        }

        /* Back to top button styles */
        #backToTop {
            transition: all 0.3s ease;
            opacity: 0;
            visibility: hidden;
        }

        #backToTop.visible {
            opacity: 1;
            visibility: visible;
        }

        /* Social icon hover effects */
        .social-hover:hover {
            transform: translateY(-2px);
            transition: transform 0.3s ease;
        }

        /* Newsletter input focus effects */
        footer input:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Category icon colors */
        .category-link:hover i {
            color: inherit;
        }

        /* Mobile responsiveness improvements */
        @media (max-width: 768px) {
            footer .grid {
                gap: 2rem;
            }
            
            footer .text-sm {
                font-size: 0.875rem;
            }
        }

        /* Dark mode enhancements */
        .dark footer input::placeholder {
            color: #9ca3af;
        }

        /* Loading animation for newsletter button */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .fa-spinner {
            animation: spin 1s linear infinite;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-white dark:bg-gray-900 dark:text-gray-100 transition-colors duration-300 min-h-full">
    <!-- Navigation -->
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-gray-900 dark:text-white">
                        BlogSite
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-700 dark:text-gray-200 hover:text-accent-600 dark:hover:text-accent-400 transition-colors">Home</a>
                    <a href="{{ route('posts.index') }}" class="text-gray-700 dark:text-gray-200 hover:text-accent-600 dark:hover:text-accent-400 transition-colors">Articles</a>
                    <a href="{{ route('categories.index') }}" class="text-gray-700 dark:text-gray-200 hover:text-accent-600 dark:hover:text-accent-400 transition-colors">Categories</a>
                    <a href="{{ route('search') }}" class="text-gray-700 dark:text-gray-200 hover:text-accent-600 dark:hover:text-accent-400 transition-colors">Search</a>
                </div>

                <!-- Right Section -->
                <div class="flex items-center space-x-4">
                    <!-- Dark Mode Toggle -->
                    <button id="darkModeToggle" class="p-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <svg id="sunIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <svg id="moonIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                    </button>

                    @auth
                        <!-- Simple Logout Button -->
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button 
                                type="submit"
                                class="text-gray-700 dark:text-gray-200 hover:text-accent-600 dark:hover:text-accent-400 transition-colors"
                            >
                                Logout
                            </button>
                        </form>
                    @else
                        <!-- Auth Links -->
                        <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-200 hover:text-accent-600 dark:hover:text-accent-400 transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="bg-accent-500 text-white px-4 py-2 rounded-lg hover:bg-accent-600 transition-colors">Sign Up</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Enhanced Footer -->
    <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Main Footer Content -->
            <div class="py-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Brand Section -->
                <div class="lg:col-span-1">
                    <div class="flex items-center mb-4">
                        <a href="{{ route('home') }}" class="text-2xl font-bold text-gray-900 dark:text-white">
                            BlogSite
                        </a>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">
                        Discover insightful articles, tutorials, and stories. Join our community of readers and writers passionate about knowledge sharing.
                    </p>
                    <div class="flex space-x-4">
                        <!-- Social Links -->
                        <a href="#" class="text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200" aria-label="Facebook">
                            <i class="fab fa-facebook-f text-lg"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200" aria-label="Twitter">
                            <i class="fab fa-twitter text-lg"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-pink-600 dark:hover:text-pink-400 transition-colors duration-200" aria-label="Instagram">
                            <i class="fab fa-instagram text-lg"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors duration-200" aria-label="LinkedIn">
                            <i class="fab fa-linkedin-in text-lg"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors duration-200" aria-label="YouTube">
                            <i class="fab fa-youtube text-lg"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Links</h3>
                    <ul class="space-y-3">
                        <li>
                            <a href="{{ route('home') }}" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200 flex items-center">
                                <i class="fas fa-home mr-2 text-sm"></i>
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('posts.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200 flex items-center">
                                <i class="fas fa-newspaper mr-2 text-sm"></i>
                                All Articles
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('categories.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200 flex items-center">
                                <i class="fas fa-th-large mr-2 text-sm"></i>
                                Categories
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('search') }}" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200 flex items-center">
                                <i class="fas fa-search mr-2 text-sm"></i>
                                Search
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Categories -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top Categories</h3>
                    <ul class="space-y-3">
                        @php
                            $topCategories = [
                                'Technology' => 'fas fa-laptop-code',
                                'Programming' => 'fas fa-code',
                                'Web Development' => 'fas fa-globe',
                                'Lifestyle' => 'fas fa-heart',
                                'Business' => 'fas fa-briefcase',
                                'Health' => 'fas fa-heartbeat'
                            ];
                        @endphp
                        @foreach($topCategories as $category => $icon)
                        <li>
                            <a href="{{ route('search') }}?q={{ urlencode($category) }}" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200 flex items-center">
                                <i class="{{ $icon }} mr-2 text-sm"></i>
                                {{ $category }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Newsletter -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Stay Updated</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4 text-sm">
                        Get the latest articles and updates delivered directly to your inbox.
                    </p>
                    <form class="space-y-3 newsletter-form" id="newsletterForm">
                        @csrf
                        <input type="hidden" name="source" value="footer">
                        <div class="relative">
                            <input 
                                type="email" 
                                name="email"
                                id="newsletterEmail"
                                placeholder="Enter your email"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 pr-12"
                                required
                                aria-label="Email for newsletter"
                                aria-describedby="newsletter-error newsletter-success"
                            >
                            <button 
                                type="submit"
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 disabled:bg-gray-400 disabled:cursor-not-allowed"
                                aria-label="Subscribe to newsletter"
                                id="newsletterSubmit"
                            >
                                <i class="fas fa-paper-plane text-sm"></i>
                            </button>
                        </div>
                        
                        <!-- Status Messages -->
                        <div id="newsletter-success" class="hidden p-3 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-lg text-sm"></div>
                        <div id="newsletter-error" class="hidden p-3 bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 rounded-lg text-sm"></div>
                    </form>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                        No spam ever. Unsubscribe at any time.
                    </p>
                </div>
            </div>

            <!-- Bottom Footer -->
            <div class="border-t border-gray-200 dark:border-gray-700 py-6">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    <!-- Copyright -->
                    <div class="text-gray-600 dark:text-gray-400 text-sm">
                        <p>&copy; {{ date('Y') }} BlogSite. All rights reserved.</p>
                    </div>

                    <!-- Legal Links -->
                    <div class="flex flex-wrap items-center space-x-6 text-sm">
                        <a href="/privacy-policy" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                            Privacy Policy
                        </a>
                        <a href="/terms-of-service" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                            Terms of Service
                        </a>
                        <a href="/contact" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                            Contact
                        </a>
                        <a href="/sitemap.xml" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                            Sitemap
                        </a>
                    </div>

                    <!-- Back to Top -->
                    <button 
                        id="backToTop" 
                        class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200 flex items-center text-sm"
                        aria-label="Back to top"
                    >
                        <i class="fas fa-arrow-up mr-2"></i>
                        Back to Top
                    </button>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Dark Mode Toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        const sunIcon = document.getElementById('sunIcon');
        const moonIcon = document.getElementById('moonIcon');

        // Check for saved theme or prefer-color-scheme
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            sunIcon.classList.remove('hidden');
            moonIcon.classList.add('hidden');
        } else {
            document.documentElement.classList.remove('dark');
            sunIcon.classList.add('hidden');
            moonIcon.classList.remove('hidden');
        }

        darkModeToggle.addEventListener('click', () => {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
                sunIcon.classList.add('hidden');
                moonIcon.classList.remove('hidden');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
                sunIcon.classList.remove('hidden');
                moonIcon.classList.add('hidden');
            }
        });

        // Like functionality
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.like-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const postId = this.dataset.postId;
                    const likeCount = this.querySelector('.like-count');
                    
                    fetch(`/posts/${postId}/like`, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({})
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.liked) {
                            this.classList.add('text-red-500');
                            likeCount.textContent = data.likes_count;
                        } else {
                            this.classList.remove('text-red-500');
                            likeCount.textContent = data.likes_count;
                        }
                    })
                    .catch(error => console.error('Error:', error));
                });
            });
        });

        // Back to Top functionality
        const backToTopButton = document.getElementById('backToTop');
        
        if (backToTopButton) {
            // Show/hide back to top button based on scroll position
            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 300) {
                    backToTopButton.classList.remove('opacity-0', 'invisible');
                    backToTopButton.classList.add('opacity-100', 'visible');
                } else {
                    backToTopButton.classList.remove('opacity-100', 'visible');
                    backToTopButton.classList.add('opacity-0', 'invisible');
                }
            });

            // Smooth scroll to top
            backToTopButton.addEventListener('click', (e) => {
                e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }

        // Newsletter form handling
        document.addEventListener('DOMContentLoaded', function() {
            const newsletterForm = document.querySelector('footer form');
            
            if (newsletterForm) {
                newsletterForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const emailInput = this.querySelector('input[type="email"]');
                    const submitButton = this.querySelector('button[type="submit"]');
                    const originalText = submitButton.innerHTML;
                    
                    if (!emailInput.value) {
                        return;
                    }
                    
                    // Show loading state
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                    submitButton.disabled = true;
                    
                    // Simulate API call - replace with actual newsletter subscription
                    setTimeout(() => {
                        // Show success message
                        const successMessage = document.createElement('div');
                        successMessage.className = 'mt-3 p-3 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-lg text-sm';
                        successMessage.innerHTML = 'Thank you for subscribing! Check your email to confirm.';
                        
                        this.appendChild(successMessage);
                        emailInput.value = '';
                        
                        // Reset button after 3 seconds
                        setTimeout(() => {
                            submitButton.innerHTML = originalText;
                            submitButton.disabled = false;
                            successMessage.remove();
                        }, 3000);
                    }, 1000);
                });
            }
        });

        // Add smooth hover effects for social icons
        document.addEventListener('DOMContentLoaded', function() {
            const socialLinks = document.querySelectorAll('footer a[aria-label*="Facebook"], footer a[aria-label*="Twitter"], footer a[aria-label*="Instagram"], footer a[aria-label*="LinkedIn"], footer a[aria-label*="YouTube"]');
            
            socialLinks.forEach(link => {
                link.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                
                link.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });

        // Newsletter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const newsletterForm = document.getElementById('newsletterForm');
            const newsletterEmail = document.getElementById('newsletterEmail');
            const newsletterSubmit = document.getElementById('newsletterSubmit');
            const newsletterSuccess = document.getElementById('newsletter-success');
            const newsletterError = document.getElementById('newsletter-error');

            if (newsletterForm) {
                newsletterForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const email = newsletterEmail.value.trim();
                    const submitButton = newsletterSubmit;
                    const originalHTML = submitButton.innerHTML;

                    // Hide previous messages
                    newsletterSuccess.classList.add('hidden');
                    newsletterError.classList.add('hidden');

                    // Basic email validation
                    if (!email || !isValidEmail(email)) {
                        showNewsletterError('Please enter a valid email address.');
                        return;
                    }

                    // Show loading state
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                    submitButton.disabled = true;

                    try {
                        const response = await fetch('/newsletter/subscribe', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                email: email,
                                source: 'website_footer'
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            showNewsletterSuccess(data.message);
                            newsletterForm.reset();
                        } else {
                            showNewsletterError(data.message || 'Something went wrong. Please try again.');
                        }

                    } catch (error) {
                        console.error('Newsletter subscription error:', error);
                        showNewsletterError('Network error. Please check your connection and try again.');
                    } finally {
                        // Reset button
                        submitButton.innerHTML = originalHTML;
                        submitButton.disabled = false;
                    }
                });
            }

            function showNewsletterSuccess(message) {
                newsletterSuccess.textContent = message;
                newsletterSuccess.classList.remove('hidden');
                newsletterError.classList.add('hidden');
                
                // Auto-hide success message after 5 seconds
                setTimeout(() => {
                    newsletterSuccess.classList.add('hidden');
                }, 5000);
            }

            function showNewsletterError(message) {
                newsletterError.textContent = message;
                newsletterError.classList.remove('hidden');
                newsletterSuccess.classList.add('hidden');
            }

            function isValidEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }

            // Real-time email validation
            if (newsletterEmail) {
                newsletterEmail.addEventListener('blur', function() {
                    const email = this.value.trim();
                    if (email && !isValidEmail(email)) {
                        this.classList.add('border-red-500');
                    } else {
                        this.classList.remove('border-red-500');
                    }
                });

                newsletterEmail.addEventListener('input', function() {
                    this.classList.remove('border-red-500');
                    newsletterError.classList.add('hidden');
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>