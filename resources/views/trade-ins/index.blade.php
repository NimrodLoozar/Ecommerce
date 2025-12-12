<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Trade-In Submissions') }}
            </h2>
            <a href="{{ route('trade-ins.create') }}"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Submit New Trade-In
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

            @if($tradeIns->count() > 0)
                <div class="space-y-6">
                    @foreach($tradeIns as $tradeIn)
                        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                            <div class="p-6">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <h3 class="text-lg font-semibold text-gray-900">
                                                {{ $tradeIn->year }} {{ $tradeIn->brand->name }} {{ $tradeIn->carModel->name }}
                                            </h3>
                                            @if($tradeIn->status === 'pending')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Pending Review
                                                </span>
                                            @elseif($tradeIn->status === 'under_review')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Under Review
                                                </span>
                                            @elseif($tradeIn->status === 'offer_made')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                    Offer Made
                                                </span>
                                            @elseif($tradeIn->status === 'accepted')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Accepted
                                                </span>
                                            @elseif($tradeIn->status === 'rejected')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Rejected
                                                </span>
                                            @elseif($tradeIn->status === 'completed')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    Completed
                                                </span>
                                            @endif
                                        </div>

                                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-4 text-sm">
                                            <div>
                                                <p class="text-gray-500">Year</p>
                                                <p class="font-medium text-gray-900">{{ $tradeIn->year }}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-500">Mileage</p>
                                                <p class="font-medium text-gray-900">{{ number_format($tradeIn->mileage) }} km</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-500">Condition</p>
                                                <p class="font-medium text-gray-900 capitalize">{{ $tradeIn->condition }}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-500">Submitted</p>
                                                <p class="font-medium text-gray-900">{{ $tradeIn->created_at->format('M d, Y') }}</p>
                                            </div>
                                        </div>

                                        @if($tradeIn->offered_value)
                                            <div class="mt-4 p-4 bg-indigo-50 rounded-lg">
                                                <p class="text-sm text-indigo-800">
                                                    <span class="font-semibold">Offer:</span> 
                                                    €{{ number_format($tradeIn->offered_value, 2) }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="ml-4">
                                        <a href="{{ route('trade-ins.show', $tradeIn) }}"
                                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $tradeIns->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">No Trade-In Submissions</h3>
                    <p class="mt-2 text-sm text-gray-500">
                        You haven't submitted any trade-in requests yet. Get started by submitting your vehicle information.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('trade-ins.create') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Submit Trade-In Request
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
