<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Wishlist') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 rounded-md bg-green-50 p-4">
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

            @if (session('info'))
                <div class="mb-6 rounded-md bg-blue-50 p-4">
                    <div class="flex">
                        <div class="shrink-0">
                            <svg class="size-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-blue-800">{{ session('info') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if ($wishlists->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($wishlists as $wishlist)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <!-- Car Image -->
                            <div class="relative">
                                @if ($wishlist->car->images->first())
                                    <img src="{{ Storage::url($wishlist->car->images->first()->image_path) }}"
                                        alt="{{ $wishlist->car->brand->name }} {{ $wishlist->car->carModel->name }}"
                                        class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif

                                <!-- Remove Button -->
                                <form action="{{ route('wishlist.destroy', $wishlist) }}" method="POST"
                                    class="absolute top-2 right-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-2 bg-white rounded-full shadow-lg hover:bg-gray-100 transition"
                                        onclick="return confirm('Remove this car from your wishlist?')">
                                        <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </form>

                                <!-- Availability Badge -->
                                @if ($wishlist->car->stock_quantity > 0)
                                    <span
                                        class="absolute top-2 left-2 inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                        In Stock
                                    </span>
                                @else
                                    <span
                                        class="absolute top-2 left-2 inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">
                                        Out of Stock
                                    </span>
                                @endif
                            </div>

                            <!-- Car Details -->
                            <div class="p-6">
                                <!-- Title -->
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $wishlist->car->year }} {{ $wishlist->car->brand->name }}
                                    {{ $wishlist->car->carModel->name }}
                                </h3>

                                <!-- Specs -->
                                <div class="mt-2 flex flex-wrap gap-2 text-sm text-gray-600">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                        {{ $wishlist->car->fuel_type }}
                                    </span>
                                    <span>•</span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ number_format($wishlist->car->mileage) }} km
                                    </span>
                                    <span>•</span>
                                    <span>{{ $wishlist->car->transmission }}</span>
                                </div>

                                <!-- Condition -->
                                <div class="mt-2">
                                    <span
                                        class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
                                        {{ $wishlist->car->condition->name }}
                                    </span>
                                </div>

                                <!-- Price -->
                                <div class="mt-4 flex items-baseline justify-between">
                                    <div>
                                        <p class="text-2xl font-bold text-gray-900">
                                            €{{ number_format($wishlist->car->price, 2) }}
                                        </p>
                                        @if ($wishlist->car->lease_price)
                                            <p class="text-sm text-gray-600">
                                                or €{{ number_format($wishlist->car->lease_price, 2) }}/mo
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Added Date -->
                                <p class="mt-3 text-xs text-gray-500">
                                    Added {{ $wishlist->created_at->diffForHumans() }}
                                </p>

                                <!-- Actions -->
                                <div class="mt-4 flex gap-2">
                                    <a href="{{ route('cars.show', $wishlist->car) }}"
                                        class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        View Details
                                    </a>

                                    @if ($wishlist->car->stock_quantity > 0)
                                        <form action="{{ route('cart.store') }}" method="POST" class="flex-1">
                                            @csrf
                                            <input type="hidden" name="car_id" value="{{ $wishlist->car->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit"
                                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                Add to Cart
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($wishlists->hasPages())
                    <div class="mt-6">
                        {{ $wishlists->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <h3 class="mt-4 text-lg font-semibold text-gray-900">Your wishlist is empty</h3>
                        <p class="mt-2 text-sm text-gray-600">
                            Start adding cars you love to your wishlist so you can easily find them later.
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('cars.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Browse Cars
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
