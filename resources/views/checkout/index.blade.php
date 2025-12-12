<x-app-layout>
    <div class="bg-white">
        <div class="mx-auto max-w-2xl px-4 pb-24 pt-16 sm:px-6 lg:max-w-7xl lg:px-8">
            <h2 class="sr-only">Checkout</h2>

            <form action="{{ route('checkout.store') }}" method="POST"
                class="lg:grid lg:grid-cols-2 lg:gap-x-12 xl:gap-x-16">
                @csrf
                <div>
                    <div>
                        <h2 class="text-lg font-medium text-gray-900">Contact information</h2>

                        <div class="mt-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                            <div class="mt-1">
                                <input type="email" id="email" name="email" value="{{ auth()->user()->email }}"
                                    readonly
                                    class="block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="mt-10 border-t border-gray-200 pt-10">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-medium text-gray-900">Shipping information</h2>
                            <a href="{{ route('addresses.create') }}?return=checkout"
                                class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                Add new address
                            </a>
                        </div>

                        @if ($addresses->count() > 0)
                            <div class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                                @foreach ($addresses as $address)
                                    <label
                                        class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none {{ $loop->first ? 'border-indigo-600 ring-2 ring-indigo-600' : 'border-gray-300' }}">
                                        <input type="radio" name="shipping_address_id" value="{{ $address->id }}"
                                            {{ $loop->first ? 'checked' : '' }} class="sr-only">
                                        <span class="flex flex-1">
                                            <span class="flex flex-col">
                                                <span class="block text-sm font-medium text-gray-900">
                                                    {{ $address->first_name }} {{ $address->last_name }}
                                                </span>
                                                <span class="mt-1 flex items-center text-sm text-gray-500">
                                                    <span class="block">
                                                        {{ $address->address_line1 }}<br>
                                                        @if ($address->address_line2)
                                                            {{ $address->address_line2 }}<br>
                                                        @endif
                                                        {{ $address->city }}, {{ $address->state }}
                                                        {{ $address->postal_code }}<br>
                                                        {{ $address->country }}
                                                    </span>
                                                </span>
                                                @if ($address->phone)
                                                    <span
                                                        class="mt-1 text-sm text-gray-500">{{ $address->phone }}</span>
                                                @endif
                                            </span>
                                        </span>
                                        <svg class="size-5 text-indigo-600 {{ $loop->first ? '' : 'hidden' }}"
                                            viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span
                                            class="pointer-events-none absolute -inset-px rounded-lg border-2 {{ $loop->first ? 'border-indigo-600' : 'border-transparent' }}"
                                            aria-hidden="true"></span>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <div class="mt-4 rounded-md bg-yellow-50 p-4">
                                <div class="flex">
                                    <div class="shrink-0">
                                        <svg class="size-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"
                                            aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800">No shipping address</h3>
                                        <div class="mt-2 text-sm text-yellow-700">
                                            <p>You need to add a shipping address before you can complete your order.
                                            </p>
                                        </div>
                                        <div class="mt-4">
                                            <a href="{{ route('addresses.create') }}?return=checkout"
                                                class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1.5 text-sm font-medium text-yellow-800 hover:bg-yellow-100 focus:outline-none focus:ring-2 focus:ring-yellow-600 focus:ring-offset-2 focus:ring-offset-yellow-50">
                                                Add address now
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Billing Address -->
                    <div class="mt-10 border-t border-gray-200 pt-10">
                        <h2 class="text-lg font-medium text-gray-900">Billing information</h2>

                        <div class="mt-4 flex items-center">
                            <input id="same-as-shipping" name="billing_same_as_shipping" type="checkbox" checked
                                class="size-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="same-as-shipping" class="ml-2 block text-sm text-gray-900">
                                Same as shipping information
                            </label>
                        </div>

                        <div id="billing-address-section" class="mt-4 hidden">
                            <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                                @foreach ($addresses as $address)
                                    <label
                                        class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none border-gray-300">
                                        <input type="radio" name="billing_address_id" value="{{ $address->id }}"
                                            {{ $loop->first ? 'checked' : '' }} class="sr-only">
                                        <span class="flex flex-1">
                                            <span class="flex flex-col">
                                                <span class="block text-sm font-medium text-gray-900">
                                                    {{ $address->first_name }} {{ $address->last_name }}
                                                </span>
                                                <span class="mt-1 flex items-center text-sm text-gray-500">
                                                    <span class="block">
                                                        {{ $address->address_line1 }}<br>
                                                        {{ $address->city }}, {{ $address->state }}
                                                        {{ $address->postal_code }}
                                                    </span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Payment Gateway Selection -->
                    <div class="mt-10 border-t border-gray-200 pt-10">
                        <h2 class="text-lg font-medium text-gray-900">Payment Gateway</h2>

                        <fieldset class="mt-4">
                            <legend class="sr-only">Select payment gateway</legend>
                            <div class="space-y-4">
                                <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none border-indigo-600 ring-2 ring-indigo-600">
                                    <input type="radio" name="payment_gateway" value="stripe" checked class="sr-only" onchange="updatePaymentGateway()">
                                    <span class="flex flex-1">
                                        <span class="flex flex-col">
                                            <span class="block text-sm font-medium text-gray-900">Stripe</span>
                                            <span class="mt-1 text-sm text-gray-500">Secure credit card payments (Visa, Mastercard, Amex)</span>
                                        </span>
                                    </span>
                                    <svg class="size-5 text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                                    </svg>
                                </label>

                                <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none border-gray-300">
                                    <input type="radio" name="payment_gateway" value="mollie" class="sr-only" onchange="updatePaymentGateway()">
                                    <span class="flex flex-1">
                                        <span class="flex flex-col">
                                            <span class="block text-sm font-medium text-gray-900">Mollie</span>
                                            <span class="mt-1 text-sm text-gray-500">iDEAL, credit cards, and European payment methods</span>
                                        </span>
                                    </span>
                                    <svg class="size-5 text-indigo-600 hidden" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                                    </svg>
                                </label>

                                <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none border-gray-300">
                                    <input type="radio" name="payment_gateway" value="paypal" class="sr-only" onchange="updatePaymentGateway()">
                                    <span class="flex flex-1">
                                        <span class="flex flex-col">
                                            <span class="block text-sm font-medium text-gray-900">PayPal</span>
                                            <span class="mt-1 text-sm text-gray-500">Pay with your PayPal account or credit card</span>
                                        </span>
                                    </span>
                                    <svg class="size-5 text-indigo-600 hidden" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                                    </svg>
                                </label>
                            </div>
                        </fieldset>

                        <!-- Stripe Payment Details -->
                        <div id="stripe-details" class="mt-6">
                            <div id="card-element" class="rounded-md border border-gray-300 p-3 bg-white"></div>
                            <div id="card-errors" role="alert" class="mt-2 text-sm text-red-600"></div>
                            <input type="hidden" id="payment-intent-secret" name="payment_intent_secret">
                        </div>

                        <!-- Mollie Payment Details -->
                        <div id="mollie-details" class="mt-6 hidden">
                            <p class="text-sm text-gray-600 mb-4">
                                You will be redirected to Mollie to complete your payment securely.
                            </p>
                            <select name="mollie_method" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">All available methods</option>
                                <option value="ideal">iDEAL (Netherlands)</option>
                                <option value="creditcard">Credit Card</option>
                                <option value="bancontact">Bancontact (Belgium)</option>
                                <option value="sofort">SOFORT (Germany)</option>
                                <option value="giropay">Giropay (Germany)</option>
                            </select>
                        </div>

                        <!-- PayPal Payment Details -->
                        <div id="paypal-details" class="mt-6 hidden">
                            <p class="text-sm text-gray-600">
                                You will be redirected to PayPal to complete your payment.
                                You can pay with your PayPal account or credit card.
                            </p>
                        </div>
                    </div>

                    <!-- Order Notes -->
                    <div class="mt-10 border-t border-gray-200 pt-10">
                        <h2 class="text-lg font-medium text-gray-900">Order notes (optional)</h2>
                        <div class="mt-4">
                            <label for="notes" class="sr-only">Order notes</label>
                            <textarea id="notes" name="notes" rows="4" placeholder="Any special instructions for this order..."
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Order summary -->
                <div class="mt-10 lg:mt-0">
                    <h2 class="text-lg font-medium text-gray-900">Order summary</h2>

                    <div class="mt-4 rounded-lg border border-gray-200 bg-white shadow-sm">
                        <h3 class="sr-only">Items in your cart</h3>
                        <ul role="list" class="divide-y divide-gray-200">
                            @foreach ($cartItems as $item)
                                <li class="flex px-4 py-6 sm:px-6">
                                    <div class="shrink-0">
                                        @php
                                            $image = $item->car->images->first();
                                            $imageUrl = $image
                                                ? asset('storage/' . $image->image_path)
                                                : 'https://via.placeholder.com/200x150?text=' .
                                                    urlencode($item->car->brand->name);
                                        @endphp
                                        <img src="{{ $imageUrl }}" alt="{{ $item->car->title }}"
                                            class="w-20 rounded-md">
                                    </div>

                                    <div class="ml-6 flex flex-1 flex-col">
                                        <div class="flex">
                                            <div class="min-w-0 flex-1">
                                                <h4 class="text-sm">
                                                    <a href="{{ route('cars.show', $item->car->id) }}"
                                                        class="font-medium text-gray-700 hover:text-gray-800">
                                                        {{ $item->car->title }}
                                                    </a>
                                                </h4>
                                                <p class="mt-1 text-sm text-gray-500">{{ $item->car->year }} •
                                                    {{ $item->car->brand->name }}</p>
                                                <p class="mt-1 text-sm text-gray-500">Qty: {{ $item->quantity }}</p>
                                            </div>
                                        </div>

                                        <div class="flex flex-1 items-end justify-between pt-2">
                                            <p class="text-sm font-medium text-gray-900">
                                                €{{ number_format($item->car->price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <dl class="space-y-6 border-t border-gray-200 px-4 py-6 sm:px-6">
                            <div class="flex items-center justify-between">
                                <dt class="text-sm">Subtotal</dt>
                                <dd class="text-sm font-medium text-gray-900">
                                    €{{ number_format($subtotal, 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-sm">Shipping</dt>
                                <dd class="text-sm font-medium text-gray-900">
                                    €{{ number_format($shipping, 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-sm">Tax</dt>
                                <dd class="text-sm font-medium text-gray-900">€{{ number_format($tax, 0, ',', '.') }}
                                </dd>
                            </div>
                            <div class="flex items-center justify-between border-t border-gray-200 pt-6">
                                <dt class="text-base font-medium">Total</dt>
                                <dd class="text-base font-medium text-gray-900">
                                    €{{ number_format($total, 0, ',', '.') }}</dd>
                            </div>
                        </dl>

                        <div class="border-t border-gray-200 px-4 py-6 sm:px-6">
                            <button type="submit" {{ $addresses->count() === 0 ? 'disabled' : '' }}
                                class="w-full rounded-md border border-transparent bg-indigo-600 px-4 py-3 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-50 disabled:bg-gray-300 disabled:cursor-not-allowed">
                                Confirm order
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Stripe.js -->
    <script src="https://js.stripe.com/v3/"></script>
    
    <script>
        // Toggle billing address section
        document.getElementById('same-as-shipping').addEventListener('change', function() {
            const billingSection = document.getElementById('billing-address-section');
            if (this.checked) {
                billingSection.classList.add('hidden');
            } else {
                billingSection.classList.remove('hidden');
            }
        });

        // Update payment gateway UI
        function updatePaymentGateway() {
            const selectedGateway = document.querySelector('input[name="payment_gateway"]:checked').value;
            const stripeDetails = document.getElementById('stripe-details');
            const mollieDetails = document.getElementById('mollie-details');
            const paypalDetails = document.getElementById('paypal-details');

            // Hide all payment details
            stripeDetails.classList.add('hidden');
            mollieDetails.classList.add('hidden');
            paypalDetails.classList.add('hidden');

            // Show selected gateway details
            if (selectedGateway === 'stripe') {
                stripeDetails.classList.remove('hidden');
            } else if (selectedGateway === 'mollie') {
                mollieDetails.classList.remove('hidden');
            } else if (selectedGateway === 'paypal') {
                paypalDetails.classList.remove('hidden');
            }

            // Update visual selection
            document.querySelectorAll('input[name="payment_gateway"]').forEach(radio => {
                const label = radio.closest('label');
                const checkIcon = label.querySelector('svg');
                
                if (radio.checked) {
                    label.classList.remove('border-gray-300');
                    label.classList.add('border-indigo-600', 'ring-2', 'ring-indigo-600');
                    checkIcon.classList.remove('hidden');
                } else {
                    label.classList.remove('border-indigo-600', 'ring-2', 'ring-indigo-600');
                    label.classList.add('border-gray-300');
                    checkIcon.classList.add('hidden');
                }
            });
        }

        // Initialize Stripe
        const stripe = Stripe('{{ config('payment.stripe.public_key') }}');
        const elements = stripe.elements();
        const cardElement = elements.create('card', {
            style: {
                base: {
                    fontSize: '16px',
                    color: '#1f2937',
                    fontFamily: 'system-ui, -apple-system, sans-serif',
                    '::placeholder': {
                        color: '#9ca3af',
                    },
                },
                invalid: {
                    color: '#dc2626',
                },
            },
        });

        cardElement.mount('#card-element');

        // Handle real-time validation errors
        cardElement.on('change', function(event) {
            const displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        // Handle form submission
        const form = document.querySelector('form');
        const submitButton = form.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.textContent;

        form.addEventListener('submit', async function(event) {
            event.preventDefault();

            const selectedGateway = document.querySelector('input[name="payment_gateway"]:checked').value;
            
            // Disable submit button
            submitButton.disabled = true;
            submitButton.textContent = 'Processing...';

            try {
                if (selectedGateway === 'stripe') {
                    await handleStripePayment();
                } else if (selectedGateway === 'mollie') {
                    await handleMolliePayment();
                } else if (selectedGateway === 'paypal') {
                    await handlePayPalPayment();
                }
            } catch (error) {
                console.error('Payment error:', error);
                showError(error.message);
                submitButton.disabled = false;
                submitButton.textContent = originalButtonText;
            }
        });

        async function handleStripePayment() {
            // Create payment intent
            const response = await fetch('{{ route('payment.intent') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    amount: {{ $total }}
                })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Failed to create payment intent');
            }

            // Confirm payment with Stripe
            const {error, paymentIntent} = await stripe.confirmCardPayment(data.clientSecret, {
                payment_method: {
                    card: cardElement,
                }
            });

            if (error) {
                throw new Error(error.message);
            }

            if (paymentIntent.status === 'succeeded') {
                document.getElementById('payment-intent-secret').value = paymentIntent.id;
                
                // Confirm payment on backend
                const confirmResponse = await fetch('{{ route('payment.confirm') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        payment_intent_id: paymentIntent.id
                    })
                });

                if (!confirmResponse.ok) {
                    throw new Error('Failed to confirm payment on server');
                }

                // Submit the checkout form
                form.submit();
            }
        }

        async function handleMolliePayment() {
            // First, create the order
            const formData = new FormData(form);
            const orderResponse = await fetch('{{ route('checkout.store') }}', {
                method: 'POST',
                body: formData
            });

            if (!orderResponse.ok) {
                throw new Error('Failed to create order');
            }

            const orderData = await orderResponse.json();
            const orderId = orderData.order_id;

            // Create Mollie payment
            const mollieMethod = document.querySelector('select[name="mollie_method"]').value;
            const mollieResponse = await fetch('{{ route('payment.mollie.create') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    order_id: orderId,
                    method: mollieMethod || null
                })
            });

            const mollieData = await mollieResponse.json();

            if (!mollieResponse.ok) {
                throw new Error(mollieData.error || 'Failed to create Mollie payment');
            }

            // Redirect to Mollie checkout
            window.location.href = mollieData.checkoutUrl;
        }

        async function handlePayPalPayment() {
            // First, create the order
            const formData = new FormData(form);
            const orderResponse = await fetch('{{ route('checkout.store') }}', {
                method: 'POST',
                body: formData
            });

            if (!orderResponse.ok) {
                throw new Error('Failed to create order');
            }

            const orderData = await orderResponse.json();
            const orderId = orderData.order_id;

            // Create PayPal order
            const paypalResponse = await fetch('{{ route('payment.paypal.create') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    order_id: orderId
                })
            });

            const paypalData = await paypalResponse.json();

            if (!paypalResponse.ok) {
                throw new Error(paypalData.error || 'Failed to create PayPal order');
            }

            // Redirect to PayPal for approval
            window.location.href = paypalData.approvalUrl;
        }

        function showError(message) {
            const selectedGateway = document.querySelector('input[name="payment_gateway"]:checked').value;
            
            if (selectedGateway === 'stripe') {
                document.getElementById('card-errors').textContent = message;
            } else {
                alert(message); // Fallback for other gateways
            }
        }
    </script>
</x-app-layout>
