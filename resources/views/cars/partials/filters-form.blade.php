<!-- Brand Filter -->
<div class="border-t border-gray-200 px-4 py-6">
    <h3 class="-mx-2 -my-3 flow-root">
        <button popovertarget="filter-brand" type="button"
            class="flex w-full items-center justify-between bg-white px-2 py-3 text-gray-400 hover:text-gray-500">
            <span class="font-medium text-gray-900">Brand</span>
            <span class="ml-6 flex items-center">
                <svg class="size-5 group-has-open:hidden" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path
                        d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                </svg>
                <svg class="size-5 hidden group-has-open:block" viewBox="0 0 20 20" fill="currentColor"
                    aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M4 10a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H4.75A.75.75 0 0 1 4 10Z"
                        clip-rule="evenodd" />
                </svg>
            </span>
        </button>
    </h3>
    <div id="filter-brand" popover class="pt-6">
        <div class="space-y-4">
            @foreach ($brands as $brand)
                <div class="flex gap-3">
                    <div class="flex h-5 shrink-0 items-center">
                        <input id="brand-{{ $brand->id }}" name="brand_id" value="{{ $brand->id }}" type="radio"
                            {{ request('brand_id') == $brand->id ? 'checked' : '' }}
                            class="size-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                    </div>
                    <label for="brand-{{ $brand->id }}" class="text-sm text-gray-600">{{ $brand->name }}</label>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Category Filter -->
<div class="border-t border-gray-200 px-4 py-6">
    <h3 class="-mx-2 -my-3 flow-root">
        <button popovertarget="filter-category" type="button"
            class="flex w-full items-center justify-between bg-white px-2 py-3 text-gray-400 hover:text-gray-500">
            <span class="font-medium text-gray-900">Category</span>
            <span class="ml-6 flex items-center">
                <svg class="size-5 group-has-open:hidden" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path
                        d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                </svg>
                <svg class="size-5 hidden group-has-open:block" viewBox="0 0 20 20" fill="currentColor"
                    aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M4 10a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H4.75A.75.75 0 0 1 4 10Z"
                        clip-rule="evenodd" />
                </svg>
            </span>
        </button>
    </h3>
    <div id="filter-category" popover class="pt-6">
        <div class="space-y-4">
            @foreach ($categories as $category)
                <div class="flex gap-3">
                    <div class="flex h-5 shrink-0 items-center">
                        <input id="category-{{ $category->id }}" name="category_id" value="{{ $category->id }}"
                            type="radio" {{ request('category_id') == $category->id ? 'checked' : '' }}
                            class="size-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                    </div>
                    <label for="category-{{ $category->id }}"
                        class="text-sm text-gray-600">{{ $category->name }}</label>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Condition Filter -->
<div class="border-t border-gray-200 px-4 py-6">
    <h3 class="-mx-2 -my-3 flow-root">
        <button popovertarget="filter-condition" type="button"
            class="flex w-full items-center justify-between bg-white px-2 py-3 text-gray-400 hover:text-gray-500">
            <span class="font-medium text-gray-900">Condition</span>
            <span class="ml-6 flex items-center">
                <svg class="size-5 group-has-open:hidden" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path
                        d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                </svg>
                <svg class="size-5 hidden group-has-open:block" viewBox="0 0 20 20" fill="currentColor"
                    aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M4 10a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H4.75A.75.75 0 0 1 4 10Z"
                        clip-rule="evenodd" />
                </svg>
            </span>
        </button>
    </h3>
    <div id="filter-condition" popover class="pt-6">
        <div class="space-y-4">
            @foreach ($conditions as $condition)
                <div class="flex gap-3">
                    <div class="flex h-5 shrink-0 items-center">
                        <input id="condition-{{ $condition->id }}" name="condition_id" value="{{ $condition->id }}"
                            type="radio" {{ request('condition_id') == $condition->id ? 'checked' : '' }}
                            class="size-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                    </div>
                    <label for="condition-{{ $condition->id }}"
                        class="text-sm text-gray-600">{{ $condition->name }}</label>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Price Range Filter -->
<div class="border-t border-gray-200 px-4 py-6">
    <h3 class="-mx-2 -my-3 flow-root">
        <button popovertarget="filter-price" type="button"
            class="flex w-full items-center justify-between bg-white px-2 py-3 text-gray-400 hover:text-gray-500">
            <span class="font-medium text-gray-900">Price Range</span>
            <span class="ml-6 flex items-center">
                <svg class="size-5 group-has-open:hidden" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path
                        d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                </svg>
                <svg class="size-5 hidden group-has-open:block" viewBox="0 0 20 20" fill="currentColor"
                    aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M4 10a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H4.75A.75.75 0 0 1 4 10Z"
                        clip-rule="evenodd" />
                </svg>
            </span>
        </button>
    </h3>
    <div id="filter-price" popover class="pt-6">
        <div class="space-y-4">
            <div>
                <label for="min_price" class="block text-sm font-medium text-gray-700">Min Price (€)</label>
                <input type="number" name="min_price" id="min_price" value="{{ request('min_price') }}"
                    placeholder="0" min="0" step="1000"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
            </div>
            <div>
                <label for="max_price" class="block text-sm font-medium text-gray-700">Max Price (€)</label>
                <input type="number" name="max_price" id="max_price" value="{{ request('max_price') }}"
                    placeholder="100000" min="0" step="1000"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
            </div>
        </div>
    </div>
