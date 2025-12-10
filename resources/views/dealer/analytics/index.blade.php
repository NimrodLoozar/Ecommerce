<x-app-layout>
    <div class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <!-- Page header -->
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Analytics Dashboard</h1>
                    <p class="mt-2 text-sm text-gray-600">Performance insights for your dealership.</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <a href="{{ route('dealer.dashboard') }}"
                        class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                        <svg class="mr-2 size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                        </svg>
                        Back to Dashboard
                    </a>
                </div>
            </div>

            <!-- Key Metrics -->
            <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Total Inquiries -->
                <div class="overflow-hidden rounded-lg bg-white shadow ring-1 ring-black/5">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="shrink-0 rounded-md bg-indigo-500 p-3">
                                <svg class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="truncate text-sm font-medium text-gray-500">Total Inquiries</dt>
                                    <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">
                                        {{ $totalInquiries }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Conversion Rate -->
                <div class="overflow-hidden rounded-lg bg-white shadow ring-1 ring-black/5">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="shrink-0 rounded-md bg-green-500 p-3">
                                <svg class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="truncate text-sm font-medium text-gray-500">Conversion Rate</dt>
                                    <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">
                                        {{ number_format($conversionRate, 1) }}%
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Test Drives -->
                <div class="overflow-hidden rounded-lg bg-white shadow ring-1 ring-black/5">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="shrink-0 rounded-md bg-blue-500 p-3">
                                <svg class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="truncate text-sm font-medium text-gray-500">Test Drives</dt>
                                    <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">
                                        {{ $testDriveStats->sum() }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-2">
                <!-- Sales by Month -->
                <div class="rounded-lg bg-white shadow ring-1 ring-black/5">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-900">Sales Trend (Last 12 Months)</h2>
                    </div>
                    <div class="p-6">
                        <canvas id="salesChart" class="w-full" height="300"></canvas>
                    </div>
                </div>

                <!-- Revenue by Category -->
                <div class="rounded-lg bg-white shadow ring-1 ring-black/5">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-900">Revenue by Category</h2>
                    </div>
                    <div class="p-6">
                        <canvas id="categoryChart" class="w-full" height="300"></canvas>
                    </div>
                </div>
            </div>

            <!-- Top Performing Cars -->
            <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-2">
                <!-- Top Selling Cars -->
                <div class="rounded-lg bg-white shadow ring-1 ring-black/5">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-900">Top Selling Cars</h2>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @forelse($topCars as $car)
                            <div class="px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $car->year }} {{ $car->brand->name }} {{ $car->carModel->name }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ $car->order_items_count }}
                                            {{ Str::plural('sale', $car->order_items_count) }}
                                        </p>
                                    </div>
                                    <div class="ml-4 flex-shrink-0">
                                        <span
                                            class="inline-flex items-center rounded-full bg-indigo-100 px-2.5 py-0.5 text-xs font-medium text-indigo-800">
                                            #{{ $loop->iteration }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center">
                                <p class="text-sm text-gray-500">No sales data available yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Most Viewed Cars -->
                <div class="rounded-lg bg-white shadow ring-1 ring-black/5">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-900">Most Viewed Cars</h2>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @forelse($mostViewedCars as $car)
                            <div class="px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $car->year }} {{ $car->brand->name }} {{ $car->carModel->name }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ number_format($car->views_count) }}
                                            {{ Str::plural('view', $car->views_count) }}
                                        </p>
                                    </div>
                                    <div class="ml-4 flex-shrink-0">
                                        <span
                                            class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                            #{{ $loop->iteration }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center">
                                <p class="text-sm text-gray-500">No view data available yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Test Drive Status -->
            @if ($testDriveStats->isNotEmpty())
                <div class="mt-8">
                    <div class="rounded-lg bg-white shadow ring-1 ring-black/5">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-900">Test Drive Status</h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                                @foreach (['pending' => 'yellow', 'confirmed' => 'blue', 'completed' => 'green', 'cancelled' => 'red'] as $status => $color)
                                    @php
                                        $count = $testDriveStats->get($status, 0);
                                        $colorClasses = [
                                            'yellow' => 'bg-yellow-100 text-yellow-800',
                                            'blue' => 'bg-blue-100 text-blue-800',
                                            'green' => 'bg-green-100 text-green-800',
                                            'red' => 'bg-red-100 text-red-800',
                                        ];
                                    @endphp
                                    <div class="rounded-lg {{ $colorClasses[$color] }} p-4">
                                        <dt class="text-sm font-medium">{{ ucfirst($status) }}</dt>
                                        <dd class="mt-1 text-2xl font-semibold">{{ $count }}</dd>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            // Sales by Month Chart
            const salesCtx = document.getElementById('salesChart');
            if (salesCtx) {
                new Chart(salesCtx, {
                    type: 'line',
                    data: {
                        labels: @json($salesByMonth->pluck('month')),
                        datasets: [{
                            label: 'Revenue (€)',
                            data: @json($salesByMonth->pluck('revenue')),
                            borderColor: 'rgb(79, 70, 229)',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return '€' + context.parsed.y.toLocaleString('en-US', {
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2
                                        });
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '€' + value.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Revenue by Category Chart
            const categoryCtx = document.getElementById('categoryChart');
            if (categoryCtx) {
                new Chart(categoryCtx, {
                    type: 'doughnut',
                    data: {
                        labels: @json($revenueByCategory->pluck('name')),
                        datasets: [{
                            data: @json($revenueByCategory->pluck('revenue')),
                            backgroundColor: [
                                'rgb(79, 70, 229)',
                                'rgb(59, 130, 246)',
                                'rgb(16, 185, 129)',
                                'rgb(245, 158, 11)',
                                'rgb(239, 68, 68)',
                                'rgb(139, 92, 246)',
                                'rgb(236, 72, 153)',
                                'rgb(14, 165, 233)'
                            ],
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.parsed || 0;
                                        return label + ': €' + value.toLocaleString('en-US', {
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2
                                        });
                                    }
                                }
                            }
                        }
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
