<x-app-layout>
    <div class="bg-white">
        <div class="mx-auto max-w-3xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="mb-8">
                <a href="{{ $return ?? route('addresses.index') }}"
                    class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                    <svg class="mr-2 size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M17 10a.75.75 0 0 1-.75.75H5.612l4.158 3.96a.75.75 0 1 1-1.04 1.08l-5.5-5.25a.75.75 0 0 1 0-1.08l5.5-5.25a.75.75 0 1 1 1.04 1.08L5.612 9.25H16.25A.75.75 0 0 1 17 10Z"
                            clip-rule="evenodd" />
                    </svg>
                    Back
                </a>
            </div>

            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">Add new address</h1>
                <p class="mt-2 text-sm text-gray-600">Add a new shipping or billing address to your account.</p>
            </div>

            <form action="{{ route('addresses.store') }}" method="POST" class="mt-8">
                @csrf
                <input type="hidden" name="return" value="{{ request('return') }}">

                <div class="space-y-6">
                    <!-- Personal Information -->
                    <div class="border-b border-gray-900/10 pb-8">
                        <h2 class="text-base font-semibold text-gray-900">Personal information</h2>

                        <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                            <div class="sm:col-span-3">
                                <label for="first_name" class="block text-sm font-medium text-gray-900">First
                                    name</label>
                                <div class="mt-2">
                                    <input type="text" name="first_name" id="first_name"
                                        value="{{ old('first_name') }}" required
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm @error('first_name') ring-red-500 @enderror">
                                    @error('first_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="last_name" class="block text-sm font-medium text-gray-900">Last name</label>
                                <div class="mt-2">
                                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}"
                                        required
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm @error('last_name') ring-red-500 @enderror">
                                    @error('last_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="sm:col-span-4">
                                <label for="company" class="block text-sm font-medium text-gray-900">Company
                                    (optional)</label>
                                <div class="mt-2">
                                    <input type="text" name="company" id="company" value="{{ old('company') }}"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                </div>
                            </div>

                            <div class="sm:col-span-4">
                                <label for="phone" class="block text-sm font-medium text-gray-900">Phone
                                    number</label>
                                <div class="mt-2">
                                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                                        required
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm @error('phone') ring-red-500 @enderror">
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div class="border-b border-gray-900/10 pb-8">
                        <h2 class="text-base font-semibold text-gray-900">Address</h2>

                        <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                            <div class="col-span-full">
                                <label for="address_line1" class="block text-sm font-medium text-gray-900">Street
                                    address</label>
                                <div class="mt-2">
                                    <input type="text" name="address_line1" id="address_line1"
                                        value="{{ old('address_line1') }}" required
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm @error('address_line1') ring-red-500 @enderror">
                                    @error('address_line1')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-span-full">
                                <label for="address_line2" class="block text-sm font-medium text-gray-900">Apartment,
                                    suite, etc. (optional)</label>
                                <div class="mt-2">
                                    <input type="text" name="address_line2" id="address_line2"
                                        value="{{ old('address_line2') }}"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <label for="city" class="block text-sm font-medium text-gray-900">City</label>
                                <div class="mt-2">
                                    <input type="text" name="city" id="city" value="{{ old('city') }}"
                                        required
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm @error('city') ring-red-500 @enderror">
                                    @error('city')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <label for="state" class="block text-sm font-medium text-gray-900">State /
                                    Province</label>
                                <div class="mt-2">
                                    <input type="text" name="state" id="state" value="{{ old('state') }}"
                                        required
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm @error('state') ring-red-500 @enderror">
                                    @error('state')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <label for="postal_code" class="block text-sm font-medium text-gray-900">ZIP / Postal
                                    code</label>
                                <div class="mt-2">
                                    <input type="text" name="postal_code" id="postal_code"
                                        value="{{ old('postal_code') }}" required
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm @error('postal_code') ring-red-500 @enderror">
                                    @error('postal_code')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="country" class="block text-sm font-medium text-gray-900">Country</label>
                                <div class="mt-2">
                                    <select name="country" id="country" required
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm @error('country') ring-red-500 @enderror">
                                        <option value="">Select a country</option>
                                        <option value="Netherlands"
                                            {{ old('country') == 'Netherlands' ? 'selected' : '' }}>Netherlands
                                        </option>
                                        <option value="Belgium" {{ old('country') == 'Belgium' ? 'selected' : '' }}>
                                            Belgium</option>
                                        <option value="Germany" {{ old('country') == 'Germany' ? 'selected' : '' }}>
                                            Germany</option>
                                        <option value="France" {{ old('country') == 'France' ? 'selected' : '' }}>
                                            France</option>
                                        <option value="United Kingdom"
                                            {{ old('country') == 'United Kingdom' ? 'selected' : '' }}>United Kingdom
                                        </option>
                                        <option value="Spain" {{ old('country') == 'Spain' ? 'selected' : '' }}>Spain
                                        </option>
                                        <option value="Italy" {{ old('country') == 'Italy' ? 'selected' : '' }}>Italy
                                        </option>
                                        <option value="Portugal" {{ old('country') == 'Portugal' ? 'selected' : '' }}>
                                            Portugal</option>
                                    </select>
                                    @error('country')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Options -->
                    <div class="pb-8">
                        <div class="flex items-center">
                            <input id="is_default" name="is_default" type="checkbox" value="1"
                                {{ old('is_default') ? 'checked' : '' }}
                                class="size-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                            <label for="is_default" class="ml-2 block text-sm text-gray-900">
                                Set as default address
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-end gap-x-6">
                    <a href="{{ $return ?? route('addresses.index') }}"
                        class="text-sm font-semibold text-gray-900">Cancel</a>
                    <button type="submit"
                        class="rounded-md bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Save address
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
