<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Inventaris Lab' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2F3185',
                        secondary: '#F5C23E',
                        light: '#FEFEFE',
                        surface: '#F2F2F2'
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Ensure buttons are always visible and clickable */
        button {
            cursor: pointer !important;
            pointer-events: auto !important;
        }

        button:hover {
            opacity: 0.8 !important;
        }

        /* Fix for invisible buttons */
        .btn-invisible-fix {
            background-color: rgba(59, 130, 246, 0.1) !important;
            border: 1px solid rgba(59, 130, 246, 0.3) !important;
        }

        .btn-invisible-fix:hover {
            background-color: rgba(59, 130, 246, 0.2) !important;
        }

        /* Allow scrolling on all screen sizes */
        html, body {
            height: auto;
            overflow: visible;
        }

        /* Ensure main content can scroll properly */
        @media (min-width: 1280px) {
            main {
                max-height: calc(100vh - 80px); /* Adjust based on header height */
                overflow-y: auto;
            }
        }

        /* Ensure content areas scroll properly */
        .overflow-y-auto {
            scrollbar-width: thin;
            scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
        }

        .overflow-y-auto::-webkit-scrollbar {
            width: 6px;
        }

        .overflow-y-auto::-webkit-scrollbar-track {
            background: transparent;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.5);
            border-radius: 3px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background-color: rgba(156, 163, 175, 0.7);
        }

        /* Mobile-specific improvements */
        @media (max-width: 1023px) {
            /* Ensure mobile/tablet sidebar is full width when shown */
            #sidebar {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 40;
                height: 100vh;
                transform: translateY(0);
            }

            /* Add backdrop when mobile menu is open */
            #sidebar:not(.hidden)::before {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: -1;
            }

            /* Improve touch targets */
            button, a {
                min-height: 44px;
            }
        }
    </style>
</head>

