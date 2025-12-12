<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Trade-In Details
            </h2>
            <a href="{{ route('trade-ins.index') }}"
                class="text-sm font-medium text-gray-700 hover:text-gray-900">
                ← Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 rounded-md bg-green-50 p-4">
                    <div class="flex">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
                                clip-rule="evenodd" />
                        </svg>
                        <p class="ml-3 text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <div class="lg:grid lg:grid-cols-3 lg:gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Vehicle Information -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $tradeIn->year }} {{ $tradeIn->brand->name }} {{ $tradeIn->carModel->name }}
                                </h3>
                                @if($tradeIn->status === 'pending')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        Pending Review
                                    </span>
                                @elseif($tradeIn->status === 'under_review')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        Under Review
                                    </span>
                                @elseif($tradeIn->status === 'offer_made')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                        Offer Made
                                    </span>
                                @elseif($tradeIn->status === 'accepted')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        Accepted
                                    </span>
                                @elseif($tradeIn->status === 'rejected')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        Rejected
                                    </span>
                                @elseif($tradeIn->status === 'completed')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        Completed
                                    </span>
                                @endif
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <p class="text-sm text-gray-500">Brand</p>
                                    <p class="mt-1 text-base font-medium text-gray-900">{{ $tradeIn->brand->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Model</p>
                                    <p class="mt-1 text-base font-medium text-gray-900">{{ $tradeIn->carModel->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Year</p>
                                    <p class="mt-1 text-base font-medium text-gray-900">{{ $tradeIn->year }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Mileage</p>
                                    <p class="mt-1 text-base font-medium text-gray-900">{{ number_format($tradeIn->mileage) }} km</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Condition</p>
                                    <p class="mt-1 text-base font-medium text-gray-900 capitalize">{{ $tradeIn->condition }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Exterior Color</p>
                                    <p class="mt-1 text-base font-medium text-gray-900">{{ $tradeIn->exterior_color }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Interior Color</p>
                                    <p class="mt-1 text-base font-medium text-gray-900">{{ $tradeIn->interior_color }}</p>
                                </div>
                                @if($tradeIn->vin)
                                    <div>
                                        <p class="text-sm text-gray-500">VIN</p>
                                        <p class="mt-1 text-base font-medium text-gray-900">{{ $tradeIn->vin }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Ownership & History -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ownership & History</h3>
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <p class="text-sm text-gray-500">Ownership Status</p>
                                    <p class="mt-1 text-base font-medium text-gray-900 capitalize">
                                        {{ str_replace('_', ' ', $tradeIn->ownership_status ?? 'N/A') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Service History</p>
                                    <p class="mt-1 text-base font-medium text-gray-900 capitalize">
                                        {{ str_replace('_', ' ', $tradeIn->service_history ?? 'N/A') }}
                                    </p>
                                </div>
                                <div class="col-span-2">
                                    <p class="text-sm text-gray-500">Accidents</p>
                                    <p class="mt-1 text-base font-medium text-gray-900">
                                        @if($tradeIn->accidents ?? false)
                                            <span class="text-red-600">Yes - Has been in an accident</span>
                                        @else
                                            <span class="text-green-600">No accidents reported</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($tradeIn->description)
                        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
                                <p class="text-gray-700 whitespace-pre-line">{{ $tradeIn->description }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Photos -->
                    @if($tradeIn->images->count() > 0)
                        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Photos</h3>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    @foreach($tradeIn->images as $image)
                                        <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
                                            <img src="{{ Storage::url($image->image_path) }}" 
                                                alt="Vehicle photo"
                                                class="w-full h-full object-cover">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="mt-6 lg:mt-0">
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg sticky top-24">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Submission Info</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm text-gray-500">Submitted</p>
                                    <p class="mt-1 text-base font-medium text-gray-900">
                                        {{ $tradeIn->created_at->format('M d, Y') }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $tradeIn->created_at->diffForHumans() }}</p>
                                </div>

                                @if($tradeIn->estimated_value)
                                    <div class="pt-4 border-t border-gray-200">
                                        <p class="text-sm text-gray-500">Your Estimated Value</p>
                                        <p class="mt-1 text-xl font-bold text-gray-900">
                                            €{{ number_format($tradeIn->estimated_value, 2) }}
                                        </p>
                                    </div>
                                @endif

                                @if($tradeIn->offered_value)
                                    <div class="pt-4 border-t border-gray-200">
                                        <p class="text-sm text-gray-500">Our Offer</p>
                                        <p class="mt-1 text-2xl font-bold text-indigo-600">
                                            €{{ number_format($tradeIn->offered_value, 2) }}
                                        </p>
                                        @if($tradeIn->expires_at)
                                            <p class="mt-2 text-xs text-gray-500">
                                                Expires: {{ $tradeIn->expires_at->format('M d, Y') }}
                                            </p>
                                        @endif
                                    </div>
                                @endif

                                @if($tradeIn->status === 'pending')
                                    <div class="pt-4 border-t border-gray-200">
                                        <div class="rounded-lg bg-blue-50 p-4">
                                            <p class="text-sm text-blue-800">
                                                We're reviewing your submission. We'll contact you within 24-48 hours with an offer.
                                            </p>
                                        </div>
                                    </div>
                                @elseif($tradeIn->status === 'offer_made')
                                    <div class="pt-4 border-t border-gray-200">
                                        <div class="rounded-lg bg-indigo-50 p-4">
                                            <p class="text-sm text-indigo-800 font-medium mb-2">
                                                We've made you an offer!
                                            </p>
                                            <p class="text-sm text-indigo-700">
                                                Please contact us to accept or discuss the offer.
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
