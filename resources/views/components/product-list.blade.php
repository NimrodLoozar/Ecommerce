@props(['cars' => [], 'title' => 'Featured Cars'])

<div class="bg-gradient-to-b from-blue-400/60 to-transparent">
    <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
        <h2 class="text-2xl font-bold tracking-tight text-gray-900">{{ $title }}</h2>

        <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
            @forelse($cars as $car)
            <div class="group relative">
                @php
                // Try to get filesystem cover image first, fallback to database image, then placeholder
                $coverImage = $car->getCoverImage();
                $imageUrl = $coverImage 
                    ? asset($coverImage)
                    : ($car->images->first() 
                        ? asset('storage/' . $car->images->first()->image_path)
                        : 'https://via.placeholder.com/400x300?text=' . urlencode($car->brand->name ?? 'Car'));
                @endphp

                <img src="{{ $imageUrl }}" alt="{{ $car->brand->name ?? '' }} {{ $car->carModel->name ?? '' }}"
                    class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 lg:aspect-auto lg:h-80" />

                <div class="mt-4 flex justify-between">
                    <div>
                        <h3 class="text-sm text-gray-700">
                            <a href="{{ route('cars.show', $car->id) }}">
                                <span aria-hidden="true" class="absolute inset-0"></span>
                                {{ $car->brand->name ?? 'Unknown' }} {{ $car->carModel->name ?? '' }}
                            </a>
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ $car->year }} • {{ $car->fuel_type }}
                        </p>
                    </div>
                    <p class="text-sm font-medium text-gray-900">
                        €{{ number_format($car->price, 0, ',', '.') }}
                    </p>
                </div>

                @if ($car->is_featured)
                <div class="absolute top-2 right-2 bg-indigo-600 text-white text-xs font-bold px-2 py-1 rounded">
                    Featured
                </div>
                @endif
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-500">No cars available at the moment.</p>
                <a href="{{ route('cars.index') }}" class="mt-4 inline-block text-indigo-600 hover:text-indigo-500">
                    Browse all cars &rarr;
                </a>
            </div>
            @endforelse
        </div>
    </div>
</div>