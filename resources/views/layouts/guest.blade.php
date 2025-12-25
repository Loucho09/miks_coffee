<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=2">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.png') }}?v=2">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}?v=2">

    <title>{{ config('app.name', 'Miks Coffee') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700&display=swap" rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <script>
        // 🟢 FIXED: Synchronized with your tailwind.config.js 60-30-10 palette
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                // 🟢 SOPHISTICATED BEIGE & ESPRESSO THEME (60-30-10 Rule)
                stone: {
                    // LIGHT MODE: The 60% and 30% Beige connection
                    50: '#F5F2EA',  // 60% - Main Beige Background (Soft & Warm)
                    100: '#EBE6D9', // 30% - Secondary/Card Background
                    200: '#DED7C5', // 10% - Subtle Beige Borders
                    
                    // NEUTRAL STEPS: Typography and Accents
                    300: '#D1C8B1', //
                    400: '#B8AD91', //
                    500: '#8F8366', //
                    600: '#736852', //
                    700: '#574F3E', //
                    800: '#3B352A', //
                    
                    // DARK MODE: The "Connected" Charcoal-Beige
                    900: '#1A1816', // 30% - Secondary Dark BG (Warm Charcoal)
                    950: '#0C0B0A', // 60% - Primary Dark BG (Deep Espresso)
                    1000: '#FF0000', // Custom Red Preserved
                },
                
                // 🟢 ACCENT COLORS: 10% Branding
                amber: {
                    400: '#FBBF24', //
                    500: '#F59E0B', // Primary 10% Accent (Brand Orange)
                    600: '#D97706', //
                    700: '#B45309', //
                    1000: '#F59E0B', //
                },

                // 🟢 COFFEE SPECIFIC PALETTE
                'coffee': {
                    100: '#F5E6E0', //
                    600: '#8D5F46', //
                    800: '#4B2C20', //
                    900: '#2C1810', //
                },

                // 🟢 LEGACY BRANDING: Compatibility
                'brand': {
                    orange: '#F59E0B', //
                },

                'dashboard': {
                    1000: '#FF0000', //
                },
            },
            boxShadow: {
                // 🟢 NEW FEATURE: Custom Depth Effects
                // Soft shadow tuned for light beige backgrounds
                'beige': '0 20px 40px -15px rgba(143, 131, 102, 0.2)', //
                // Heavy, atmospheric shadow for dark theme depth
                'connected': '0 25px 60px -15px rgba(0, 0, 0, 0.7)', //
            },
            letterSpacing: {
                // For that premium high-end look on headers
                'widest': '0.4em',
            },
                }
            }
        }
    </script>

    <script>
        // 🟢 NEW FEATURE: Anti-Flash Theme Persistence
        // Immediately applies the correct theme color to the root before the body renders
        (function() {
            const theme = localStorage.getItem('color-theme') || 
                         (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
                document.documentElement.style.backgroundColor = '#0C0B0A';
            } else {
                document.documentElement.classList.remove('dark');
                document.documentElement.style.backgroundColor = '#F5F2EA';
            }
        })();
    </script>
</head>

<body class="font-sans antialiased transition-colors duration-500 bg-stone-100  text-stone-900 dark:bg-stone-950 dark:text-stone-50">
    
    <div class="fixed inset-0 pointer-events-none opacity-[0.03] dark:opacity-[0.05] z-[9999]" 
         style="background-image: url('https://www.transparenttextures.com/patterns/stardust.png');"></div>

    <div class="relative z-10">
        {{ $slot }}
    </div>

    <script>
        window.toggleTheme = function() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
                document.documentElement.style.backgroundColor = '#F5F2EA';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
                document.documentElement.style.backgroundColor = '#0C0B0A';
            }
        }
    </script>
</body>
</html>