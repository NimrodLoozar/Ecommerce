<x-app-layout>
    <div class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <!-- Back button -->
            <div class="mb-8">
                <a href="{{ route('dealer.dashboard') }}"
                    class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                    <svg class="mr-2 size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M17 10a.75.75 0 0 1-.75.75H5.612l4.158 3.96a.75.75 0 1 1-1.04 1.08l-5.5-5.25a.75.75 0 0 1 0-1.08l5.5-5.25a.75.75 0 1 1 1.04 1.08L5.612 9.25H16.25A.75.75 0 0 1 17 10Z"
                            clip-rule="evenodd" />
                    </svg>
                    Back to Dashboard
                </a>
            </div>

            <!-- Page header -->
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Dealer Profile</h1>
                    <p class="mt-2 text-sm text-gray-600">View your dealership information and settings.</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <a href="{{ route('dealer.profile.edit') }}"
                        class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                        <svg class="mr-2 size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                        Edit Profile
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="mt-6 rounded-md bg-green-50 p-4">
                    <div class="flex">
                        <div class="shrink-0">
                            <svg class="size-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Profile Status Badge -->
            <div class="mt-8">
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-50 text-yellow-800 ring-yellow-600/20',
                        'approved' => 'bg-green-50 text-green-800 ring-green-600/20',
                        'suspended' => 'bg-red-50 text-red-800 ring-red-600/20',
                        'rejected' => 'bg-gray-50 text-gray-800 ring-gray-600/20',
                    ];
                    $colorClass = $statusColors[$dealer->status] ?? 'bg-gray-50 text-gray-800 ring-gray-600/20';
                @endphp
                <span
                    class="inline-flex items-center rounded-md px-3 py-2 text-sm font-medium ring-1 ring-inset {{ $colorClass }}">
                    <svg class="mr-1.5 size-2 fill-current" viewBox="0 0 6 6">
                        <circle cx="3" cy="3" r="3" />
                    </svg>
                    Account Status: {{ ucfirst($dealer->status) }}
                </span>
            </div>

            <!-- Profile Content -->
            <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-3">
                <!-- Main Info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Company Information -->
                    <div class="rounded-lg border border-gray-200 bg-white">
                        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                            <h2 class="text-base font-semibold text-gray-900">Company Information</h2>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Company Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $dealer->company_name }}</dd>
                                </div>
                                @if ($dealer->business_registration)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Business Registration</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $dealer->business_registration }}</dd>
                                    </div>
                                @endif
                                @if ($dealer->tax_id)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Tax ID</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $dealer->tax_id }}</dd>
                                    </div>
                                @endif
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $dealer->phone }}</dd>
                                </div>
                                @if ($dealer->website)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Website</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            <a href="{{ $dealer->website }}" target="_blank"
                                                class="text-indigo-600 hover:text-indigo-900">
                                                {{ $dealer->website }}
                                            </a>
                                        </dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Description -->
                    @if ($dealer->description)
                        <div class="rounded-lg border border-gray-200 bg-white">
                            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                                <h2 class="text-base font-semibold text-gray-900">About</h2>
                            </div>
                            <div class="px-6 py-4">
                                <p class="text-sm text-gray-900 whitespace-pre-line">{{ $dealer->description }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Business Details -->
                    <div class="rounded-lg border border-gray-200 bg-white">
                        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                            <h2 class="text-base font-semibold text-gray-900">Business Details</h2>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                @if ($dealer->commission_rate)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Commission Rate</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $dealer->commission_rate }}%</dd>
                                    </div>
                                @endif
                                @if ($dealer->subscription_plan)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Subscription Plan</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ ucfirst($dealer->subscription_plan) }}
                                        </dd>
                                    </div>
                                @endif
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Account Owner</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $dealer->user->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $dealer->user->email }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $dealer->created_at->format('F d, Y') }}
                                    </dd>
                                </div>
                                @if ($dealer->approved_at)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Approved On</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ $dealer->approved_at->format('F d, Y') }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Company Logo -->
                    <div class="rounded-lg border border-gray-200 bg-white">
                        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                            <h2 class="text-base font-semibold text-gray-900">Company Logo</h2>
                        </div>
                        <div class="px-6 py-4">
                            @if ($dealer->logo)
                                <img src="{{ Storage::url($dealer->logo) }}" alt="{{ $dealer->company_name }}"
                                    class="w-full rounded-lg">
                            @else
                                <div class="flex h-48 items-center justify-center rounded-lg bg-gray-100 text-gray-400">
                                    <svg class="size-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <p class="mt-2 text-center text-sm text-gray-500">No logo uploaded</p>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="rounded-lg border border-gray-200 bg-white">
                        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                            <h2 class="text-base font-semibold text-gray-900">Quick Stats</h2>
                        </div>
                        <div class="px-6 py-4 space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Total Vehicles</span>
                                <span
                                    class="text-sm font-semibold text-gray-900">{{ $dealer->cars()->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Active Listings</span>
                                <span
                                    class="text-sm font-semibold text-gray-900">{{ $dealer->cars()->where('stock_quantity', '>', 0)->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Total Sales</span>
                                <span
                                    class="text-sm font-semibold text-gray-900">{{ $dealer->commissions()->where('status', 'paid')->count() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Documents -->
                    @if ($dealer->documents && count($dealer->documents) > 0)
                        <div class="rounded-lg border border-gray-200 bg-white">
                            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                                <h2 class="text-base font-semibold text-gray-900">Documents</h2>
                            </div>
                            <div class="px-6 py-4">
                                <ul class="space-y-2">
                                    @foreach ($dealer->documents as $document)
                                        <li>
                                            <a href="{{ Storage::url($document) }}" target="_blank"
                                                class="flex items-center text-sm text-indigo-600 hover:text-indigo-900">
                                                <svg class="mr-2 size-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                {{ basename($document) }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