</div>

<!-- Year Range Filter -->
<div class="border-t border-gray-200 px-4 py-6">
    <h3 class="-mx-2 -my-3 flow-root">
        <button popovertarget="filter-year" type="button"
            class="flex w-full items-center justify-between bg-white px-2 py-3 text-gray-400 hover:text-gray-500">
            <span class="font-medium text-gray-900">Year</span>
            <span class="ml-6 flex items-center">
                <svg class="size-5 group-has-open:hidden" viewBox="0 0 20 20" fill="currentColor"
                    aria-hidden="true">
                    <path
                        d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                </svg>
                <svg class="size-5 hidden group-has-open:block" viewBox="0 0 20 20" fill="currentColor"
                    aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M4 10a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H4.75A.75.75 0 0 1 4 10Z"
                        clip-rule="evenodd" />
                </svg>
            </span>
        </button>
    </h3>
    <div id="filter-year" popover class="pt-6">
        <div class="space-y-4">
            <div>
                <label for="min_year" class="block text-sm font-medium text-gray-700">Min Year</label>
                <input type="number" name="min_year" id="min_year" value="{{ request('min_year') }}"
                    placeholder="2000" min="1980" max="{{ date('Y') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
            </div>
            <div>
                <label for="max_year" class="block text-sm font-medium text-gray-700">Max Year</label>
                <input type="number" name="max_year" id="max_year" value="{{ request('max_year') }}"
                    placeholder="{{ date('Y') }}" min="1980" max="{{ date('Y') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
            </div>
        </div>
    </div>
</div>

<!-- Fuel Type Filter -->
<div class="border-t border-gray-200 px-4 py-6">
    <h3 class="-mx-2 -my-3 flow-root">
        <button popovertarget="filter-fuel" type="button"
            class="flex w-full items-center justify-between bg-white px-2 py-3 text-gray-400 hover:text-gray-500">
            <span class="font-medium text-gray-900">Fuel Type</span>
            <span class="ml-6 flex items-center">
                <svg class="size-5 group-has-open:hidden" viewBox="0 0 20 20" fill="currentColor"
                    aria-hidden="true">
                    <path
                        d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                </svg>
                <svg class="size-5 hidden group-has-open:block" viewBox="0 0 20 20" fill="currentColor"
                    aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M4 10a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H4.75A.75.75 0 0 1 4 10Z"
                        clip-rule="evenodd" />
                </svg>
            </span>
        </button>
    </h3>
    <div id="filter-fuel" popover class="pt-6">
        <div class="space-y-4">
            @foreach (['gasoline', 'diesel', 'electric', 'hybrid', 'plug-in hybrid'] as $fuelType)
                <div class="flex gap-3">
                    <div class="flex h-5 shrink-0 items-center">
                        <input id="fuel-{{ $fuelType }}" name="fuel_type" value="{{ $fuelType }}"
                            type="radio" {{ request('fuel_type') == $fuelType ? 'checked' : '' }}
                            class="size-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                    </div>
                    <label for="fuel-{{ $fuelType }}"
                        class="text-sm text-gray-600">{{ ucfirst($fuelType) }}</label>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Transmission Filter -->
<div class="border-t border-gray-200 px-4 py-6">
    <h3 class="-mx-2 -my-3 flow-root">
        <button popovertarget="filter-transmission" type="button"
            class="flex w-full items-center justify-between bg-white px-2 py-3 text-gray-400 hover:text-gray-500">
            <span class="font-medium text-gray-900">Transmission</span>
            <span class="ml-6 flex items-center">
                <svg class="size-5 group-has-open:hidden" viewBox="0 0 20 20" fill="currentColor"
                    aria-hidden="true">
                    <path
                        d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                </svg>
                <svg class="size-5 hidden group-has-open:block" viewBox="0 0 20 20" fill="currentColor"
                    aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M4 10a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H4.75A.75.75 0 0 1 4 10Z"
                        clip-rule="evenodd" />
                </svg>
            </span>
        </button>
    </h3>
    <div id="filter-transmission" popover class="pt-6">
        <div class="space-y-4">
            @foreach (['manual', 'automatic', 'semi-automatic'] as $transmissionType)
                <div class="flex gap-3">
                    <div class="flex h-5 shrink-0 items-center">
                        <input id="transmission-{{ $transmissionType }}" name="transmission"
                            value="{{ $transmissionType }}" type="radio"
                            {{ request('transmission') == $transmissionType ? 'checked' : '' }}
                            class="size-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                    </div>
                    <label for="transmission-{{ $transmissionType }}"
                        class="text-sm text-gray-600">{{ ucfirst($transmissionType) }}</label>
                </div>
            @endforeach
        </div>
    </div>
</div>
