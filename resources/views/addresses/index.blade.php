<x-app-layout>
    <div class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Addresses</h1>
                    <p class="mt-2 text-sm text-gray-700">Manage your shipping and billing addresses</p>
                </div>
                <div class="mt-4 sm:ml-16 sm:mt-0">
                    <a href="{{ route('addresses.create') }}"
                        class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        <svg class="-ml-0.5 mr-1.5 size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path
                                d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                        </svg>
                        Add address
                    </a>
                </div>
            </div>

            @if ($addresses->count() > 0)
                <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($addresses as $address)
                        <div
                            class="relative flex flex-col rounded-lg border border-gray-300 bg-white p-6 shadow-sm hover:border-gray-400">
                            <div class="flex-1">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="text-base font-medium text-gray-900">
                                            {{ $address->first_name }} {{ $address->last_name }}
                                        </h3>
                                        @if ($address->company)
                                            <p class="mt-1 text-sm text-gray-500">{{ $address->company }}</p>
                                        @endif
                                    </div>
                                    @if ($address->is_default)
                                        <span
                                            class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                            Default
                                        </span>
                                    @endif
                                </div>

                                <div class="mt-4 text-sm text-gray-500">
                                    <p>{{ $address->address_line1 }}</p>
                                    @if ($address->address_line2)
                                        <p>{{ $address->address_line2 }}</p>
                                    @endif
                                    <p>{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
                                    <p>{{ $address->country }}</p>
                                </div>

                                @if ($address->phone)
                                    <p class="mt-4 text-sm text-gray-500">
                                        <svg class="mr-1.5 inline size-4 text-gray-400" viewBox="0 0 20 20"
                                            fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M2 3.5A1.5 1.5 0 0 1 3.5 2h1.148a1.5 1.5 0 0 1 1.465 1.175l.716 3.223a1.5 1.5 0 0 1-1.052 1.767l-.933.267c-.41.117-.643.555-.48.95a11.542 11.542 0 0 0 6.254 6.254c.395.163.833-.07.95-.48l.267-.933a1.5 1.5 0 0 1 1.767-1.052l3.223.716A1.5 1.5 0 0 1 18 15.352V16.5a1.5 1.5 0 0 1-1.5 1.5H15c-1.149 0-2.263-.15-3.326-.43A13.022 13.022 0 0 1 2.43 8.326 13.019 13.019 0 0 1 2 5V3.5Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $address->phone }}
                                    </p>
                                @endif
                            </div>

                            <div class="mt-6 flex space-x-3">
                                <a href="{{ route('addresses.edit', $address) }}"
                                    class="flex-1 rounded-md bg-white px-3 py-2 text-center text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                    Edit
                                </a>
                                <form action="{{ route('addresses.destroy', $address) }}" method="POST" class="flex-1"
                                    onsubmit="return confirm('Are you sure you want to delete this address?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full rounded-md bg-white px-3 py-2 text-sm font-semibold text-red-600 shadow-sm ring-1 ring-inset ring-red-300 hover:bg-red-50">
                                        Delete
                                    </button>
                                </form>
                            </div>

                            @if (!$address->is_default)
                                <form action="{{ route('addresses.set-default', $address) }}" method="POST"
                                    class="mt-3">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="w-full text-center text-sm text-indigo-600 hover:text-indigo-500">
                                        Set as default
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="mt-12 text-center">
                    <svg class="mx-auto size-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                    </svg>
                    <h3 class="mt-6 text-2xl font-semibold text-gray-900">No addresses</h3>
                    <p class="mt-2 text-base text-gray-500">Get started by creating a new address for shipping and
                        billing.</p>
                    <div class="mt-8">
                        <a href="{{ route('addresses.create') }}"
                            class="inline-flex items-center rounded-md bg-indigo-600 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            <svg class="-ml-1 mr-2 size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path
                                    d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                            </svg>
                            Add your first address
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
