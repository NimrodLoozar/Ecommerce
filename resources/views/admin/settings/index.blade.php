<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('System Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
            @endif

            <!-- General Settings -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">General Settings</h3>
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <!-- Site Name -->
                        <div class="mb-6">
                            <label for="site_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Site Name
                            </label>
                            <input type="text" name="site_name" id="site_name"
                                value="{{ old('site_name', Cache::get('settings.site_name', config('app.name'))) }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('site_name') border-red-500 @enderror">
                            @error('site_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">The name of your website displayed throughout the
                                platform.</p>
                        </div>

                        <!-- Site Email -->
                        <div class="mb-6">
                            <label for="site_email" class="block text-sm font-medium text-gray-700 mb-2">
                                Site Email
                            </label>
                            <input type="email" name="site_email" id="site_email"
                                value="{{ old('site_email', Cache::get('settings.site_email', config('mail.from.address'))) }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('site_email') border-red-500 @enderror">
                            @error('site_email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Email address used for system notifications and
                                customer support.</p>
                        </div>

                        <!-- Default Commission Rate -->
                        <div class="mb-6">
                            <label for="default_commission_rate" class="block text-sm font-medium text-gray-700 mb-2">
                                Default Commission Rate (%)
                            </label>
                            <input type="number" name="default_commission_rate" id="default_commission_rate"
                                value="{{ old('default_commission_rate', Cache::get('settings.default_commission_rate', 5)) }}"
                                step="0.01" min="0" max="100"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('default_commission_rate') border-red-500 @enderror">
                            @error('default_commission_rate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Default commission rate applied to new dealer accounts
                                (0-100%).</p>
                        </div>

                        <!-- VAT Rate -->
                        <div class="mb-6">
                            <label for="vat_rate" class="block text-sm font-medium text-gray-700 mb-2">
                                VAT Rate (%)
                            </label>
                            <input type="number" name="vat_rate" id="vat_rate"
                                value="{{ old('vat_rate', Cache::get('settings.vat_rate', 21)) }}" step="0.01" min="0"
                                max="100"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('vat_rate') border-red-500 @enderror">
                            @error('vat_rate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Value Added Tax rate applied to all transactions
                                (0-100%).</p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end pt-4 border-t border-gray-200">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- System Maintenance -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">System Maintenance</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-gray-200">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Clear Cache</h4>
                                <p class="text-xs text-gray-500">Clear application cache, config cache, route cache, and
                                    view cache.</p>
                            </div>
                            <form action="{{ route('admin.settings.clear-cache') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center px-3 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-md transition"
                                    onclick="return confirm('Are you sure you want to clear all caches? This may temporarily slow down the application.')">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Clear All Caches
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">System Information</h3>
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Laravel Version</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ app()->version() }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">PHP Version</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ PHP_VERSION }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Environment</dt>
                            <dd class="mt-1">
                                @if (app()->environment('production'))
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Production
                                </span>
                                @elseif (app()->environment('staging'))
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Staging
                                </span>
                                @else
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ ucfirst(app()->environment()) }}
                                </span>
                                @endif
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Debug Mode</dt>
                            <dd class="mt-1">
                                @if (config('app.debug'))
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Enabled
                                </span>
                                @else
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Disabled
                                </span>
                                @endif
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Database Driver</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ config('database.default') }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Cache Driver</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ config('cache.default') }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Queue Connection</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ config('queue.default') }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Mail Driver</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ config('mail.default') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Warning Notice -->
            <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Important Notice</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Settings are currently stored in cache. For production use, implement a database-backed
                                settings system to ensure persistence across cache clears and application restarts.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>