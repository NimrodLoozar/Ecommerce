<x-app-layout>
    <div class="bg-white">
        <!-- Brand Header -->
        <div class="relative overflow-hidden bg-gray-900">
            @if ($brand->image)
                <img src="{{ asset($brand->image) }}" alt="{{ $brand->name }}"
                    class="absolute inset-0 size-full object-cover opacity-30" />
            @endif
            <div class="relative mx-auto max-w-7xl px-6 py-24 sm:py-32 lg:px-8">
                <div class="mx-auto max-w-2xl lg:mx-0">
                    @if ($brand->logo)
                        <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}" class="h-16 w-auto mb-6" />
                    @endif
                    <h1 class="text-4xl font-bold tracking-tight text-white sm:text-6xl">{{ $brand->name }}</h1>
                    @if ($brand->description)
                        <p class="mt-6 text-lg leading-8 text-gray-300">{{ $brand->description }}</p>
                    @endif
                    <div class="mt-10 flex items-center gap-x-6">
                        <div class="text-white">
                            <span class="text-4xl font-bold">{{ $carsCount }}</span>
                            <span class="ml-2 text-lg">{{ Str::plural('vehicle', $carsCount) }} available</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                    <li class="font-medium text-gray-500">{{ $brand->name }}</li>
                </ol>
            </nav>

            <!-- Filter and Sort Bar -->
            <div class="flex items-center justify-between border-b border-gray-200 pb-6">
                <div class="flex items-center">
                    <h2 class="text-xl font-bold text-gray-900">
                        {{ $brand->name }} Vehicles
                    </h2>
                    <span class="ml-3 text-sm text-gray-500">
                        ({{ $cars->total() }} {{ Str::plural('result', $cars->total()) }})
                    </span>
                </div>

                <!-- Sort Menu -->
                <div class="flex items-center gap-4">
                    <!-- Mobile Filter Button -->
                    <button type="button" command="show-modal" commandfor="filters-dialog"
                        class="lg:hidden inline-flex items-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                        <svg class="-ml-0.5 size-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                            aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M2.628 1.601C5.028 1.206 7.49 1 10 1s4.973.206 7.372.601a.75.75 0 0 1 .628.74v2.288a2.25 2.25 0 0 1-.659 1.59l-4.682 4.683a2.25 2.25 0 0 0-.659 1.59v3.037c0 .684-.31 1.33-.844 1.757l-1.937 1.55A.75.75 0 0 1 8 18.25v-5.757a2.25 2.25 0 0 0-.659-1.591L2.659 6.22A2.25 2.25 0 0 1 2 4.629V2.34a.75.75 0 0 1 .628-.74Z"
                                clip-rule="evenodd" />
                        </svg>
                        Filters
                    </button>

                    <!-- Sort Dropdown -->
                    <el-popover anchor="bottom-end" class="relative">
                        <button type="button" popovertarget="sort-menu"
                            class="inline-flex items-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            Sort
                            <svg class="-mr-1 size-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                                aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div id="sort-menu" popover
                            class="w-40 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none">
                            <div class="py-1">
                                <a href="{{ route('brands.show', array_merge(['brand' => $brand->slug], request()->except('sort_by', 'sort_order'))) }}"
                                    class="block px-4 py-2 text-sm {{ !request('sort_by') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">
                                    Newest First
                                </a>
                                <a href="{{ route('brands.show', array_merge(['brand' => $brand->slug], request()->except('sort_by', 'sort_order'), ['sort_by' => 'price', 'sort_order' => 'asc'])) }}"
                                    class="block px-4 py-2 text-sm {{ request('sort_by') === 'price' && request('sort_order') === 'asc' ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">
                                    Price: Low to High
                                </a>
                                <a href="{{ route('brands.show', array_merge(['brand' => $brand->slug], request()->except('sort_by', 'sort_order'), ['sort_by' => 'price', 'sort_order' => 'desc'])) }}"
                                    class="block px-4 py-2 text-sm {{ request('sort_by') === 'price' && request('sort_order') === 'desc' ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">
                                    Price: High to Low
                                </a>
                                <a href="{{ route('brands.show', array_merge(['brand' => $brand->slug], request()->except('sort_by', 'sort_order'), ['sort_by' => 'year', 'sort_order' => 'desc'])) }}"
                                    class="block px-4 py-2 text-sm {{ request('sort_by') === 'year' ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">
                                    Year: Newest
                                </a>
                                <a href="{{ route('brands.show', array_merge(['brand' => $brand->slug], request()->except('sort_by', 'sort_order'), ['sort_by' => 'mileage', 'sort_order' => 'asc'])) }}"
                                    class="block px-4 py-2 text-sm {{ request('sort_by') === 'mileage' ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">
                                    Mileage: Low to High
                                </a>
                            </div>
                        </div>
                    </el-popover>
                </div>
            </div>

            <!-- Main Content -->
            <div class="mt-6 lg:grid lg:grid-cols-12 lg:gap-x-8">
                <!-- Filters Sidebar (Desktop) -->
                <aside class="hidden lg:block lg:col-span-3">
                    <h3 class="sr-only">Filters</h3>
                    @include('brands.partials.filters-form', ['brand' => $brand])
                </aside>

                <!-- Mobile Filters Dialog -->
                <el-dialog id="filters-dialog" class="relative z-50 lg:hidden">
                    <el-dialog-backdrop class="fixed inset-0 bg-black/25"></el-dialog-backdrop>
                    <div class="fixed inset-0 z-50 flex">
                        <el-dialog-panel
                            class="relative ml-auto flex size-full max-w-xs flex-col overflow-y-auto bg-white py-4 pb-6 shadow-xl">
                            <div class="flex items-center justify-between px-4">
                                <h2 class="text-lg font-medium text-gray-900">Filters</h2>
                                <button type="button" command="hide-modal" commandfor="filters-dialog"
                                    class="-mr-2 flex size-10 items-center justify-center rounded-md bg-white p-2 text-gray-400 hover:bg-gray-50">
                                    <span class="sr-only">Close menu</span>
                                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="mt-4 px-4">
                                @include('brands.partials.filters-form', ['brand' => $brand])
                            </div>
                        </el-dialog-panel>
                    </div>
                </el-dialog>

                <!-- Car Grid -->
                <div class="lg:col-span-9">
                    @if ($cars->count() > 0)
                        <!-- Results Info -->
                        <div class="mb-6 text-sm text-gray-700">
                            Showing {{ $cars->firstItem() }} to {{ $cars->lastItem() }} of {{ $cars->total() }}
                            results
                        </div>

                        <!-- Car Grid -->
                        <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 xl:gap-x-8">
                            @foreach ($cars as $car)
                                @include('cars.partials.car-card', ['car' => $car])
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-12">
                            {{ $cars->links() }}
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-12">
                            <svg class="mx-auto size-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-semibold text-gray-900">No vehicles found</h3>
                            <p class="mt-1 text-sm text-gray-500">Try adjusting your filters to find what you're
                                looking for.</p>
                            <div class="mt-6">
                                <a href="{{ route('brands.show', $brand->slug) }}"
                                    class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                    Clear all filters
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
