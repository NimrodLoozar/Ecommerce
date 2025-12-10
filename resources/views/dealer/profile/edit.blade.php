<x-app-layout>
    <div class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <!-- Back button -->
            <div class="mb-8">
                <a href="{{ route('dealer.profile.show') }}"
                    class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                    <svg class="mr-2 size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M17 10a.75.75 0 0 1-.75.75H5.612l4.158 3.96a.75.75 0 1 1-1.04 1.08l-5.5-5.25a.75.75 0 0 1 0-1.08l5.5-5.25a.75.75 0 1 1 1.04 1.08L5.612 9.25H16.25A.75.75 0 0 1 17 10Z"
                            clip-rule="evenodd" />
                    </svg>
                    Back to Profile
                </a>
            </div>

            <!-- Page header -->
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">Edit Dealer Profile</h1>
                <p class="mt-2 text-sm text-gray-600">Update your dealership information and settings.</p>
            </div>

            <form action="{{ route('dealer.profile.update') }}" method="POST" enctype="multipart/form-data"
                class="mt-8">
                @csrf
                @method('PATCH')

                <div class="space-y-8">
                    <!-- Company Information -->
                    <div class="border-b border-gray-900/10 pb-8">
                        <h2 class="text-base font-semibold text-gray-900">Company Information</h2>
                        <p class="mt-1 text-sm text-gray-600">Basic information about your dealership.</p>

                        <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                            <!-- Company Name -->
                            <div class="sm:col-span-4">
                                <label for="company_name" class="block text-sm font-medium text-gray-900">Company
                                    Name</label>
                                <div class="mt-2">
                                    <input type="text" name="company_name" id="company_name"
                                        value="{{ old('company_name', $dealer->company_name) }}" required
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm @error('company_name') ring-red-500 @enderror">
                                    @error('company_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Business Registration -->
                            <div class="sm:col-span-3">
                                <label for="business_registration"
                                    class="block text-sm font-medium text-gray-900">Business
                                    Registration Number</label>
                                <div class="mt-2">
                                    <input type="text" name="business_registration" id="business_registration"
                                        value="{{ old('business_registration', $dealer->business_registration) }}"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                    @error('business_registration')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Tax ID -->
                            <div class="sm:col-span-3">
                                <label for="tax_id" class="block text-sm font-medium text-gray-900">Tax ID</label>
                                <div class="mt-2">
                                    <input type="text" name="tax_id" id="tax_id"
                                        value="{{ old('tax_id', $dealer->tax_id) }}"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                    @error('tax_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="sm:col-span-3">
                                <label for="phone" class="block text-sm font-medium text-gray-900">Phone
                                    Number</label>
                                <div class="mt-2">
                                    <input type="tel" name="phone" id="phone"
                                        value="{{ old('phone', $dealer->phone) }}" required
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm @error('phone') ring-red-500 @enderror">
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Website -->
                            <div class="sm:col-span-3">
                                <label for="website" class="block text-sm font-medium text-gray-900">Website
                                    URL</label>
                                <div class="mt-2">
                                    <input type="url" name="website" id="website"
                                        value="{{ old('website', $dealer->website) }}"
                                        placeholder="https://example.com"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                    @error('website')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="sm:col-span-6">
                                <label for="description" class="block text-sm font-medium text-gray-900">Business
                                    Description</label>
                                <div class="mt-2">
                                    <textarea name="description" id="description" rows="4"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"
                                        placeholder="Describe your dealership, services, and what makes you unique...">{{ old('description', $dealer->description) }}</textarea>
                                    @error('description')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Company Logo -->
                    <div class="border-b border-gray-900/10 pb-8">
                        <h2 class="text-base font-semibold text-gray-900">Company Logo</h2>
                        <p class="mt-1 text-sm text-gray-600">Upload your company logo for better visibility.</p>

                        <div class="mt-6">
                            @if ($dealer->logo)
                                <div class="mb-4">
                                    <p class="text-sm text-gray-700 mb-2">Current Logo:</p>
                                    <img src="{{ Storage::url($dealer->logo) }}" alt="{{ $dealer->company_name }}"
                                        class="h-32 w-auto rounded-lg border border-gray-200">
                                </div>
                            @endif

                            <label for="logo" class="block text-sm font-medium text-gray-900">
                                {{ $dealer->logo ? 'Replace Logo' : 'Upload Logo' }}
                            </label>
                            <div class="mt-2">
                                <input type="file" name="logo" id="logo" accept="image/*"
                                    class="block w-full text-sm text-gray-900 file:mr-4 file:rounded-md file:border-0 file:bg-indigo-600 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-indigo-500">
                                @error('logo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">PNG, JPG, or SVG. Maximum file size: 2MB</p>
                            </div>
                        </div>
                    </div>

                    <!-- Business Settings -->
                    <div class="border-b border-gray-900/10 pb-8">
                        <h2 class="text-base font-semibold text-gray-900">Business Settings</h2>
                        <p class="mt-1 text-sm text-gray-600">Financial and operational settings.</p>

                        <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                            <!-- Commission Rate (read-only for dealers) -->
                            <div class="sm:col-span-3">
                                <label for="commission_rate"
                                    class="block text-sm font-medium text-gray-900">Commission
                                    Rate (%)</label>
                                <div class="mt-2">
                                    <input type="text" name="commission_rate_display" id="commission_rate"
                                        value="{{ $dealer->commission_rate ?? 'Not set' }}" disabled
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 bg-gray-50 sm:text-sm">
                                    <p class="mt-1 text-xs text-gray-500">Commission rate is managed by administrators
                                    </p>
                                </div>
                            </div>

                            <!-- Subscription Plan (read-only for dealers) -->
                            <div class="sm:col-span-3">
                                <label for="subscription_plan"
                                    class="block text-sm font-medium text-gray-900">Subscription
                                    Plan</label>
                                <div class="mt-2">
                                    <input type="text" name="subscription_plan_display" id="subscription_plan"
                                        value="{{ ucfirst($dealer->subscription_plan ?? 'Standard') }}" disabled
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 bg-gray-50 sm:text-sm">
                                    <p class="mt-1 text-xs text-gray-500">Contact support to change your subscription
                                    </p>
                                </div>
                            </div>

                            <!-- Bank Account -->
                            <div class="sm:col-span-6">
                                <label for="bank_account" class="block text-sm font-medium text-gray-900">Bank Account
                                    Information</label>
                                <div class="mt-2">
                                    <textarea name="bank_account" id="bank_account" rows="3"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"
                                        placeholder="Bank name, account number, routing number, etc.">{{ old('bank_account', $dealer->bank_account) }}</textarea>
                                    @error('bank_account')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">For commission payments (stored securely)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Documents -->
                    <div class="border-b border-gray-900/10 pb-8">
                        <h2 class="text-base font-semibold text-gray-900">Business Documents</h2>
                        <p class="mt-1 text-sm text-gray-600">Upload required business verification documents.</p>

                        <div class="mt-6">
                            @if ($dealer->documents && count($dealer->documents) > 0)
                                <div class="mb-4">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Current Documents:</p>
                                    <ul class="space-y-2">
                                        @foreach ($dealer->documents as $document)
                                            <li class="flex items-center text-sm text-gray-600">
                                                <svg class="mr-2 size-4 text-green-500" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <a href="{{ Storage::url($document) }}" target="_blank"
                                                    class="text-indigo-600 hover:text-indigo-900">
                                                    {{ basename($document) }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <label for="documents" class="block text-sm font-medium text-gray-900">Upload Additional
                                Documents</label>
                            <div class="mt-2">
                                <input type="file" name="documents[]" id="documents" multiple
                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                    class="block w-full text-sm text-gray-900 file:mr-4 file:rounded-md file:border-0 file:bg-indigo-600 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-indigo-500">
                                @error('documents')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                @error('documents.*')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">PDF, DOC, or images. Multiple files allowed.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Account Status (read-only) -->
                    <div class="border-b border-gray-900/10 pb-8">
                        <h2 class="text-base font-semibold text-gray-900">Account Status</h2>
                        <p class="mt-1 text-sm text-gray-600">Your current account standing.</p>

                        <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                            <div class="sm:col-span-3">
                                <label class="block text-sm font-medium text-gray-900">Status</label>
                                <div class="mt-2">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-50 text-yellow-800 ring-yellow-600/20',
                                            'approved' => 'bg-green-50 text-green-800 ring-green-600/20',
                                            'suspended' => 'bg-red-50 text-red-800 ring-red-600/20',
                                            'rejected' => 'bg-gray-50 text-gray-800 ring-gray-600/20',
                                        ];
                                        $colorClass =
                                            $statusColors[$dealer->status] ??
                                            'bg-gray-50 text-gray-800 ring-gray-600/20';
                                    @endphp
                                    <span
                                        class="inline-flex items-center rounded-md px-3 py-2 text-sm font-medium ring-1 ring-inset {{ $colorClass }}">
                                        {{ ucfirst($dealer->status) }}
                                    </span>
                                </div>
                            </div>

                            @if ($dealer->approved_at)
                                <div class="sm:col-span-3">
                                    <label class="block text-sm font-medium text-gray-900">Approved On</label>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-900">{{ $dealer->approved_at->format('F d, Y') }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 flex items-center justify-end gap-x-6">
                    <a href="{{ route('dealer.profile.show') }}"
                        class="text-sm font-semibold text-gray-900 hover:text-gray-700">Cancel</a>
                    <button type="submit"
                        class="rounded-md bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
