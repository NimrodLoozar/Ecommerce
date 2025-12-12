<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dealer Details') }}
            </h2>
            <a href="{{ route('admin.dealers.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-md transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Dealers
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Dealer Profile -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-6">Dealer Profile</h3>

                            <div class="flex items-center mb-6">
                                <div class="flex-shrink-0 h-20 w-20">
                                    <div class="h-20 w-20 rounded-full bg-purple-100 flex items-center justify-center">
                                        <span class="text-purple-600 font-medium text-2xl">
                                            {{ strtoupper(substr($dealer->company_name, 0, 2)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-6">
                                    <h4 class="text-2xl font-bold text-gray-900">{{ $dealer->company_name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $dealer->user->name }} ({{ $dealer->user->email }})</p>
                                </div>
                            </div>

                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $dealer->phone ?? 'Not provided' }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1">
                                        @if ($dealer->status === 'approved')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Approved
                                            </span>
                                        @elseif ($dealer->status === 'pending')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        @elseif ($dealer->status === 'rejected')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Rejected
                                            </span>
                                        @else
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Suspended
                                            </span>
                                        @endif
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Subscription Plan</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($dealer->subscription_plan) }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Commission Rate</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $dealer->commission_rate }}%</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $dealer->created_at->format('F d, Y') }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $dealer->updated_at->format('F d, Y') }}</dd>
                                </div>

                                @if ($dealer->address)
                                    <div class="sm:col-span-2">
                                        <dt class="text-sm font-medium text-gray-500">Address</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $dealer->address }}</dd>
                                    </div>
                                @endif

                                @if ($dealer->description)
                                    <div class="sm:col-span-2">
                                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $dealer->description }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Edit Dealer Form -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Update Dealer Settings</h3>
                            <form action="{{ route('admin.dealers.update', $dealer) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <!-- Status -->
                                <div class="mb-6">
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                        Status <span class="text-red-500">*</span>
                                    </label>
                                    <select name="status" id="status"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('status') border-red-500 @enderror"
                                        required>
                                        <option value="pending" {{ $dealer->status === 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="approved" {{ $dealer->status === 'approved' ? 'selected' : '' }}>
                                            Approved</option>
                                        <option value="rejected" {{ $dealer->status === 'rejected' ? 'selected' : '' }}>
                                            Rejected</option>
                                        <option value="suspended" {{ $dealer->status === 'suspended' ? 'selected' : '' }}>
                                            Suspended</option>
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Commission Rate -->
                                <div class="mb-6">
                                    <label for="commission_rate" class="block text-sm font-medium text-gray-700 mb-2">
                                        Commission Rate (%)
                                    </label>
                                    <input type="number" name="commission_rate" id="commission_rate"
                                        value="{{ old('commission_rate', $dealer->commission_rate) }}" step="0.01"
                                        min="0" max="100"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('commission_rate') border-red-500 @enderror">
                                    @error('commission_rate')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">The percentage commission charged on sales (0-100)
                                    </p>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Update Dealer
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Inventory -->
                    @if ($dealer->cars->count() > 0)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Inventory ({{ $dealer->cars->count() }}
                                    cars)</h3>
                                <div class="space-y-3">
                                    @foreach ($dealer->cars->take(10) as $car)
                                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                            <div class="flex-1">
                                                <a href="{{ route('cars.show', $car) }}"
                                                    class="text-sm font-medium text-blue-600 hover:text-blue-700">
                                                    {{ $car->title }}
                                                </a>
                                                <p class="text-xs text-gray-500">
                                                    {{ $car->condition->name ?? 'N/A' }} • {{ $car->year }} •
                                                    {{ number_format($car->mileage) }} km
                                                </p>
                                            </div>
                                            <span class="text-sm font-semibold text-gray-900">
                                                €{{ number_format($car->price, 2) }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                                @if ($dealer->cars->count() > 10)
                                    <div class="mt-4">
                                        <a href="{{ route('dealer.cars.index') }}"
                                            class="text-sm text-blue-600 hover:text-blue-700">
                                            View all {{ $dealer->cars->count() }} cars →
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Statistics -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Statistics</h3>
                            <dl class="space-y-4">
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-600">Total Cars</dt>
                                    <dd class="text-sm font-semibold text-gray-900">{{ $dealer->cars->count() }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-600">Active Listings</dt>
                                    <dd class="text-sm font-semibold text-gray-900">
                                        {{ $dealer->cars->where('status', 'available')->count() }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-600">Total Commissions</dt>
                                    <dd class="text-sm font-semibold text-gray-900">
                                        €{{ number_format($dealer->commissions->sum('amount'), 2) }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- User Account -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">User Account</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm text-gray-600">Name</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $dealer->user->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-600">Email</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $dealer->user->email }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-600">Role</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ ucfirst($dealer->user->role) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-600">Status</dt>
                                    <dd class="text-sm font-medium text-gray-900">
                                        {{ $dealer->user->is_active ? 'Active' : 'Inactive' }}
                                    </dd>
                                </div>
                            </dl>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <a href="{{ route('admin.users.show', $dealer->user) }}"
                                    class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                    View User Profile →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
