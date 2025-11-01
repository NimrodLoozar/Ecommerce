<form action="{{ route('brands.show', $brand->slug) }}" method="GET" class="space-y-6">
    <!-- Category Filter -->
    <div>
        <h3 class="text-sm font-medium text-gray-900 mb-3">Category</h3>
        <div class="space-y-2">
            @foreach ($categories as $category)
                <div class="flex items-center">
                    <input id="category-{{ $category->id }}" name="category_id" type="radio" value="{{ $category->id }}"
                        {{ request('category_id') == $category->id ? 'checked' : '' }}
                        class="size-4 border-gray-300 text-indigo-600 focus:ring-indigo-600">
                    <label for="category-{{ $category->id }}" class="ml-3 text-sm text-gray-600">
                        {{ $category->name }}
                    </label>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Condition Filter -->
    <div class="border-t border-gray-200 pt-6">
        <h3 class="text-sm font-medium text-gray-900 mb-3">Condition</h3>
        <div class="space-y-2">
            @foreach ($conditions as $condition)
                <div class="flex items-center">
                    <input id="condition-{{ $condition->id }}" name="condition_id" type="radio"
                        value="{{ $condition->id }}" {{ request('condition_id') == $condition->id ? 'checked' : '' }}
                        class="size-4 border-gray-300 text-indigo-600 focus:ring-indigo-600">
                    <label for="condition-{{ $condition->id }}" class="ml-3 text-sm text-gray-600">
                        {{ $condition->name }}
                    </label>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Price Range -->
    <div class="border-t border-gray-200 pt-6">
        <h3 class="text-sm font-medium text-gray-900 mb-3">Price Range</h3>
        <div class="space-y-3">
            <div>
                <label for="min_price" class="block text-xs text-gray-600 mb-1">Min Price (€)</label>
                <input type="number" id="min_price" name="min_price" value="{{ request('min_price') }}"
                    placeholder="0" step="1000" min="0"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
            </div>
            <div>
                <label for="max_price" class="block text-xs text-gray-600 mb-1">Max Price (€)</label>
                <input type="number" id="max_price" name="max_price" value="{{ request('max_price') }}"
                    placeholder="100000" step="1000" min="0"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
            </div>
        </div>
    </div>

    <!-- Year Range -->
    <div class="border-t border-gray-200 pt-6">
        <h3 class="text-sm font-medium text-gray-900 mb-3">Year</h3>
        <div class="space-y-3">
            <div>
                <label for="min_year" class="block text-xs text-gray-600 mb-1">From</label>
                <input type="number" id="min_year" name="min_year" value="{{ request('min_year') }}"
                    placeholder="1980" min="1980" max="{{ date('Y') }}"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
            </div>
            <div>
                <label for="max_year" class="block text-xs text-gray-600 mb-1">To</label>
                <input type="number" id="max_year" name="max_year" value="{{ request('max_year') }}"
                    placeholder="{{ date('Y') }}" min="1980" max="{{ date('Y') }}"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
            </div>
        </div>
    </div>

    <!-- Fuel Type -->
    <div class="border-t border-gray-200 pt-6">
        <h3 class="text-sm font-medium text-gray-900 mb-3">Fuel Type</h3>
        <div class="space-y-2">
            <div class="flex items-center">
                <input id="fuel-petrol" name="fuel_type" type="radio" value="petrol"
                    {{ request('fuel_type') === 'petrol' ? 'checked' : '' }}
                    class="size-4 border-gray-300 text-indigo-600 focus:ring-indigo-600">
                <label for="fuel-petrol" class="ml-3 text-sm text-gray-600">Petrol</label>
            </div>
            <div class="flex items-center">
                <input id="fuel-diesel" name="fuel_type" type="radio" value="diesel"
                    {{ request('fuel_type') === 'diesel' ? 'checked' : '' }}
                    class="size-4 border-gray-300 text-indigo-600 focus:ring-indigo-600">
                <label for="fuel-diesel" class="ml-3 text-sm text-gray-600">Diesel</label>
            </div>
            <div class="flex items-center">
                <input id="fuel-electric" name="fuel_type" type="radio" value="electric"
                    {{ request('fuel_type') === 'electric' ? 'checked' : '' }}
                    class="size-4 border-gray-300 text-indigo-600 focus:ring-indigo-600">
                <label for="fuel-electric" class="ml-3 text-sm text-gray-600">Electric</label>
            </div>
            <div class="flex items-center">
                <input id="fuel-hybrid" name="fuel_type" type="radio" value="hybrid"
                    {{ request('fuel_type') === 'hybrid' ? 'checked' : '' }}
                    class="size-4 border-gray-300 text-indigo-600 focus:ring-indigo-600">
                <label for="fuel-hybrid" class="ml-3 text-sm text-gray-600">Hybrid</label>
            </div>
            <div class="flex items-center">
                <input id="fuel-plugin-hybrid" name="fuel_type" type="radio" value="plugin_hybrid"
                    {{ request('fuel_type') === 'plugin_hybrid' ? 'checked' : '' }}
                    class="size-4 border-gray-300 text-indigo-600 focus:ring-indigo-600">
                <label for="fuel-plugin-hybrid" class="ml-3 text-sm text-gray-600">Plugin Hybrid</label>
            </div>
        </div>
    </div>

    <!-- Transmission -->
    <div class="border-t border-gray-200 pt-6">
        <h3 class="text-sm font-medium text-gray-900 mb-3">Transmission</h3>
        <div class="space-y-2">
            <div class="flex items-center">
                <input id="trans-manual" name="transmission" type="radio" value="manual"
                    {{ request('transmission') === 'manual' ? 'checked' : '' }}
                    class="size-4 border-gray-300 text-indigo-600 focus:ring-indigo-600">
                <label for="trans-manual" class="ml-3 text-sm text-gray-600">Manual</label>
            </div>
            <div class="flex items-center">
                <input id="trans-automatic" name="transmission" type="radio" value="automatic"
                    {{ request('transmission') === 'automatic' ? 'checked' : '' }}
                    class="size-4 border-gray-300 text-indigo-600 focus:ring-indigo-600">
                <label for="trans-automatic" class="ml-3 text-sm text-gray-600">Automatic</label>
            </div>
            <div class="flex items-center">
                <input id="trans-semi" name="transmission" type="radio" value="semi_automatic"
                    {{ request('transmission') === 'semi_automatic' ? 'checked' : '' }}
                    class="size-4 border-gray-300 text-indigo-600 focus:ring-indigo-600">
                <label for="trans-semi" class="ml-3 text-sm text-gray-600">Semi-Automatic</label>
            </div>
        </div>
    </div>

    <!-- Mileage Range -->
    <div class="border-t border-gray-200 pt-6">
        <h3 class="text-sm font-medium text-gray-900 mb-3">Mileage (km)</h3>
        <div class="space-y-3">
            <div>
                <label for="min_mileage" class="block text-xs text-gray-600 mb-1">Min</label>
                <input type="number" id="min_mileage" name="min_mileage" value="{{ request('min_mileage') }}"
                    placeholder="0" step="1000" min="0"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
            </div>
            <div>
                <label for="max_mileage" class="block text-xs text-gray-600 mb-1">Max</label>
                <input type="number" id="max_mileage" name="max_mileage" value="{{ request('max_mileage') }}"
                    placeholder="200000" step="1000" min="0"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="border-t border-gray-200 pt-6 space-y-3">
        <button type="submit"
            class="w-full rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
            Apply Filters
        </button>
        <a href="{{ route('brands.show', $brand->slug) }}"
            class="block w-full rounded-md bg-white px-3 py-2 text-center text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
            Clear All
        </a>
    </div>
</form>
