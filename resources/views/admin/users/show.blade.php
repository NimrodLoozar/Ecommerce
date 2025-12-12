<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('User Details') }}
            </h2>
            <a href="{{ route('admin.users.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-md transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Users
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
                    <!-- User Information -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-medium text-gray-900">User Information</h3>
                                @if ($user->id !== auth()->id())
                                    <button type="button" onclick="document.getElementById('editForm').scrollIntoView({behavior: 'smooth'})"
                                        class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit User
                                    </button>
                                @endif
                            </div>

                            <div class="flex items-center mb-6">
                                <div class="flex-shrink-0 h-20 w-20">
                                    <div class="h-20 w-20 rounded-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-600 font-medium text-2xl">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-6">
                                    <h4 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $user->email }}</p>
                                </div>
                            </div>

                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $user->phone ?? 'Not provided' }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Role</dt>
                                    <dd class="mt-1">
                                        @if ($user->role === 'admin')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Admin
                                            </span>
                                        @elseif ($user->role === 'dealer')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                                Dealer
                                            </span>
                                        @else
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Customer
                                            </span>
                                        @endif
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Account Status</dt>
                                    <dd class="mt-1">
                                        @if ($user->is_active)
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        @else
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Inactive
                                            </span>
                                        @endif
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('F d, Y') }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Last Login</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $user->last_login_at ? $user->last_login_at->format('F d, Y H:i') : 'Never' }}
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email Verified</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $user->email_verified_at ? $user->email_verified_at->format('F d, Y') : 'Not verified' }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Dealer Profile (if applicable) -->
                    @if ($user->dealerProfile)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Dealer Profile</h3>
                                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Company Name</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $user->dealerProfile->company_name }}
                                        </dd>
                                    </div>

                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                                        <dd class="mt-1">
                                            @if ($user->dealerProfile->status === 'approved')
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Approved
                                                </span>
                                            @elseif ($user->dealerProfile->status === 'pending')
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Pending
                                                </span>
                                            @elseif ($user->dealerProfile->status === 'rejected')
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
                                        <dt class="text-sm font-medium text-gray-500">Commission Rate</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ $user->dealerProfile->commission_rate }}%</dd>
                                    </div>

                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Subscription Plan</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ ucfirst($user->dealerProfile->subscription_plan) }}</dd>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <a href="{{ route('admin.dealers.show', $user->dealerProfile) }}"
                                            class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                            View Full Dealer Profile →
                                        </a>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    @endif

                    <!-- Edit Form -->
                    @if ($user->id !== auth()->id())
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" id="editForm">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Edit User</h3>
                                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <!-- Role -->
                                    <div class="mb-6">
                                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                                            Role <span class="text-red-500">*</span>
                                        </label>
                                        <select name="role" id="role"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('role') border-red-500 @enderror"
                                            required>
                                            <option value="customer" {{ $user->role === 'customer' ? 'selected' : '' }}>
                                                Customer</option>
                                            <option value="dealer" {{ $user->role === 'dealer' ? 'selected' : '' }}>
                                                Dealer</option>
                                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin
                                            </option>
                                        </select>
                                        @error('role')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Status -->
                                    <div class="mb-6">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="is_active" value="1"
                                                {{ $user->is_active ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <span class="ml-2 text-sm text-gray-700">Account is active</span>
                                        </label>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                                        <button type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                            Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Activity Stats -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Activity Stats</h3>
                            <dl class="space-y-4">
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-600">Total Orders</dt>
                                    <dd class="text-sm font-semibold text-gray-900">{{ $user->orders->count() }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-600">Reviews Written</dt>
                                    <dd class="text-sm font-semibold text-gray-900">{{ $user->reviews->count() }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-600">Wishlist Items</dt>
                                    <dd class="text-sm font-semibold text-gray-900">{{ $user->wishlists->count() }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Recent Orders -->
                    @if ($user->orders->count() > 0)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Orders</h3>
                                <div class="space-y-3">
                                    @foreach ($user->orders->take(5) as $order)
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <a href="{{ route('admin.orders.show', $order) }}"
                                                    class="text-sm font-medium text-blue-600 hover:text-blue-700">
                                                    #{{ $order->order_number }}
                                                </a>
                                                <p class="text-xs text-gray-500">
                                                    {{ $order->created_at->format('M d, Y') }}
                                                </p>
                                            </div>
                                            <span class="text-sm font-semibold text-gray-900">
                                                €{{ number_format($order->total, 2) }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Danger Zone -->
                    @if ($user->id !== auth()->id())
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-2 border-red-200">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-red-900 mb-2">Danger Zone</h3>
                                <p class="text-sm text-gray-600 mb-4">Permanently delete this user account.</p>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone and will remove all associated data.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-md transition">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Delete User Account
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
