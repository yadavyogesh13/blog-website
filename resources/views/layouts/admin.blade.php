<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        }
                    },
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-in-left': 'slideInLeft 0.3s ease-out',
                        'bounce-subtle': 'bounceSubtle 2s infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideInLeft: {
                            '0%': { transform: 'translateX(-100%)' },
                            '100%': { transform: 'translateX(0)' },
                        },
                        bounceSubtle: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-5px)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            transition-property: color, background-color, border-color, transform, opacity;
            transition-duration: 200ms;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar {
            background: linear-gradient(135deg, #1e3a8a 0%, #7c3aed 100%);
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.1);
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-left: 3px solid #ffffff;
        }

        .nav-link:hover:not(.active) {
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(8px);
        }

        .logo-gradient {
            background: linear-gradient(135deg, #ffffff 0%, #f0f9ff 100%);
        }

        .stats-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid rgba(226, 232, 240, 0.8);
            backdrop-filter: blur(10px);
        }

        @media (max-width: 768px) {
            .sidebar {
                box-shadow: 0 0 50px rgba(0, 0, 0, 0.3);
            }
        }

        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans antialiased">
    <!-- Mobile Menu Button -->
    <div class="mobile-menu-btn fixed top-6 left-6 z-50 lg:hidden">
        <button id="mobileMenuToggle" class="p-3 rounded-xl bg-primary-600 text-white shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
            <i class="fas fa-bars text-lg"></i>
        </button>
    </div>
    
    <!-- Overlay for mobile -->
    <div id="overlay" class="overlay fixed inset-0 bg-black bg-opacity-50 z-40 hidden transition-opacity duration-300"></div>
    
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar h-screen text-white fixed lg:relative z-40 w-80 lg:w-72 transform -translate-x-full lg:translate-x-0 transition-transform duration-300">
            <div class="flex flex-col h-full">
                <!-- Logo and Toggle -->
                <div class="p-6 border-b border-white/10">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 rounded-xl logo-gradient flex items-center justify-center shadow-lg">
                                <i class="fas fa-cog text-primary-600 text-xl"></i>
                            </div>
                            <div>
                                <h1 class="logo-text text-xl font-bold text-white">AdminPanel</h1>
                                <p class="text-white/60 text-sm">Management Console</p>
                            </div>
                        </div>
                        <button id="sidebarToggle" class="hidden lg:flex items-center justify-center w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 text-white/80 hover:text-white transition-all duration-200">
                            <i class="fas fa-chevron-left text-sm"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Navigation -->
                <div class="flex-1 overflow-y-auto custom-scrollbar py-6">
                    <ul class="space-y-2 px-4">
                        <li>
                            <a class="nav-link flex items-center p-4 rounded-xl hover:bg-white/10 relative transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                               href="{{ route('admin.dashboard') }}">
                                <div class="w-8 text-center">
                                    <i class="fas fa-tachometer-alt text-lg"></i>
                                </div>
                                <span class="nav-text ml-4 font-medium">Dashboard</span>
                                <div class="absolute right-4">
                                    <i class="fas fa-chevron-right text-xs text-white/40"></i>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link flex items-center p-4 rounded-xl hover:bg-white/10 relative transition-all duration-200 {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}" 
                               href="{{ route('admin.posts.index') }}">
                                <div class="w-8 text-center">
                                    <i class="fas fa-newspaper text-lg"></i>
                                </div>
                                <span class="nav-text ml-4 font-medium">Posts Management</span>
                                <div class="absolute right-4">
                                    <i class="fas fa-chevron-right text-xs text-white/40"></i>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link flex items-center p-4 rounded-xl hover:bg-white/10 relative transition-all duration-200 {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" 
                               href="{{ route('admin.categories.index') }}">
                                <div class="w-8 text-center">
                                    <i class="fas fa-folder text-lg"></i>
                                </div>
                                <span class="nav-text ml-4 font-medium">Categories</span>
                                <div class="absolute right-4">
                                    <i class="fas fa-chevron-right text-xs text-white/40"></i>
                                </div>
                            </a>
                        </li>
                        
                        <!-- Divider -->
                        <li class="pt-6 mt-4 border-t border-white/10">
                            <form method="POST" action="{{ route('admin.logout') }}" class="w-full">
                                @csrf
                                <button type="submit" class="nav-link flex items-center p-4 rounded-xl hover:bg-red-500/20 w-full text-left transition-all duration-200 group">
                                    <div class="w-8 text-center">
                                        <i class="fas fa-sign-out-alt text-lg text-red-300 group-hover:text-red-200"></i>
                                    </div>
                                    <span class="nav-text ml-4 font-medium text-red-300 group-hover:text-red-200">Logout</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
                
                <!-- User Info -->
                <div class="p-6 border-t border-white/10">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center shadow-lg">
                            <i class="fas fa-user-cog text-white/80"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-white truncate">Administrator</p>
                            <p class="text-xs text-white/60 truncate">admin@example.com</p>
                        </div>
                        <div class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div id="mainContent" class="main-content flex-1 min-h-screen transition-all duration-300">
            <div class="p-6 lg:p-8">
                <!-- Notifications -->
                <div class="mb-8 space-y-4 max-w-6xl mx-auto">
                    @if(session('success'))
                        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-xl shadow-sm animate-fade-in">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-green-500 text-lg"></i>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                                </div>
                                <button type="button" class="ml-auto pl-3 text-green-500 hover:text-green-700 transition-colors duration-200">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm animate-fade-in">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-red-500 text-lg"></i>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-red-700 font-medium">{{ session('error') }}</p>
                                </div>
                                <button type="button" class="ml-auto pl-3 text-red-500 hover:text-red-700 transition-colors duration-200">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Page Content -->
                <div class="max-6xl mx-auto">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const overlay = document.getElementById('overlay');
            const mainContent = document.getElementById('mainContent');
            
            // Desktop sidebar toggle
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    
                    // Change icon based on state
                    const icon = sidebarToggle.querySelector('i');
                    if (sidebar.classList.contains('collapsed')) {
                        icon.classList.remove('fa-chevron-left');
                        icon.classList.add('fa-chevron-right');
                    } else {
                        icon.classList.remove('fa-chevron-right');
                        icon.classList.add('fa-chevron-left');
                    }
                });
            }
            
            // Mobile menu toggle
            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('mobile-open');
                    overlay.classList.toggle('active');
                });
            }
            
            // Close mobile menu when clicking overlay
            if (overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('mobile-open');
                    overlay.classList.remove('active');
                });
            }
            
            // Close alerts when clicking the close button
            document.querySelectorAll('.bg-green-50 button, .bg-red-50 button').forEach(button => {
                button.addEventListener('click', function() {
                    this.closest('.bg-green-50, .bg-red-50').style.display = 'none';
                });
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>