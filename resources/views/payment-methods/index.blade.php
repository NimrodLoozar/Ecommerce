<x-app-layout>
    <div class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-3xl">
                <!-- Header -->
                <div class="pb-5 border-b border-gray-200 mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Payment Methods</h1>
                    <p class="mt-2 text-sm text-gray-600">Manage your saved payment methods for faster checkout</p>
                </div>

                @if(session('success'))
                    <div class="mb-6 rounded-md bg-green-50 p-4">
                        <div class="flex">
                            <div class="shrink-0">
                                <svg class="size-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 rounded-md bg-red-50 p-4">
                        <div class="flex">
                            <div class="shrink-0">
                                <svg class="size-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Add Payment Method Card -->
                <div class="mb-8 rounded-lg border-2 border-dashed border-gray-300 p-8">
                    <div class="text-center">
                        <svg class="mx-auto size-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Add a payment method</h3>
                        <p class="mt-1 text-sm text-gray-500">Save a card for faster checkout</p>
                        <div class="mt-6">
                            <button type="button" id="add-payment-method-btn"
                                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                <svg class="-ml-0.5 mr-1.5 size-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                </svg>
                                Add Payment Method
                            </button>
                        </div>
                    </div>

                    <!-- Add Payment Method Form (hidden initially) -->
                    <div id="add-payment-form" class="mt-8 hidden">
                        <div class="border-t border-gray-200 pt-6">
                            <h4 class="text-base font-medium text-gray-900 mb-4">Enter card details</h4>
                            
                            <!-- Stripe Card Element -->
                            <div id="card-element" class="rounded-md border border-gray-300 p-3 bg-white mb-4"></div>
                            <div id="card-errors" role="alert" class="text-sm text-red-600 mb-4"></div>

                            <div class="flex items-center mb-4">
                                <input type="checkbox" id="set-as-default" 
                                    class="size-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <label for="set-as-default" class="ml-2 block text-sm text-gray-700">
                                    Set as default payment method
                                </label>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <button type="button" id="cancel-add-btn"
                                    class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                    Cancel
                                </button>
                                <button type="button" id="save-payment-method-btn"
                                    class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                    Save Payment Method
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Saved Payment Methods List -->
                @if($paymentMethods->count() > 0)
                    <div class="space-y-4">
                        <h2 class="text-lg font-medium text-gray-900">Saved Payment Methods</h2>
                        
                        @foreach($paymentMethods as $method)
                            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <!-- Card Icon -->
                                        <div class="shrink-0">
                                            @if(strtolower($method->brand) === 'visa')
                                                <svg class="size-12 text-blue-600" viewBox="0 0 48 32" fill="currentColor">
                                                    <rect width="48" height="32" rx="4" fill="#1434CB"/>
                                                    <text x="24" y="20" text-anchor="middle" fill="white" font-family="Arial" font-size="14" font-weight="bold">VISA</text>
                                                </svg>
                                            @elseif(strtolower($method->brand) === 'mastercard')
                                                <svg class="size-12" viewBox="0 0 48 32">
                                                    <rect width="48" height="32" rx="4" fill="#EB001B"/>
                                                    <text x="24" y="20" text-anchor="middle" fill="white" font-family="Arial" font-size="10" font-weight="bold">MC</text>
                                                </svg>
                                            @else
                                                <svg class="size-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                </svg>
                                            @endif
                                        </div>

                                        <!-- Card Details -->
                                        <div>
                                            <div class="flex items-center space-x-2">
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ ucfirst($method->brand) }} •••• {{ $method->last_four }}
                                                </p>
                                                @if($method->is_default)
                                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                                        Default
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="mt-1 text-sm text-gray-500">
                                                Expires {{ str_pad($method->exp_month, 2, '0', STR_PAD_LEFT) }}/{{ $method->exp_year }}
                                            </p>
                                            @if($method->cardholder_name)
                                                <p class="text-sm text-gray-500">{{ $method->cardholder_name }}</p>
                                            @endif
                                            @if($method->isExpired())
                                                <p class="mt-1 text-sm text-red-600 font-medium">⚠️ Expired</p>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex items-center space-x-2">
                                        @if(!$method->is_default)
                                            <form action="{{ route('payment-methods.set-default', $method) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                    class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                                    Set as Default
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('payment-methods.destroy', $method) }}" method="POST" 
                                            class="inline" onsubmit="return confirm('Are you sure you want to delete this payment method?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                class="text-sm font-medium text-red-600 hover:text-red-500">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto size-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No payment methods</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by adding your first payment method.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Stripe.js -->
    <script src="https://js.stripe.com/v3/"></script>

    <script>
        // Initialize Stripe
        const stripe = Stripe('{{ config('payment.stripe.public_key') }}');
        const elements = stripe.elements();
        const cardElement = elements.create('card');
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

        // Show/hide add payment form
        const addBtn = document.getElementById('add-payment-method-btn');
        const cancelBtn = document.getElementById('cancel-add-btn');
        const addForm = document.getElementById('add-payment-form');

        addBtn.addEventListener('click', () => {
            addForm.classList.remove('hidden');
            addBtn.classList.add('hidden');
        });

        cancelBtn.addEventListener('click', () => {
            addForm.classList.add('hidden');
            addBtn.classList.remove('hidden');
            cardElement.clear();
            document.getElementById('card-errors').textContent = '';
        });

        // Save payment method
        const saveBtn = document.getElementById('save-payment-method-btn');
        saveBtn.addEventListener('click', async () => {
            saveBtn.disabled = true;
            saveBtn.textContent = 'Saving...';

            try {
                // Create payment method
                const {error, paymentMethod} = await stripe.createPaymentMethod({
                    type: 'card',
                    card: cardElement,
                });

                if (error) {
                    document.getElementById('card-errors').textContent = error.message;
                    saveBtn.disabled = false;
                    saveBtn.textContent = 'Save Payment Method';
                    return;
                }

                // Save to backend
                const response = await fetch('{{ route('payment-methods.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        payment_method_id: paymentMethod.id,
                        set_as_default: document.getElementById('set-as-default').checked
                    })
                });

                if (response.ok) {
                    // Reload page to show new payment method
                    window.location.reload();
                } else {
                    const data = await response.json();
                    document.getElementById('card-errors').textContent = data.message;
                    saveBtn.disabled = false;
                    saveBtn.textContent = 'Save Payment Method';
                }
            } catch (error) {
                document.getElementById('card-errors').textContent = 'An error occurred. Please try again.';
                saveBtn.disabled = false;
                saveBtn.textContent = 'Save Payment Method';
            }
        });
    </script>
</x-app-layout>
