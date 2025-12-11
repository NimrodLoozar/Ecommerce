<x-app-layout>
    <div class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <!-- Back button -->
            <div class="mb-8">
                <a href="{{ route('dealer.cars.index') }}"
                    class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                    <svg class="mr-2 size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M17 10a.75.75 0 0 1-.75.75H5.612l4.158 3.96a.75.75 0 1 1-1.04 1.08l-5.5-5.25a.75.75 0 0 1 0-1.08l5.5-5.25a.75.75 0 1 1 1.04 1.08L5.612 9.25H16.25A.75.75 0 0 1 17 10Z"
                            clip-rule="evenodd" />
                    </svg>
                    Back to Inventory
                </a>
            </div>

            <!-- Page header -->
            <div class="sm:flex sm:items-start sm:justify-between">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">
                        {{ $car->year }} {{ $car->brand->name }} {{ $car->carModel->name }}
                    </h1>
                    <p class="mt-2 text-sm text-gray-600">Manage your vehicle listing and view performance.</p>
                </div>
                <div class="mt-4 flex gap-3 sm:mt-0">
                    <a href="{{ route('dealer.cars.edit', $car) }}"
                        class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                        <svg class="mr-2 size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                        Edit
                    </a>
                    <a href="{{ route('cars.show', $car) }}" target="_blank"
                        class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                        <svg class="mr-2 size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        View Public Page
                    </a>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div class="overflow-hidden rounded-lg bg-white shadow ring-1 ring-black/5">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="shrink-0">
                                <svg class="size-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </div>
                            <div class="ml-5">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500">Total Views</dt>
                                    <dd class="text-2xl font-semibold text-gray-900">
                                        {{ number_format($car->views_count) }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-lg bg-white shadow ring-1 ring-black/5">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="shrink-0">
                                <svg class="size-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                </svg>
                            </div>
                            <div class="ml-5">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500">Wishlist Adds</dt>
                                    <dd class="text-2xl font-semibold text-gray-900">
                                        {{ $car->wishlists()->count() }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-lg bg-white shadow ring-1 ring-black/5">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="shrink-0">
                                <svg class="size-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                </svg>
                            </div>
                            <div class="ml-5">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500">Times Sold</dt>
                                    <dd class="text-2xl font-semibold text-gray-900">
                                        {{ $car->orderItems()->count() }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-lg bg-white shadow ring-1 ring-black/5">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="shrink-0">
                                <svg class="size-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                                </svg>
                            </div>
                            <div class="ml-5">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500">Inquiries</dt>
                                    <dd class="text-2xl font-semibold text-gray-900">
                                        {{ $car->inquiries()->count() }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-3">
                <!-- Vehicle Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Images -->
                    @if (count($filesystemImages) > 0)
                        <div class="rounded-lg border border-gray-200 bg-white">
                            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                                <h2 class="text-base font-semibold text-gray-900">Images</h2>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                                    @foreach ($filesystemImages as $index => $imagePath)
                                        <div class="relative">
                                            <img src="{{ asset($imagePath) }}" alt="Car image"
                                                class="aspect-square w-full rounded-lg object-cover">
                                            @if ($index === 0)
                                                <span
                                                    class="absolute top-2 left-2 rounded-md bg-indigo-600 px-2 py-1 text-xs font-semibold text-white">
                                                    Primary
                                                </span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="rounded-lg border border-gray-200 bg-white">
                            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                                <h2 class="text-base font-semibold text-gray-900">Images</h2>
                            </div>
                            <div class="p-6">
                                <p class="text-sm text-gray-500">No images uploaded yet. Images should be placed in the folder structure.</p>
                            </div>
                        </div>
                    @endif

                    <!-- Specifications -->
                    <div class="rounded-lg border border-gray-200 bg-white">
                        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                            <h2 class="text-base font-semibold text-gray-900">Specifications</h2>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Brand</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $car->brand->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Model</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $car->carModel->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Year</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $car->year }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Category</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $car->category->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Condition</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $car->condition->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Mileage</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ number_format($car->mileage) }} km</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Fuel Type</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($car->fuel_type) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Transmission</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($car->transmission) }}</dd>
                                </div>
                                @if ($car->engine_size)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Engine Size</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $car->engine_size }}L</dd>
                                    </div>
                                @endif
                                @if ($car->horsepower)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Horsepower</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $car->horsepower }} HP</dd>
                                    </div>
                                @endif
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Doors</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $car->doors }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Seats</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $car->seats }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Exterior Color</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $car->exterior_color }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Interior Color</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $car->interior_color }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">VIN</dt>
                                    <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $car->vin }}</dd>
                                </div>
                                @if ($car->license_plate)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">License Plate</dt>
                                        <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $car->license_plate }}
                                        </dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Description -->
                    @if ($car->description)
                        <div class="rounded-lg border border-gray-200 bg-white">
                            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                                <h2 class="text-base font-semibold text-gray-900">Description</h2>
                            </div>
                            <div class="px-6 py-4">
                                <p class="text-sm text-gray-900 whitespace-pre-line">{{ $car->description }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Features -->
                    @if ($car->features->count() > 0)
                        <div class="rounded-lg border border-gray-200 bg-white">
                            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                                <h2 class="text-base font-semibold text-gray-900">Features</h2>
                            </div>
                            <div class="px-6 py-4">
                                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                                    @foreach ($car->features as $feature)
                                        <div class="flex items-center text-sm text-gray-900">
                                            <svg class="mr-2 size-4 text-green-500" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                            {{ $feature->name }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Pricing & Stock -->
                    <div class="rounded-lg border border-gray-200 bg-white">
                        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                            <h2 class="text-base font-semibold text-gray-900">Pricing & Stock</h2>
                        </div>
                        <div class="px-6 py-4 space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Sale Price</dt>
                                <dd class="mt-1 text-2xl font-bold text-gray-900">€{{ number_format($car->price, 2) }}
                                </dd>
                            </div>
                            @if ($car->lease_price_monthly)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Lease Price</dt>
                                    <dd class="mt-1 text-lg font-semibold text-gray-900">
                                        €{{ number_format($car->lease_price_monthly, 2) }}/month</dd>
                                </div>
                            @endif
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Stock Quantity</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <span
                                        class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $car->stock_quantity > 0 ? 'bg-green-50 text-green-700 ring-green-600/20' : 'bg-red-50 text-red-700 ring-red-600/20' }}">
                                        {{ $car->stock_quantity }} {{ Str::plural('unit', $car->stock_quantity) }}
                                    </span>
                                </dd>
                            </div>
                            @if ($car->is_featured)
                                <div>
                                    <span
                                        class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10">
                                        ⭐ Featured Vehicle
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Listing Info -->
                    <div class="rounded-lg border border-gray-200 bg-white">
                        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                            <h2 class="text-base font-semibold text-gray-900">Listing Information</h2>
                        </div>
                        <div class="px-6 py-4 space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Created</span>
                                <span
                                    class="font-medium text-gray-900">{{ $car->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Last Updated</span>
                                <span
                                    class="font-medium text-gray-900">{{ $car->updated_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Listed By</span>
                                <span class="font-medium text-gray-900">{{ $car->user->name }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="rounded-lg border border-gray-200 bg-white">
                        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                            <h2 class="text-base font-semibold text-gray-900">Quick Actions</h2>
                        </div>
                        <div class="px-6 py-4 space-y-3">
                            <a href="{{ route('dealer.cars.edit', $car) }}"
                                class="block w-full rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                Edit Vehicle
                            </a>
                            <form action="{{ route('dealer.cars.destroy', $car) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this vehicle?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="block w-full rounded-md bg-red-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-red-500">
                                    Delete Vehicle
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
