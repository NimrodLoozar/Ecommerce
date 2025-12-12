<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <!-- Page Header -->
        <div class="bg-white border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Advanced Search</h1>
                        <p class="mt-1 text-sm text-gray-600">
                            @if($cars->total() > 0)
                                Found {{ number_format($cars->total()) }} {{ Str::plural('vehicle', $cars->total()) }}
                            @else
                                No vehicles found matching your criteria
                            @endif
                        </p>
                    </div>
                    <a href="{{ route('cars.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                        View All Cars →
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="lg:grid lg:grid-cols-4 lg:gap-8">
                <!-- Filters Sidebar -->
                <aside class="hidden lg:block">
                    <div class="sticky top-24">
                        <form method="GET" action="{{ route('search') }}" class="space-y-6">
                            <!-- Search Query -->
                            <div>
                                <label for="q" class="block text-sm font-medium text-gray-700 mb-2">
                                    Search
                                </label>
                                <input type="text" id="q" name="q" value="{{ request('q') }}"
                                    placeholder="Brand, model, VIN..."
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <!-- Brand -->
                            <div>
                                <label for="brand_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Brand
                                </label>
                                <select id="brand_id" name="brand_id"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">All Brands</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Category
                                </label>
                                <select id="category_id" name="category_id"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Condition -->
                            <div>
                                <label for="condition_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Condition
                                </label>
                                <select id="condition_id" name="condition_id"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Any Condition</option>
                                    @foreach($conditions as $condition)
                                        <option value="{{ $condition->id }}" {{ request('condition_id') == $condition->id ? 'selected' : '' }}>
                                            {{ $condition->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Price Range -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Price Range (€)
                                </label>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="number" name="min_price" value="{{ request('min_price') }}"
                                        placeholder="Min"
                                        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <input type="number" name="max_price" value="{{ request('max_price') }}"
                                        placeholder="Max"
                                        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>

                            <!-- Year Range -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Year
                                </label>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="number" name="min_year" value="{{ request('min_year') }}"
                                        placeholder="Min"
                                        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <input type="number" name="max_year" value="{{ request('max_year') }}"
                                        placeholder="Max"
                                        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>

                            <!-- Mileage Range -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Mileage (km)
                                </label>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="number" name="min_mileage" value="{{ request('min_mileage') }}"
                                        placeholder="Min"
                                        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <input type="number" name="max_mileage" value="{{ request('max_mileage') }}"
                                        placeholder="Max"
                                        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>

                            <!-- Fuel Type -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Fuel Type
                                </label>
                                <div class="space-y-2">
                                    @foreach($availableFuelTypes as $fuelType)
                                        <label class="flex items-center">
                                            <input type="checkbox" name="fuel_type[]" value="{{ $fuelType }}"
                                                {{ in_array($fuelType, (array) request('fuel_type', [])) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-700 capitalize">{{ $fuelType }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Transmission -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Transmission
                                </label>
                                <div class="space-y-2">
                                    @foreach($availableTransmissions as $trans)
                                        <label class="flex items-center">
                                            <input type="checkbox" name="transmission[]" value="{{ $trans }}"
                                                {{ in_array($trans, (array) request('transmission', [])) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-700 capitalize">{{ $trans }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Exterior Color -->
                            @if($availableColors->count() > 0)
                                <div>
                                    <label for="exterior_color" class="block text-sm font-medium text-gray-700 mb-2">
                                        Color
                                    </label>
                                    <select id="exterior_color" name="exterior_color"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Any Color</option>
                                        @foreach($availableColors as $color)
                                            <option value="{{ $color }}" {{ request('exterior_color') == $color ? 'selected' : '' }}>
                                                {{ $color }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <!-- Seats Range -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Number of Seats
                                </label>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="number" name="min_seats" value="{{ request('min_seats') }}"
                                        placeholder="Min"
                                        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <input type="number" name="max_seats" value="{{ request('max_seats') }}"
                                        placeholder="Max"
                                        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>

                            <!-- Featured Only -->
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="featured_only" value="1"
                                        {{ request('featured_only') ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700">Featured vehicles only</span>
                                </label>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-2">
                                <button type="submit"
                                    class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Apply Filters
                                </button>
                                <a href="{{ route('search') }}"
                                    class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Clear
                                </a>
                            </div>
                        </form>
                    </div>
                </aside>

                <!-- Results -->
                <div class="lg:col-span-3">
                    <!-- Sort and View Options -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-4">
                            <label for="sort_by" class="text-sm font-medium text-gray-700">Sort by:</label>
                            <form method="GET" action="{{ route('search') }}" class="flex items-center gap-2">
                                <!-- Preserve all query parameters -->
                                @foreach(request()->except(['sort_by', 'sort_order']) as $key => $value)
                                    @if(is_array($value))
                                        @foreach($value as $v)
                                            <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                        @endforeach
                                    @else
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endif
                                @endforeach
                                
                                <select name="sort_by" id="sort_by" onchange="this.form.submit()"
                                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Newest</option>
                                    <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>Price</option>
                                    <option value="year" {{ request('sort_by') == 'year' ? 'selected' : '' }}>Year</option>
                                    <option value="mileage" {{ request('sort_by') == 'mileage' ? 'selected' : '' }}>Mileage</option>
                                    <option value="views" {{ request('sort_by') == 'views' ? 'selected' : '' }}>Most Viewed</option>
                                </select>

                                <select name="sort_order" onchange="this.form.submit()"
                                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    <option value="desc" {{ request('sort_order', 'desc') == 'desc' ? 'selected' : '' }}>Descending</option>
                                    <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                                </select>
                            </form>
                        </div>

                        <!-- Mobile Filter Button -->
                        <button type="button" class="lg:hidden bg-white px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Filters
                        </button>
                    </div>

                    <!-- Car Grid -->
                    @if($cars->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($cars as $car)
                                @include('cars.partials.car-card', ['car' => $car])
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-8">
                            {{ $cars->links() }}
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">No vehicles found</h3>
                            <p class="mt-2 text-sm text-gray-500">
                                Try adjusting your search criteria or
                                <a href="{{ route('search') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">clear all filters</a>
                            </p>
                            <div class="mt-6">
                                <a href="{{ route('cars.index') }}"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Browse All Vehicles
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
