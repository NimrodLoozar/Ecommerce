@props(['brands' => []])

<div class="bg-gradient-to-b from-black/30 to-transparent">
    <div class="mx-auto max-w-[1800px] px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl py-16 sm:py-24 lg:max-w-none lg:py-32">
            <h2 class="text-2xl font-bold text-gray-900">Browse by Brand</h2>

            <div class="mt-6 space-y-12 md:space-y-0 grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6">
                @forelse($brands as $brand)
                @if ($brand->image)
                <a href="{{ route('brands.show', $brand->slug) }}" class="block">
                    <div class="group relative rounded-lg overflow-hidden shadow-2xl border border-gray-200">
                        <img src="{{ asset($brand->image) }}" alt="{{ $brand->name }}"
                            class="w-full h-64 sm:h-56 lg:h-72 xl:h-80 object-cover transition-transform duration-300 group-hover:scale-105" />

                        <!-- Gradient overlay: transparent at top -> darker at bottom -->
                        <div
                            class="absolute inset-x-0 bottom-0 p-4 bg-gradient-to-t from-black/60 to-transparent text-white">
                            <h3 class="text-lg font-semibold">{{ $brand->name }}</h3>
                            <p class="text-sm mt-1 opacity-90">Explore the collection</p>
                        </div>
                    </div>
                </a>
                @endif
                @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500">No brands available at the moment.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>