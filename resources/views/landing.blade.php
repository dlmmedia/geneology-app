<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) ?: 'en' }}" dir="ltr" x-data="tallstackui_darkTheme({ dark: false })" x-bind:class="{'dark bg-gray-900': darkTheme, 'bg-white': !darkTheme}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="DLM genealogy - Discover your family history and build your family tree with ease.">

    <title>DLM Genealogy - Discover Your Family History</title>

    <!-- favicon -->
    <link rel="icon" type="image/png" href="{{ asset('img/favicon/favicon-16x16.png') }}" sizes="16x16">
    <link rel="icon" type="image/png" href="{{ asset('img/favicon/favicon-32x32.png') }}" sizes="32x32">
    <link rel="icon" type="image/png" href="{{ asset('img/favicon/favicon-96x96.png') }}" sizes="96x96">

    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" />

    <!-- scripts -->
    <tallstackui:script />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- styles -->
    @livewireStyles
    @stack('styles')
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <!-- Navigation -->
        <nav class="sticky top-0 z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center space-x-3">
                        <x-svg.genealogy class="size-10 fill-blue-600 dark:fill-blue-400" alt="DLM Genealogy" />
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">DLM Genealogy</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <x-ts-theme-switch only-icons />
                        <form method="POST" action="{{ route('launch') }}">
                            @csrf
                            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                                Launch App
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-24 lg:py-32">
                <div class="text-center">
                    <h1 class="text-5xl sm:text-6xl lg:text-7xl font-bold text-gray-900 dark:text-white mb-6">
                        Discover Your
                        <span class="text-blue-600 dark:text-blue-400">Family History</span>
                    </h1>
                    <p class="text-xl sm:text-2xl text-gray-600 dark:text-gray-300 mb-10 max-w-3xl mx-auto">
                        Build and explore your family tree with our powerful genealogy platform. 
                        Preserve your heritage and connect generations.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <form method="POST" action="{{ route('launch') }}">
                            @csrf
                            <button type="submit" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white text-lg font-semibold rounded-lg shadow-xl hover:shadow-2xl transition-all duration-200 transform hover:scale-105 flex items-center space-x-2">
                                <span>Get Started</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-20 bg-white dark:bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">Powerful Features</h2>
                    <p class="text-xl text-gray-600 dark:text-gray-300">Everything you need to build and manage your family tree</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="p-6 bg-gray-50 dark:bg-gray-700 rounded-xl hover:shadow-lg transition-shadow">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mb-4">
                            <x-ts-icon icon="tabler.users" class="size-6 text-blue-600 dark:text-blue-400" />
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Family Tree Builder</h3>
                        <p class="text-gray-600 dark:text-gray-300">Create comprehensive family trees with unlimited generations and relationships.</p>
                    </div>
                    <div class="p-6 bg-gray-50 dark:bg-gray-700 rounded-xl hover:shadow-lg transition-shadow">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mb-4">
                            <x-ts-icon icon="tabler.photo" class="size-6 text-blue-600 dark:text-blue-400" />
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Photo Management</h3>
                        <p class="text-gray-600 dark:text-gray-300">Upload and organize family photos with automatic watermarking and galleries.</p>
                    </div>
                    <div class="p-6 bg-gray-50 dark:bg-gray-700 rounded-xl hover:shadow-lg transition-shadow">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mb-4">
                            <x-ts-icon icon="tabler.search" class="size-6 text-blue-600 dark:text-blue-400" />
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Advanced Search</h3>
                        <p class="text-gray-600 dark:text-gray-300">Search through your family members quickly and efficiently.</p>
                    </div>
                    <div class="p-6 bg-gray-50 dark:bg-gray-700 rounded-xl hover:shadow-lg transition-shadow">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mb-4">
                            <x-ts-icon icon="tabler.file-code" class="size-6 text-blue-600 dark:text-blue-400" />
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">GEDCOM Support</h3>
                        <p class="text-gray-600 dark:text-gray-300">Import and export your family tree using industry-standard GEDCOM format.</p>
                    </div>
                    <div class="p-6 bg-gray-50 dark:bg-gray-700 rounded-xl hover:shadow-lg transition-shadow">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mb-4">
                            <x-ts-icon icon="tabler.share" class="size-6 text-blue-600 dark:text-blue-400" />
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Team Collaboration</h3>
                        <p class="text-gray-600 dark:text-gray-300">Work together with family members to build your family tree.</p>
                    </div>
                    <div class="p-6 bg-gray-50 dark:bg-gray-700 rounded-xl hover:shadow-lg transition-shadow">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mb-4">
                            <x-ts-icon icon="tabler.cake" class="size-6 text-blue-600 dark:text-blue-400" />
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Birthday Tracking</h3>
                        <p class="text-gray-600 dark:text-gray-300">Never miss a family birthday with our birthday tracking feature.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Sample Datasets Section -->
        <section class="py-20 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">Explore Sample Datasets</h2>
                    <p class="text-xl text-gray-600 dark:text-gray-300">Browse through example family trees to see what's possible</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 hover:shadow-xl transition-shadow">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                <x-ts-icon icon="tabler.crown" class="size-8 text-blue-600 dark:text-blue-400" />
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">British Royals</h3>
                                <p class="text-gray-600 dark:text-gray-300">The British Royal Family</p>
                            </div>
                        </div>
                        <p class="text-gray-700 dark:text-gray-300 mb-6">
                            Explore the family tree of the British Royal Family, including members around Queen Elizabeth II. 
                            This comprehensive dataset showcases complex family relationships and multi-generational connections.
                        </p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 hover:shadow-xl transition-shadow">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                <x-ts-icon icon="tabler.flag" class="size-8 text-blue-600 dark:text-blue-400" />
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Kennedy Family</h3>
                                <p class="text-gray-600 dark:text-gray-300">The Kennedy Dynasty</p>
                            </div>
                        </div>
                        <p class="text-gray-700 dark:text-gray-300 mb-6">
                            Discover the family tree of the Kennedy family, including former US President John Fitzgerald Kennedy. 
                            This dataset demonstrates how to track political dynasties and their extensive family networks.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-6">Ready to Start Your Family Tree?</h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 mb-8">
                    Launch the application and begin building your family history today. Create your own dataset or explore the sample families.
                </p>
                <form method="POST" action="{{ route('launch') }}">
                    @csrf
                    <button type="submit" class="px-10 py-4 bg-blue-600 hover:bg-blue-700 text-white text-lg font-semibold rounded-lg shadow-xl hover:shadow-2xl transition-all duration-200 transform hover:scale-105">
                        Launch Application
                    </button>
                </form>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-gray-300 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <div class="flex items-center justify-center space-x-3 mb-4">
                        <x-svg.genealogy class="size-8 fill-white" alt="DLM Genealogy" />
                        <span class="text-xl font-bold text-white">DLM Genealogy</span>
                    </div>
                    <p class="text-gray-400 mb-4">Preserve your family history for generations to come</p>
                    <p class="text-sm text-gray-500">© {{ date('Y') }} DLM Genealogy. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>

