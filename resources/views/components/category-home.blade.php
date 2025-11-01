<div class="bg-gradient-to-b from-black/30 to-transparent">
    <div class="mx-auto max-w-[1800px] px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl py-16 sm:py-24 lg:max-w-none lg:py-32">
            <h2 class="text-2xl font-bold text-gray-900">Collections</h2>

            <div class="mt-6 space-y-12 md:space-y-0 grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6">
                @php
                    $items = [
                        ['src' => 'img/renault.webp', 'alt' => 'Renault Car', 'title' => "Renault's"],
                        ['src' => 'img/BMW.webp', 'alt' => 'BMW Car', 'title' => "BMW's"],
                        ['src' => 'img/audi.avif', 'alt' => 'Audi Car', 'title' => "Audi's"],
                        ['src' => 'img/peugeot.jpg', 'alt' => 'Peugeot Car', 'title' => "Peugoet's"],
                        ['src' => 'img/mercedes.avif', 'alt' => 'Mercedes Car', 'title' => 'Mercedes'],
                        ['src' => 'img/link&co.jpg', 'alt' => 'Link & Co Car', 'title' => "Link & Co's'"],
                    ];
                @endphp

                @foreach ($items as $item)
                    <div class="group relative rounded-lg overflow-hidden shadow-2xl border border-gray-200">
                        <img src="{{ asset($item['src']) }}" alt="{{ $item['alt'] }}"
                            class="w-full h-64 sm:h-56 lg:h-72 xl:h-80 object-cover transition-transform duration-300 group-hover:scale-105" />

                        <!-- Gradient overlay: transparent at top -> darker at bottom -->
                        <div
                            class="absolute inset-x-0 bottom-0 p-4 bg-gradient-to-t from-black/60 to-transparent text-white">
                            <a href="#" class="block">
                                <h3 class="text-lg font-semibold">{{ $item['title'] }}</h3>
                                <p class="text-sm mt-1 opacity-90">Explore the collection</p>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
