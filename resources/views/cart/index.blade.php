<x-app-layout>
    <div class="bg-white">
        <div class="mx-auto max-w-2xl px-4 pb-24 pt-16 sm:px-6 lg:max-w-7xl lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Shopping Cart</h1>

            @if ($cartItems->count() > 0)
                <form action="{{ route('cart.update') }}" method="POST"
                    class="mt-12 lg:grid lg:grid-cols-12 lg:items-start lg:gap-x-12 xl:gap-x-16">
                    @csrf
                    <section aria-labelledby="cart-heading" class="lg:col-span-7">
                        <h2 id="cart-heading" class="sr-only">Items in your shopping cart</h2>

                        <ul role="list" class="divide-y divide-gray-200 border-b border-t border-gray-200">
                            @foreach ($cartItems as $item)
                                <li class="flex py-6 sm:py-10">
                                    <div class="shrink-0">
                                        @php
                                            $image = $item->car->images->first();
                                            $imageUrl = $image
                                                ? asset('storage/' . $image->image_path)
                                                : 'https://via.placeholder.com/400x300?text=' .
                                                    urlencode($item->car->brand->name);
                                        @endphp
                                        <img src="{{ $imageUrl }}" alt="{{ $item->car->title }}"
                                            class="size-24 rounded-md object-cover object-center sm:size-48">
                                    </div>

                                    <div class="ml-4 flex flex-1 flex-col justify-between sm:ml-6">
                                        <div class="relative pr-9 sm:grid sm:grid-cols-2 sm:gap-x-6 sm:pr-0">
                                            <div>
                                                <div class="flex justify-between">
                                                    <h3 class="text-sm">
                                                        <a href="{{ route('cars.show', $item->car->id) }}"
                                                            class="font-medium text-gray-700 hover:text-gray-800">
                                                            {{ $item->car->title }}
                                                        </a>
                                                    </h3>
                                                </div>
                                                <div class="mt-1 flex text-sm">
                                                    <p class="text-gray-500">{{ $item->car->brand->name }}
                                                        {{ $item->car->carModel->name }}</p>
                                                </div>
                                                <div class="mt-1 flex text-sm">
                                                    <p class="text-gray-500">{{ $item->car->year }} •
                                                        {{ ucfirst(str_replace('_', ' ', $item->car->fuel_type)) }}</p>
                                                    <p class="ml-4 border-l border-gray-200 pl-4 text-gray-500">
                                                        {{ number_format($item->car->mileage) }} km</p>
                                                </div>
                                                <p class="mt-1 text-sm font-medium text-gray-900">
                                                    €{{ number_format($item->car->price, 0, ',', '.') }}</p>
                                            </div>

                                            <div class="mt-4 sm:mt-0 sm:pr-9">
                                                <label for="quantity-{{ $item->id }}" class="sr-only">Quantity,
                                                    {{ $item->car->title }}</label>
                                                <select id="quantity-{{ $item->id }}"
                                                    name="quantities[{{ $item->id }}]"
                                                    class="max-w-full rounded-md border border-gray-300 py-1.5 text-left text-base font-medium text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm">
                                                    @for ($i = 1; $i <= min(10, $item->car->stock_quantity); $i++)
                                                        <option value="{{ $i }}"
                                                            {{ $item->quantity == $i ? 'selected' : '' }}>
                                                            {{ $i }}</option>
                                                    @endfor
                                                </select>

                                                <div class="absolute right-0 top-0">
                                                    <button type="button"
                                                        onclick="removeFromCart({{ $item->id }})"
                                                        class="-m-2 inline-flex p-2 text-gray-400 hover:text-gray-500">
                                                        <span class="sr-only">Remove</span>
                                                        <svg class="size-5" viewBox="0 0 20 20" fill="currentColor"
                                                            aria-hidden="true">
                                                            <path
                                                                d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($item->car->stock_quantity < 3)
                                            <p class="mt-4 flex space-x-2 text-sm text-gray-700">
                                                <svg class="size-5 shrink-0 text-red-500" viewBox="0 0 20 20"
                                                    fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM8.28 7.22a.75.75 0 0 0-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 1 0 1.06 1.06L10 11.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L11.06 10l1.72-1.72a.75.75 0 0 0-1.06-1.06L10 8.94 8.28 7.22Z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span>Only {{ $item->car->stock_quantity }} left in stock</span>
                                            </p>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </section>

                    <!-- Order summary -->
                    <section aria-labelledby="summary-heading"
                        class="mt-16 rounded-lg bg-gray-50 px-4 py-6 sm:p-6 lg:col-span-5 lg:mt-0 lg:p-8">
                        <h2 id="summary-heading" class="text-lg font-medium text-gray-900">Order summary</h2>

                        <dl class="mt-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <dt class="text-sm text-gray-600">Subtotal</dt>
                                <dd class="text-sm font-medium text-gray-900">
                                    €{{ number_format($subtotal, 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                                <dt class="flex items-center text-sm text-gray-600">
                                    <span>Shipping estimate</span>
                                    <button type="button" class="ml-2 shrink-0 text-gray-400 hover:text-gray-500">
                                        <span class="sr-only">Learn more about how shipping is calculated</span>
                                        <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0ZM8.94 6.94a.75.75 0 1 1-1.061-1.061 3 3 0 1 1 2.871 5.026v.345a.75.75 0 0 1-1.5 0v-.5c0-.72.57-1.172 1.081-1.287A1.5 1.5 0 1 0 8.94 6.94ZM10 15a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </dt>
                                <dd class="text-sm font-medium text-gray-900">
                                    €{{ number_format($shipping, 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                                <dt class="flex text-sm text-gray-600">
                                    <span>Tax estimate</span>
                                    <button type="button" class="ml-2 shrink-0 text-gray-400 hover:text-gray-500">
                                        <span class="sr-only">Learn more about how tax is calculated</span>
                                        <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0ZM8.94 6.94a.75.75 0 1 1-1.061-1.061 3 3 0 1 1 2.871 5.026v.345a.75.75 0 0 1-1.5 0v-.5c0-.72.57-1.172 1.081-1.287A1.5 1.5 0 1 0 8.94 6.94ZM10 15a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </dt>
                                <dd class="text-sm font-medium text-gray-900">€{{ number_format($tax, 0, ',', '.') }}
                                </dd>
                            </div>
                            <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                                <dt class="text-base font-medium text-gray-900">Order total</dt>
                                <dd class="text-base font-medium text-gray-900">
                                    €{{ number_format($total, 0, ',', '.') }}</dd>
                            </div>
                        </dl>

                        <div class="mt-6">
                            <button type="submit"
                                class="w-full rounded-md border border-transparent bg-gray-600 px-4 py-3 text-base font-medium text-white shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:ring-offset-gray-50">
                                Update Cart
                            </button>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('checkout.index') }}"
                                class="block w-full rounded-md border border-transparent bg-indigo-600 px-4 py-3 text-base font-medium text-white text-center shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-50">
                                Proceed to Checkout
                            </a>
                        </div>

                        <div class="mt-6 text-center">
                            <p class="text-sm text-gray-500">
                                or
                                <a href="{{ route('cars.index') }}"
                                    class="font-medium text-indigo-600 hover:text-indigo-500">
                                    Continue Shopping
                                    <span aria-hidden="true"> &rarr;</span>
                                </a>
                            </p>
                        </div>
                    </section>
                </form>
            @else
                <!-- Empty Cart -->
                <div class="mt-12 text-center">
                    <svg class="mx-auto size-24 text-gray-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>
                    <h3 class="mt-6 text-2xl font-semibold text-gray-900">Your cart is empty</h3>
                    <p class="mt-2 text-base text-gray-500">Start adding some amazing vehicles to your cart.</p>
                    <div class="mt-8">
                        <a href="{{ route('cars.index') }}"
                            class="inline-flex items-center rounded-md bg-indigo-600 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Browse Vehicles
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if ($cartItems->count() > 0)
        <!-- Hidden form for removing items -->
        <form id="remove-form" action="{{ route('cart.remove') }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
            <input type="hidden" name="cart_item_id" id="remove-cart-item-id">
        </form>

        <script>
            function removeFromCart(itemId) {
                if (confirm('Are you sure you want to remove this item from your cart?')) {
                    document.getElementById('remove-cart-item-id').value = itemId;
                    document.getElementById('remove-form').submit();
                }
            }
        </script>
    @endif
</x-app-layout>
