<x-app-layout>
    <div class="bg-white">
        <div>
            <!-- Mobile filter dialog -->
            <el-dialog>
                <dialog id="mobile-filters" aria-labelledby="mobile-filters-title" class="backdrop:bg-transparent">
                    <el-dialog-backdrop
                        class="fixed inset-0 bg-black/25 transition-opacity duration-300 ease-linear data-closed:opacity-0">
                    </el-dialog-backdrop>
                    <div tabindex="0" class="fixed inset-0 z-40 flex focus:outline-none">
                        <el-dialog-panel
                            class="relative ml-auto flex size-full max-w-xs transform flex-col overflow-y-auto bg-white py-4 pb-12 shadow-xl transition duration-300 ease-in-out data-closed:translate-x-full">
                            <div class="flex items-center justify-between px-4">
                                <h2 id="mobile-filters-title" class="text-lg font-medium text-gray-900">Filters</h2>
                                <button type="button" command="close" commandfor="mobile-filters"
                                    class="-mr-2 flex size-10 items-center justify-center rounded-md bg-white p-2 text-gray-400">
                                    <span class="sr-only">Close menu</span>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                        class="size-6" aria-hidden="true">
                                        <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Filters -->
                            <form method="GET" action="{{ route('cars.index') }}"
                                class="mt-4 border-t border-gray-200">
                                @include('cars.partials.filters-form')

                                <div class="px-4 py-6 border-t border-gray-200">
                                    <button type="submit"
                                        class="w-full rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                                        Apply Filters
                                    </button>
                                    <a href="{{ route('cars.index') }}"
                                        class="mt-2 block w-full text-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                        Clear All
                                    </a>
                                </div>
                            </form>
                        </el-dialog-panel>
                    </div>
                </dialog>
            </el-dialog>

            <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex items-baseline justify-between border-b border-gray-200 pt-12 pb-6">
                    <h1 class="text-4xl font-bold tracking-tight text-gray-900">Browse Cars</h1>

                    <div class="flex items-center">
                        <div class="relative inline-block text-left">
                            <el-popover-group>
                                <div class="group/popover">
                                    <button popovertarget="sort-menu" type="button"
                                        class="group inline-flex justify-center text-sm font-medium text-gray-700 hover:text-gray-900">
                                        Sort
                                        <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"
                                            class="-mr-1 ml-1 size-5 shrink-0 text-gray-400 group-hover:text-gray-500">
                                            <path fill-rule="evenodd"
                                                d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>

                                    <el-popover id="sort-menu" popover anchor="bottom"
                                        class="absolute right-0 z-10 mt-2 w-40 origin-top-right rounded-md bg-white shadow-2xl ring-1 ring-black/5 transition focus:outline-none data-closed:scale-95 data-closed:opacity-0 data-enter:duration-100 data-enter:ease-out data-leave:duration-75 data-leave:ease-in">
                                        <div class="py-1">
                                            <a href="{{ route('cars.index', array_merge(request()->except(['sort_by', 'sort_order']), ['sort_by' => 'created_at', 'sort_order' => 'desc'])) }}"
                                                class="block px-4 py-2 text-sm text-gray-500 hover:bg-gray-100 {{ request('sort_by') == 'created_at' ? 'font-medium text-gray-900' : '' }}">
                                                Newest
                                            </a>
                                            <a href="{{ route('cars.index', array_merge(request()->except(['sort_by', 'sort_order']), ['sort_by' => 'price', 'sort_order' => 'asc'])) }}"
                                                class="block px-4 py-2 text-sm text-gray-500 hover:bg-gray-100 {{ request('sort_by') == 'price' && request('sort_order') == 'asc' ? 'font-medium text-gray-900' : '' }}">
                                                Price: Low to High
                                            </a>
                                            <a href="{{ route('cars.index', array_merge(request()->except(['sort_by', 'sort_order']), ['sort_by' => 'price', 'sort_order' => 'desc'])) }}"
                                                class="block px-4 py-2 text-sm text-gray-500 hover:bg-gray-100 {{ request('sort_by') == 'price' && request('sort_order') == 'desc' ? 'font-medium text-gray-900' : '' }}">
                                                Price: High to Low
                                            </a>
                                            <a href="{{ route('cars.index', array_merge(request()->except(['sort_by', 'sort_order']), ['sort_by' => 'year', 'sort_order' => 'desc'])) }}"
                                                class="block px-4 py-2 text-sm text-gray-500 hover:bg-gray-100 {{ request('sort_by') == 'year' ? 'font-medium text-gray-900' : '' }}">
                                                Year
                                            </a>
                                            <a href="{{ route('cars.index', array_merge(request()->except(['sort_by', 'sort_order']), ['sort_by' => 'mileage', 'sort_order' => 'asc'])) }}"
                                                class="block px-4 py-2 text-sm text-gray-500 hover:bg-gray-100 {{ request('sort_by') == 'mileage' ? 'font-medium text-gray-900' : '' }}">
                                                Mileage
                                            </a>
                                        </div>
                                    </el-popover>
                                </div>
                            </el-popover-group>
                        </div>

                        <button type="button" command="show-modal" commandfor="mobile-filters"
                            class="-m-2 ml-4 p-2 text-gray-400 hover:text-gray-500 sm:ml-6 lg:hidden">
                            <span class="sr-only">Filters</span>
                            <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="size-5">
                                <path fill-rule="evenodd"
                                    d="M2.628 1.601C5.028 1.206 7.49 1 10 1s4.973.206 7.372.601a.75.75 0 0 1 .628.74v2.288a2.25 2.25 0 0 1-.659 1.59l-4.682 4.683a2.25 2.25 0 0 0-.659 1.59v3.037c0 .684-.31 1.33-.844 1.757l-1.937 1.55A.75.75 0 0 1 8 18.25v-5.757a2.25 2.25 0 0 0-.659-1.591L2.659 6.22A2.25 2.25 0 0 1 2 4.629V2.34a.75.75 0 0 1 .628-.74Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>

                <section aria-labelledby="products-heading" class="pt-6 pb-24">
                    <h2 id="products-heading" class="sr-only">Cars</h2>

                    <div class="grid grid-cols-1 gap-x-8 gap-y-10 lg:grid-cols-4">
                        <!-- Filters (Desktop) -->
                        <form method="GET" action="{{ route('cars.index') }}" class="hidden lg:block">
                            @include('cars.partials.filters-form')

                            <div class="mt-6">
                                <button type="submit"
                                    class="w-full rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                                    Apply Filters
                                </button>
                                <a href="{{ route('cars.index') }}"
                                    class="mt-2 block w-full text-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                    Clear All
                                </a>
                            </div>
                        </form>

                        <!-- Car grid -->
                        <div class="lg:col-span-3">
                            @if ($cars->count() > 0)
                                <div class="mb-4 text-sm text-gray-700">
                                    Showing <span class="font-medium">{{ $cars->firstItem() }}</span> to
                                    <span class="font-medium">{{ $cars->lastItem() }}</span> of
                                    <span class="font-medium">{{ $cars->total() }}</span> results
                                </div>

                                <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 xl:gap-x-8">
                                    @foreach ($cars as $car)
                                        @include('cars.partials.car-card', ['car' => $car])
                                    @endforeach
                                </div>

                                <!-- Pagination -->
                                <div class="mt-8">
                                    {{ $cars->links() }}
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <svg class="mx-auto size-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No cars found</h3>
                                    <p class="mt-1 text-sm text-gray-500">Try adjusting your filters to find what
                                        you're looking for.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('cars.index') }}"
                                            class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500">
                                            Clear filters
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>
</x-app-layout>