<body class="bg-surface xl:h-full xl:overflow-hidden">
    <div class="flex flex-col lg:flex-row xl:h-screen xl:overflow-hidden">
        {{-- Mobile/Tablet Header --}}
        <div class="lg:hidden bg-primary p-4 flex items-center justify-between">
            <h1 class="text-white text-lg font-medium">
                Inventaris Lab Teknik Informatika
            </h1>
            <button id="mobile-menu-toggle" class="text-white p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        {{-- Sidebar --}}
        <aside id="sidebar" class="w-full lg:w-64 bg-primary flex flex-col lg:h-screen hidden lg:flex">
            {{-- Mobile Header with Close Button --}}
            <div class="lg:hidden p-4 flex items-center justify-between border-b border-white/20">
                <h1 class="text-white text-lg font-medium">
                    Inventaris Lab Teknik Informatika
                </h1>
                <button id="mobile-menu-close" class="text-white p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="p-6 hidden lg:block">
                <h1 class="text-white text-lg font-medium leading-tight">
                    Inventaris Lab<br>
                    Teknik Informatika
                </h1>
            </div>
            {{-- Navigation --}}
            <nav class="px-6 space-y-6 flex-grow relative before:absolute before:left-0 before:top-0 before:bottom-0 before:w-[2px] before:bg-white/20">
                <a href="/dashboard" class="flex items-center text-black gap-3 relative pl-4 hover:before:opacity-100 before:absolute before:left-0 before:top-1/2 before:-translate-y-1/2 before:h-8 before:w-[2px] before:bg-white before:opacity-0 before:transition-opacity">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 9.5L12 17L22 9.5L12 2Z" />
                    </svg>
                    <span class="font-medium text-white">DASHBOARD</span>
                </a>

                @foreach ($labs ?? [] as $lab)
                <a href="/labs/{{ Str::slug($lab->name) }}"
                    class="flex items-center gap-3 transition-colors group relative pl-4 hover:before:opacity-100 before:absolute before:left-0 before:top-1/2 before:-translate-y-1/2 before:h-8 before:w-[2px] before:bg-white before:opacity-0 before:transition-opacity">
                    <span class="text-white">
                        @if($lab->name === 'LAB FKI')
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20 18c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h16zm0-12v8H4V6h16zm-7 13h2v-2h-2v2zm-4 0h2v-2h-2v2zM9 17h2v-2H9v2z" />
                        </svg>
                        @elseif($lab->name === 'LAB LABORAN')
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4V6h16v12z" />
                            <path d="M6 10h12v2H6zm0 4h8v2H6z" />
                        </svg>
                        @elseif($lab->name === 'LAB SI')
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z" />
                            <path d="M12 6c-3.31 0-6 2.69-6 6s2.69 6 6 6 6-2.69 6-6-2.69-6-6-6zm0 10c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4z" />
                            <circle cx="12" cy="12" r="2" />
                        </svg>
                        @elseif($lab->name === 'LAB RPL')
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M3 3h18v2H3zm0 16h18v2H3zm0-8h18v2H3zm8-4h10v2H11zm0 8h10v2H11zm-8-2l4-4-4-4z" />
                        </svg>
                        @elseif($lab->name === 'LAB JARKOM')
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z" />
                        </svg>
                        @elseif($lab->name === 'LAB SIC')
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9 9-4.03 9-9-4.03-9-9-9zm0 16c-3.86 0-7-3.14-7-7s3.14-7 7-7 7 3.14 7 7-3.14 7-7 7z" />
                            <path d="M13 7h-2v6h6v-2h-4z" />
                            <path d="M12 17c2.76 0 5-2.24 5-5h-2c0 1.66-1.34 3-3 3s-3-1.34-3-3H7c0 2.76 2.24 5 5 5z" />
                        </svg>
                        @endif
                    </span>
                    <span class="font-medium text-white">{{ $lab->name }}</span>
                </a>
                @endforeach
            </nav> {{-- Logout Section --}}
            <div class="p-6">
                <form method="POST" action="{{ url('/logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-[#DC2626] text-white py-2.5 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z" />
                        </svg>
                        <span class="font-medium">LOGOUT</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 p-4 lg:p-6">
            @yield('content')
        </main>
    </div>

    {{-- Scripts --}}
    @stack('scripts')
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    {{-- Mobile Menu Toggle --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
            const mobileMenuClose = document.getElementById('mobile-menu-close');
            const sidebar = document.getElementById('sidebar');

            function closeMobileMenu() {
                sidebar.classList.add('hidden');
                document.body.style.overflow = '';
                // Reset to hamburger icon
                const icon = mobileMenuToggle.querySelector('svg path');
                icon.setAttribute('d', 'M4 6h16M4 12h16M4 18h16');
            }

            if (mobileMenuToggle && sidebar) {
                mobileMenuToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('hidden');

                    // Toggle body scroll lock on mobile/tablet
                    if (window.innerWidth < 1024) {
                        if (sidebar.classList.contains('hidden')) {
                            document.body.style.overflow = '';
                        } else {
                            document.body.style.overflow = 'hidden';
                        }
                    }

                    // Change hamburger to X when open
                    const icon = this.querySelector('svg path');
                    if (sidebar.classList.contains('hidden')) {
                        // Hamburger icon
                        icon.setAttribute('d', 'M4 6h16M4 12h16M4 18h16');
                    } else {
                        // X icon
                        icon.setAttribute('d', 'M6 18L18 6M6 6l12 12');
                    }
                });

                // Handle close button
                if (mobileMenuClose) {
                    mobileMenuClose.addEventListener('click', closeMobileMenu);
                }

                // Close sidebar when clicking on a link (mobile/tablet)
                const sidebarLinks = sidebar.querySelectorAll('a');
                sidebarLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        if (window.innerWidth < 1024) {
                            closeMobileMenu();
                        }
                    });
                });

                // Close sidebar when clicking outside (mobile/tablet)
                document.addEventListener('click', function(e) {
                    if (window.innerWidth < 1024 &&
                        !sidebar.contains(e.target) &&
                        !mobileMenuToggle.contains(e.target) &&
                        !sidebar.classList.contains('hidden')) {
                        closeMobileMenu();
                    }
                });
            }
        });
    </script>
</body>

</html>