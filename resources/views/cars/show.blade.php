<x-app-layout>
    <div class="bg-white">
        <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
            <!-- Breadcrumb -->
            <nav aria-label="Breadcrumb" class="mb-8">
                <ol role="list" class="flex items-center space-x-2 text-sm">
                    <li>
                        <a href="{{ route('home') }}" class="text-gray-400 hover:text-gray-500">Home</a>
                    </li>
                    <li>
                        <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"
                            class="size-5 shrink-0 text-gray-300">
                            <path fill-rule="evenodd"
                                d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
                                clip-rule="evenodd" />
                        </svg>
                    </li>
                    <li>
                        <a href="{{ route('cars.index') }}" class="text-gray-400 hover:text-gray-500">Cars</a>
                    </li>
                    <li>
                        <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"
                            class="size-5 shrink-0 text-gray-300">
                            <path fill-rule="evenodd"
                                d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
                                clip-rule="evenodd" />
                        </svg>
                    </li>
                    <li class="font-medium text-gray-500 truncate">{{ $car->title }}</li>
                </ol>
            </nav>

            <div class="lg:grid lg:grid-cols-2 lg:items-start lg:gap-x-8">
                <!-- Image gallery -->
                <div class="flex flex-col-reverse">
                    <!-- Image selector -->
                    @if ($car->images->count() > 1)
                        <div class="mx-auto mt-6 hidden w-full max-w-2xl sm:block lg:max-w-none">
                            <div class="grid grid-cols-4 gap-6">
                                @foreach ($car->images->take(4) as $index => $image)
                                    <button type="button"
                                        class="relative flex h-24 cursor-pointer items-center justify-center rounded-md bg-white text-sm font-medium uppercase text-gray-900 hover:bg-gray-50">
                                        <span class="sr-only">Image {{ $index + 1 }}</span>
                                        <span class="absolute inset-0 overflow-hidden rounded-md">
                                            <img src="{{ asset('storage/' . $image->image_path) }}" alt=""
                                                class="size-full object-cover object-center" />
                                        </span>
                                        <span
                                            class="pointer-events-none absolute inset-0 rounded-md ring-2 ring-transparent ring-offset-2"
                                            aria-hidden="true"></span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Main image -->
                    <div class="aspect-h-1 aspect-w-1 w-full">
                        @php
                            $primaryImage = $car->images->first();
                            $imageUrl = $primaryImage
                                ? asset('storage/' . $primaryImage->image_path)
                                : 'https://via.placeholder.com/800x600?text=' .
                                    urlencode($car->brand->name . ' ' . $car->carModel->name);
                        @endphp
                        <img src="{{ $imageUrl }}" alt="{{ $car->title }}"
                            class="h-full w-full object-cover object-center sm:rounded-lg" />

                        @if ($car->is_featured)
                            <div
                                class="absolute top-4 right-4 bg-indigo-600 text-white text-sm font-bold px-3 py-1.5 rounded shadow-lg">
                                Featured
                            </div>
                        @endif

                        @if ($car->status === 'sold')
                            <div
                                class="absolute top-4 left-4 bg-red-600 text-white text-sm font-bold px-3 py-1.5 rounded shadow-lg">
                                Sold
                            </div>
                        @elseif($car->status === 'reserved')
                            <div
                                class="absolute top-4 left-4 bg-yellow-600 text-white text-sm font-bold px-3 py-1.5 rounded shadow-lg">
                                Reserved
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Product info -->
                <div class="mt-10 px-4 sm:mt-16 sm:px-0 lg:mt-0">
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ $car->title }}</h1>

                    <div class="mt-3">
                        <h2 class="sr-only">Product information</h2>
                        <p class="text-3xl tracking-tight text-gray-900">€{{ number_format($car->price, 0, ',', '.') }}
                        </p>
                    </div>

                    <!-- Reviews -->
                    <div class="mt-3">
                        <h3 class="sr-only">Reviews</h3>
                        <div class="flex items-center">
                            <div class="flex items-center">
                                @if ($averageRating)
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="size-5 shrink-0 {{ $i <= round($averageRating) ? 'text-yellow-400' : 'text-gray-300' }}"
                                            viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    @endfor
                                @else
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="size-5 shrink-0 text-gray-300" viewBox="0 0 20 20"
                                            fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    @endfor
                                @endif
                            </div>
                            <p class="ml-3 text-sm text-gray-700">
                                @if ($averageRating)
                                    {{ number_format($averageRating, 1) }} out of 5 stars
                                @else
                                    No reviews yet
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h3 class="sr-only">Description</h3>
                        <div class="space-y-6 text-base text-gray-700">
                            <p>{{ $car->description }}</p>
                        </div>
                    </div>

                    <!-- Key specs -->
                    <div class="mt-10">
                        <h3 class="text-sm font-medium text-gray-900">Key Specifications</h3>
                        <div class="mt-4 grid grid-cols-2 gap-4">
                            <div class="border-t border-gray-200 pt-4">
                                <dt class="font-medium text-gray-900">Year</dt>
                                <dd class="mt-2 text-sm text-gray-500">{{ $car->year }}</dd>
                            </div>
                            <div class="border-t border-gray-200 pt-4">
                                <dt class="font-medium text-gray-900">Mileage</dt>
                                <dd class="mt-2 text-sm text-gray-500">{{ number_format($car->mileage) }} km</dd>
                            </div>
                            <div class="border-t border-gray-200 pt-4">
                                <dt class="font-medium text-gray-900">Fuel Type</dt>
                                <dd class="mt-2 text-sm text-gray-500">
                                    {{ ucfirst(str_replace('_', ' ', $car->fuel_type)) }}</dd>
                            </div>
                            <div class="border-t border-gray-200 pt-4">
                                <dt class="font-medium text-gray-900">Transmission</dt>
                                <dd class="mt-2 text-sm text-gray-500">
                                    {{ ucfirst(str_replace('_', ' ', $car->transmission)) }}</dd>
                            </div>
                            <div class="border-t border-gray-200 pt-4">
                                <dt class="font-medium text-gray-900">Condition</dt>
                                <dd class="mt-2 text-sm text-gray-500">{{ $car->condition->name }}</dd>
                            </div>
                            <div class="border-t border-gray-200 pt-4">
                                <dt class="font-medium text-gray-900">Stock</dt>
                                <dd class="mt-2 text-sm text-gray-500">{{ $car->stock_quantity }} available</dd>
                            </div>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="mt-10 flex gap-4">
                        @if ($car->status === 'available' && $car->stock_quantity > 0)
                            <button type="button"
                                class="flex flex-1 items-center justify-center rounded-md bg-indigo-600 px-8 py-3 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Add to cart
                            </button>
                        @else
                            <button type="button" disabled
                                class="flex flex-1 items-center justify-center rounded-md bg-gray-300 px-8 py-3 text-base font-medium text-gray-500 cursor-not-allowed">
                                Unavailable
                            </button>
                        @endif

                        <button type="button"
                            class="flex items-center justify-center rounded-md bg-white px-4 py-3 border border-gray-300 text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                            </svg>
                            <span class="sr-only">Add to wishlist</span>
                        </button>
                    </div>

                    <!-- Additional details -->
                    <div class="mt-10 border-t border-gray-200 pt-10">
                        <h3 class="text-sm font-medium text-gray-900">Additional Details</h3>
                        <div class="prose prose-sm mt-4 text-gray-500">
                            <ul role="list" class="list-disc space-y-2 pl-4">
                                @if ($car->vin)
                                    <li>VIN: <span class="font-mono">{{ $car->vin }}</span></li>
                                @endif
                                @if ($car->exterior_color)
                                    <li>Exterior Color: {{ $car->exterior_color }}</li>
                                @endif
                                @if ($car->interior_color)
                                    <li>Interior Color: {{ $car->interior_color }}</li>
                                @endif
                                @if ($car->engine_size)
                                    <li>Engine Size: {{ $car->engine_size }}L</li>
                                @endif
                                @if ($car->horsepower)
                                    <li>Horsepower: {{ $car->horsepower }} HP</li>
                                @endif
                                <li>Doors: {{ $car->doors }}</li>
                                <li>Seats: {{ $car->seats }}</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Features -->
                    @if ($car->features->count() > 0)
                        <div class="mt-10 border-t border-gray-200 pt-10">
                            <h3 class="text-sm font-medium text-gray-900">Features</h3>
                            <div class="mt-4">
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($car->features as $feature)
                                        <span
                                            class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10">
                                            {{ $feature->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Reviews -->
            @if ($car->reviews->count() > 0)
                <div class="mt-16 lg:col-span-7 lg:col-start-6 lg:mt-0">
                    <h3 class="text-lg font-medium text-gray-900">Recent Reviews</h3>
                    <div class="mt-6 flow-root">
                        <div class="-my-12 divide-y divide-gray-200">
                            @foreach ($car->reviews as $review)
                                <div class="py-12">
                                    <div class="flex items-center">
                                        <div class="ml-4">
                                            <h4 class="text-sm font-bold text-gray-900">{{ $review->user->name }}</h4>
                                            <div class="mt-1 flex items-center">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <svg class="size-5 shrink-0 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                                        viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd"
                                                            d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401Z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                @endfor
                                            </div>
                                            <p class="sr-only">{{ $review->rating }} out of 5 stars</p>
                                        </div>
                                    </div>

                                    <div class="mt-4 space-y-6 text-base italic text-gray-600">
                                        <p>{{ $review->comment }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Similar cars -->
            @if ($similarCars->count() > 0)
                <div class="mt-24">
                    <h2 class="text-2xl font-bold tracking-tight text-gray-900">You may also like</h2>
                    <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                        @foreach ($similarCars as $similarCar)
                            @include('cars.partials.car-card', ['car' => $similarCar])
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
