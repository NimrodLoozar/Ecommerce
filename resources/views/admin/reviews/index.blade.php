<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Review Moderation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Filter Tabs -->
                    <div class="mb-6 border-b border-gray-200">
                        <nav class="flex space-x-8" aria-label="Tabs">
                            <a href="{{ route('admin.reviews.index') }}"
                                class="border-b-2 {{ !request('status') ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 font-medium text-sm">
                                All Reviews ({{ $reviews->total() }})
                            </a>
                            <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}"
                                class="border-b-2 {{ request('status') === 'pending' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 font-medium text-sm">
                                Pending
                            </a>
                            <a href="{{ route('admin.reviews.index', ['status' => 'approved']) }}"
                                class="border-b-2 {{ request('status') === 'approved' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 font-medium text-sm">
                                Approved
                            </a>
                        </nav>
                    </div>

                    @if ($reviews->count() > 0)
                        <div class="space-y-4">
                            @foreach ($reviews as $review)
                                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <!-- User & Car Info -->
                                            <div class="flex items-center justify-between mb-2">
                                                <div>
                                                    <h3 class="text-lg font-medium text-gray-900">
                                                        {{ $review->title }}
                                                    </h3>
                                                    <p class="text-sm text-gray-600">
                                                        by <span class="font-medium">{{ $review->user->name }}</span>
                                                        for
                                                        <a href="{{ route('cars.show', $review->car) }}"
                                                            class="text-indigo-600 hover:text-indigo-700 font-medium"
                                                            target="_blank">
                                                            {{ $review->car->title }}
                                                        </a>
                                                    </p>
                                                </div>
                                                <div>
                                                    @if ($review->is_approved)
                                                        <span
                                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            <svg class="w-4 h-4 mr-1" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                            Approved
                                                        </span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            <svg class="w-4 h-4 mr-1" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                            Pending
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Rating -->
                                            <div class="flex items-center mb-3">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401Z" />
                                                    </svg>
                                                @endfor
                                                <span class="ml-2 text-sm text-gray-600">{{ $review->rating }}/5</span>
                                            </div>

                                            <!-- Comment -->
                                            <p class="text-gray-700 mb-3">{{ $review->comment }}</p>

                                            <!-- Metadata -->
                                            <p class="text-xs text-gray-500">
                                                Submitted {{ $review->created_at->diffForHumans() }}
                                                @if ($review->updated_at->ne($review->created_at))
                                                    · Updated {{ $review->updated_at->diffForHumans() }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="mt-4 flex items-center space-x-3 pt-4 border-t border-gray-300">
                                        @if (!$review->is_approved)
                                            <form action="{{ route('admin.reviews.update', $review) }}" method="POST"
                                                class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="is_approved" value="1">
                                                <button type="submit"
                                                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Approve
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.reviews.update', $review) }}" method="POST"
                                                class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="is_approved" value="0">
                                                <button type="submit"
                                                    class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md transition">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    Unapprove
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST"
                                            class="inline-block"
                                            onsubmit="return confirm('Are you sure you want to delete this review? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $reviews->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No reviews</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ request('status') === 'pending' ? 'No pending reviews at the moment.' : 'No reviews have been submitted yet.' }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
