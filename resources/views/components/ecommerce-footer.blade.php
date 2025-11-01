<footer class="w-full bg-gradient-to-t from-yellow-200/60 to-transparent text-slate-700 border-t">
    <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Brand / About -->
            <div>
                <a href="{{ url('/') }}" class="inline-flex items-center space-x-2">
                    <svg class="w-8 h-8 text-blue-600" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                        aria-hidden="true">
                        <path d="M3 12h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M12 3v18" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    <span class="text-lg font-semibold">{{ config('app.name', 'Ecommerce') }}</span>
                </a>
                <p class="mt-4 text-sm text-slate-600">We curate quality products at fair prices. Fast shipping and
                    friendly support — shop with confidence.</p>

                <div class="mt-6 flex space-x-3" aria-label="Social links">
                    <a href="#" class="text-slate-500 hover:text-slate-700" aria-label="Facebook">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.99H7.898v-2.888h2.54V9.797c0-2.507 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562v1.875h2.773l-.444 2.888h-2.329v6.99C18.343 21.128 22 16.991 22 12z" />
                        </svg>
                    </a>
                    <a href="#" class="text-slate-500 hover:text-slate-700" aria-label="Twitter">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M22.162 5.656c-.63.28-1.3.47-2.005.556.72-.432 1.272-1.115 1.532-1.93-.674.4-1.422.69-2.218.847C18.9 4.31 17.98 4 16.98 4c-1.59 0-2.88 1.29-2.88 2.88 0 .225.025.444.074.654C11.01 7.39 8.09 5.94 6.056 3.78c-.247.424-.388.915-.388 1.44 0 .995.507 1.873 1.28 2.387-.47-.015-.91-.144-1.296-.357v.036c0 1.39.99 2.55 2.302 2.814-.24.064-.493.097-.753.097-.185 0-.365-.018-.54-.052.365 1.14 1.426 1.972 2.683 1.995-0.983.77-2.22 1.23-3.56 1.23-.231 0-.46-.014-.685-.04 1.268.813 2.773 1.288 4.392 1.288 5.27 0 8.157-4.365 8.157-8.157 0-.125-.002-.25-.008-.374.56-.404 1.046-.91 1.43-1.487z" />
                        </svg>
                    </a>
                    <a href="#" class="text-slate-500 hover:text-slate-700" aria-label="Instagram">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm5 6.5A4.5 4.5 0 1 0 16.5 13 4.5 4.5 0 0 0 12 8.5zm4.75-2.25a1.25 1.25 0 1 0 1.25 1.25 1.25 1.25 0 0 0-1.25-1.25z" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Navigation links -->
            <div class="md:col-span-1 grid grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-semibold text-slate-900">Shop</h3>
                    <ul class="mt-3 space-y-2 text-sm text-slate-600">
                        <li><a href="#" class="hover:text-slate-800">All Products</a></li>
                        <li><a href="#" class="hover:text-slate-800">New Arrivals</a></li>
                        <li><a href="#" class="hover:text-slate-800">Best Sellers</a></li>
                        <li><a href="#" class="hover:text-slate-800">Sale</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-slate-900">Support</h3>
                    <ul class="mt-3 space-y-2 text-sm text-slate-600">
                        <li><a href="#" class="hover:text-slate-800">Help Center</a></li>
                        <li><a href="#" class="hover:text-slate-800">Shipping & Returns</a></li>
                        <li><a href="#" class="hover:text-slate-800">Warranty</a></li>
                        <li><a href="#" class="hover:text-slate-800">Contact Us</a></li>
                    </ul>
                </div>
            </div>

            <!-- Newsletter / Contact -->
            <div>
                <h3 class="text-sm font-semibold text-slate-900">Join our newsletter</h3>
                <p class="mt-2 text-sm text-slate-600">Get updates on new products and exclusive offers.</p>

                <form action="{{ url('/newsletter/subscribe') }}" method="POST" class="mt-4 sm:flex" novalidate>
                    @csrf
                    <label for="footer-email" class="sr-only">Email address</label>
                    <input id="footer-email" name="email" type="email" required placeholder="you@domain.com"
                        class="w-full px-3 py-2 border border-slate-200 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    <button type="submit"
                        class="mt-3 w-full sm:mt-0 sm:ml-3 sm:w-auto inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Subscribe
                    </button>
                </form>

                <div class="mt-6 text-sm text-slate-600">
                    <p>Contact: <a href="mailto:info@example.com"
                            class="text-slate-800 hover:underline">info@example.com</a></p>
                    <p class="mt-1">Phone: <a href="tel:+1234567890" class="text-slate-800 hover:underline">+1 (234)
                            567-890</a></p>
                </div>
            </div>
        </div>

        <div class="mt-8 border-t pt-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-sm text-slate-500">&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights
                reserved.</p>

            <div class="flex items-center space-x-4">
                <nav class="text-sm text-slate-600 space-x-3" aria-label="Footer legal">
                    <a href="#" class="hover:text-slate-800">Terms</a>
                    <a href="#" class="hover:text-slate-800">Privacy</a>
                    <a href="#" class="hover:text-slate-800">Cookies</a>
                </nav>

                <div class="flex items-center space-x-2">
                    <!-- Placeholder payment icons -->
                    <span class="text-slate-400 text-xs">Payments:</span>
                    <svg class="w-8 h-5 text-slate-600" viewBox="0 0 36 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <rect x="0" y="0" width="36" height="24" rx="4" fill="currentColor" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
</footer>
