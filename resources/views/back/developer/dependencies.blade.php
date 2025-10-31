@section('title')
    &vert; {{ __('app.dependencies') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ __('app.dependencies') }}
    </x-slot>

    <div class="p-2 max-w-5xl overflow-x-auto grow dark:text-neutral-200">
        <x-ts-tab selected="TallStack">
            {{-- tallstack --}}
            <x-ts-tab.items tab="TallStack">
                <div class="p-4">
                    <div class="grid gap-4 md:grid-cols-2">
                        <ul class="w-full">
                            <li class="py-2">
                                Laravel 12
                            </li>

                            <li class="py-2">
                                Laravel Jetstream 5 (featuring Teams)
                            </li>

                            <li class="py-2">
                                Laravel Livewire 3
                            </li>

                            <li class="py-2">
                                Alpine.js 3
                            </li>

                            <li class="py-2">
                                Laravel Filament 4 (only Table Builder)
                            </li>

                            <li class="py-2">
                                Tailwind CSS 4
                            </li>

                            <li class="py-2">
                                TallStackUI 2 (featuring Tabler Icons)
                            </li>
                        </ul>

                        <div class="grid grid-cols-4 gap-4 mt-4 ml-4 max-w-sm justify-items-center">
                            <div class="content-center max-w-24">
                                <img src="{{ url('img/logo/laravel.webp') }}" class="rounded-sm" alt="laravel" title="Laravel" />
                            </div>

                            <div class="content-center max-w-24">
                                <img src="{{ url('img/logo/livewire.webp') }}" class="rounded-sm" alt="livewire" title="Livewire" />
                            </div>

                            <div class="content-center max-w-24">
                                <img src="{{ url('img/logo/alpinejs.webp') }}" class="rounded-sm" alt="alpinejs" title="alpine.js" />
                            </div>

                            <div class="content-center max-w-24">
                                <img src="{{ url('img/logo/tailwindcss.webp') }}" class="rounded-sm" alt="tailwindcss" title="Tailwind CSS" />
                            </div>

                            <div class="content-center col-span-2 max-w-24">
                                <img src="{{ url('img/logo/tallstackui.webp') }}" class="rounded-sm" alt="talstackui" title="TallStackUI" />
                            </div>

                            <div class="content-center col-span-2 max-w-24">
                                <img src="{{ url('img/logo/filament.webp') }}" class="rounded-sm" alt="filament" title="Filament" />
                            </div>
                        </div>
                    </div>
                </div>
            </x-ts-tab.items>

            {{-- packages --}}
            <x-ts-tab.items tab="Packages">
                <div class="p-4">
                    <ul class="w-full">
                        <li class="py-2">
                            alisalehi1380/laravel-lang-files-translator
                            <x-ts-button xs class="ms-5" color="emerald">{{ 'DEV' }}</x-ts-button>
                        </li>

                        <li class="py-2">
                            barryvdh/laravel-debugbar
                            <x-ts-button xs class="ms-5" color="emerald" >{{ 'DEV' }}</x-ts-button>
                        </li>

                        <li class="py-2">
                            barryvdh/laravel-ide-helper
                            <x-ts-button xs class="ms-5" color="emerald">{{ 'DEV' }}</x-ts-button>
                        </li>

                        <li class="py-2">
                            filamentphp/tables
                        </li>

                        <li class="py-2">
                            intervention/image
                        </li>

                        <li class="py-2">
                            korridor/laravel-has-many-merged
                        </li>

                        <li class="py-2">
                            larswiegers/laravel-translations-checker
                            <x-ts-button xs class="ms-5" color="emerald">{{ 'DEV' }}</x-ts-button>
                        </li>

                        <li class="py-2">
                            opcodesio/log-viewer
                        </li>

                        <li class="py-2">
                            secondnetwork/blade-tabler-icons
                        </li>

                        <li class="py-2">
                            spatie/laravel-activitylog
                        </li>

                        <li class="py-2">
                            spatie/laravel-backup
                        </li>

                        <li class="py-2">
                            spatie/laravel-medialibrary
                        </li>

                        <li class="py-2">
                            stefangabos/world_countries
                        </li>

                        <li class="py-2">
                            stevebauman/location
                        </li>

                        <li class="py-2">
                            tallstackui/tallstackui
                        </li>

                        <li class="py-2">
                            MuhammadSadeeq/laravel-activitylog-ui
                            <x-ts-button xs class="ms-5" color="orange">{{ 'Optional' }}</x-ts-button>
                        </li>
                    </ul>
                </div>
            </x-ts-tab.items>

            {{-- localization --}}
            <x-ts-tab.items tab="Localization">
                <div class="p-4">
                    <ul class="w-full">
                        <li class="py-2">
                            Laravel-Lang/lang
                            <br />
                            <span class="ms-5">copy</span> <span class="text-red-500">/lang/locales/xx/json.json</span> to <span class="text-red-500">/lang/xx.json</span>
                        </li>

                        <li class="py-2">
                            LarsWiegers/laravel-translations-checker
                            <br />
                            <span class="ms-5">to check languages, use command :</span> <span class="text-red-500">php artisan translations:check --excludedDirectories=vendor</span>
                    </ul>

                    <hr class="my-4">

                    <ul class="w-full">
                        alisalehi1380/laravel-lang-files-translator
                        <br />
                        <span class="ms-5">to create new language, use command :</span> <span class="text-red-500">php artisan translate:lang {from} {to}</span>
                        <br />
                        <span class="ms-5">example:</span> <span class="text-red-500">php artisan translate:lang en fa</span> for Persian (fa)
                        </li>
                    </ul>
                </div>
            </x-ts-tab.items>

            {{-- javascript --}}
            <x-ts-tab.items tab="Javascript">
                <div class="p-4">
                    <ul class="w-full">
                        <li class="py-2">
                            Chart.js - Charts
                        </li>

                        <li class="py-2">
                            StephanWagner/svgMap - WordMap
                        </li>
                    </ul>
                </div>
            </x-ts-tab.items>

            {{-- 3rd Party --}}
            <x-ts-tab.items tab="3rd-Party">
                <div class="p-4">
                    <ul class="w-full">
                        <li class="py-2">
                            tabler.io - Icons
                        </li>

                        <li class="py-2">
                            svgrepo.com - SVG Repository
                        </li>
                    </ul>
                </div>
            </x-ts-tab.items>

            {{-- gedcom --}}
            <x-ts-tab.items tab="GEDCOM">
                <div class="p-4">
                    <ul class="w-full">
                        <li class="py-2">
                            FamilySearch GEDCOM
                        </li>

                        <li class="py-2">
                            FamilySearch GEDCOM - Specifications
                        </li>

                        <li class="py-2">
                            FamilySearch GEDCOM - Tools
                        </li>
                    </ul>

                    <hr class="my-4">

                    <ul class="w-full">
                        <li class="py-2">
                            GEDCOM file validation
                        </li>
                    </ul>
                </div>
            </x-ts-tab.items>
        </x-ts-tab>
    </div>
</x-app-layout>
