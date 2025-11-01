<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}">
    @endif

    <!-- TailwindPlus Elements for dialog/popover commands -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
</head>

<body class="min-h-screen flex flex-col w-full">
    {{-- <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
        @if (Route::has('login'))
            <nav class="flex items-center justify-end gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">Dashboard</a>
                @else
                    <a href="{{ route('login') }}"
                        class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal">Log
                        in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">Register</a>
                    @endif
                @endauth
            </nav>
        @endif
    </header> --}}
    <x-ecommerce-nav />
    {{-- Mobile banner (visible on small screens) --}}
    <banner class="block lg:hidden h-[50vh] sm:h-[40vh] overflow-hidden">
        <div>
            <img class="object-cover w-full h-full" src="{{ asset('img/family-with-little-son-car-salon.jpg') }}"
                alt="Family in car" />
        </div>
    </banner>

    {{-- Desktop / large banner (visible on lg and up) --}}
    <banner class="hidden lg:block h-[70vh] md:h-[55vh] overflow-hidden">
        <div class="relative">
            <img class="object-cover w-full h-full" src="{{ asset('img/family-with-little-son-car-salon.jpg') }}"
                alt="Family in car" />
            <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent"></div>
            <div
                class="absolute lg:hidden xl:top-64 xl:right-64 xl:flex items-center justify-center z-10 d-flex flex-col bg-gradient-to-b from-transparent to-black/60 p-6 rounded-lg">
                <h1 class="text-white text-4xl md:text-6xl font-bold drop-shadow-lg">Welcome to
                    {{ config('app.name', 'Laravel') }}</h1>
                <div class="p-4 text-white rotating-text-container">
                    <span class="rotating-text text-green-500">Get ready for an amazing
                        experience!</span>
                    <span class="rotating-text text-green-500">Explore our wide range of cars and
                        services
                        collected
                        just for
                        you.</span>
                    <span class="rotating-text text-green-500">Your dream car is just a few clicks
                        away.</span>
                    <span class="rotating-text text-green-500">Get your car today!</span>
                </div>
            </div>
            <div
                class="absolute xl:hidden lg:visible inset-0 lg:flex items-center justify-center z-10 d-flex flex-col bg-gradient-to-b from-transparent to-black/60 p-6 rounded-lg">
                <h1 class="text-white text-4xl md:text-6xl font-bold drop-shadow-lg">Welcome to
                    {{ config('app.name', 'Laravel') }}</h1>
                <div class="p-4 text-white rotating-text-container">
                    <span class="rotating-text text-green-500">Get ready for an amazing
                        experience!</span>
                    <span class="rotating-text text-green-500">Explore our wide range of cars and
                        services
                        collected
                        just for
                        you.</span>
                    <span class="rotating-text text-green-500">Your dream car is just a few clicks
                        away.</span>
                    <span class="rotating-text text-green-500">Get your car today!</span>
                </div>
            </div>
        </div>
    </banner>
    <main class="flex-1 w-full mx-auto">
        <x-category-home />
        <x-promo-section />
        <x-partner-logos />
    </main>

    @if (Route::has('login'))
        <div class="h-14.5 hidden lg:block"></div>
    @endif
    <x-ecommerce-footer />
    {{-- Shopping cart drawer (must be present in the DOM so header trigger can open it) --}}
    <x-shopping-cart />
</body>

</html>
