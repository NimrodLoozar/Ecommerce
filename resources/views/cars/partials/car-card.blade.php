@php
    // Try to get filesystem cover image first, fallback to database image, then placeholder
    $coverImage = $car->getCoverImage();
    $imageUrl = $coverImage 
        ? asset($coverImage)
        : ($car->images->first() 
            ? asset('storage/' . $car->images->first()->image_path)
            : 'https://via.placeholder.com/400x300?text=' . urlencode($car->brand->name ?? 'Car'));
@endphp

<div class="group relative">
    <div class="aspect-square w-full overflow-hidden rounded-lg bg-gray-200 group-hover:opacity-75 lg:aspect-[4/3]">
        <img src="{{ $imageUrl }}" alt="{{ $car->brand->name ?? '' }} {{ $car->carModel->name ?? '' }}"
            class="size-full object-cover object-center" />
    </div>

    <div class="mt-4 flex justify-between">
        <div class="flex-1">
            <h3 class="text-sm font-medium text-gray-900">
                <a href="{{ route('cars.show', $car->id) }}">
                    <span aria-hidden="true" class="absolute inset-0"></span>
                    {{ $car->brand->name ?? 'Unknown' }} {{ $car->carModel->name ?? '' }}
                </a>
            </h3>
            <p class="mt-1 text-sm text-gray-500">
                {{ $car->year }} • {{ ucfirst($car->fuel_type) }}
            </p>
            <p class="mt-1 text-xs text-gray-500">
                {{ number_format($car->mileage) }} km • {{ ucfirst($car->transmission) }}
            </p>
        </div>
        <div class="text-right">
            <p class="text-sm font-medium text-gray-900">€{{ number_format($car->price, 0, ',', '.') }}</p>
            @if ($car->condition)
                <p class="mt-1 text-xs text-gray-500">{{ $car->condition->name }}</p>
            @endif
        </div>
    </div>

    @if ($car->is_featured)
        <div class="absolute top-2 right-2 bg-indigo-600 text-white text-xs font-bold px-2 py-1 rounded shadow">
            Featured
        </div>
    @endif

    @if ($car->status === 'sold' || $car->stock_quantity == 0)
        <div class="absolute top-2 left-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded shadow">
            Sold
        </div>
    @endif
</div>
