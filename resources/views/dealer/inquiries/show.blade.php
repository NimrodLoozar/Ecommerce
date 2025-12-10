<x-app-layout>
    <div class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <!-- Back button -->
            <div class="mb-8">
                <a href="{{ route('dealer.inquiries.index') }}"
                    class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                    <svg class="mr-2 size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M17 10a.75.75 0 0 1-.75.75H5.612l4.158 3.96a.75.75 0 1 1-1.04 1.08l-5.5-5.25a.75.75 0 0 1 0-1.08l5.5-5.25a.75.75 0 1 1 1.04 1.08L5.612 9.25H16.25A.75.75 0 0 1 17 10Z"
                            clip-rule="evenodd" />
                    </svg>
                    Back to Inquiries
                </a>
            </div>

            <!-- Page header -->
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Inquiry Details</h1>
                    <p class="mt-2 text-sm text-gray-600">
                        Received on {{ $inquiry->created_at->format('F d, Y \a\t g:i A') }}
                    </p>
                </div>
                @php
                    $statusColors = [
                        'new' => 'bg-blue-50 text-blue-800 ring-blue-600/20',
                        'in_progress' => 'bg-yellow-50 text-yellow-800 ring-yellow-600/20',
                        'resolved' => 'bg-green-50 text-green-800 ring-green-600/20',
                        'converted' => 'bg-purple-50 text-purple-800 ring-purple-600/20',
                    ];
                    $colorClass = $statusColors[$inquiry->status] ?? 'bg-gray-50 text-gray-800 ring-gray-600/20';
                @endphp
                <div class="mt-4 sm:mt-0">
                    <span
                        class="inline-flex items-center rounded-md px-3 py-2 text-sm font-medium ring-1 ring-inset {{ $colorClass }}">
                        {{ ucfirst(str_replace('_', ' ', $inquiry->status)) }}
                    </span>
                </div>
            </div>

            @if (session('success'))
                <div class="mt-6 rounded-md bg-green-50 p-4">
                    <div class="flex">
                        <div class="shrink-0">
                            <svg class="size-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-3">
                <!-- Main content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Inquiry Content -->
                    <div class="rounded-lg border border-gray-200 bg-white">
                        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                            <h2 class="text-base font-semibold text-gray-900">{{ $inquiry->subject }}</h2>
                        </div>
                        <div class="px-6 py-4">
                            <p class="text-sm text-gray-900 whitespace-pre-line">{{ $inquiry->message }}</p>
                        </div>
                    </div>

                    <!-- Response -->
                    @if ($inquiry->response)
                        <div class="rounded-lg border border-gray-200 bg-green-50">
                            <div class="border-b border-green-200 bg-green-100 px-6 py-4">
                                <h2 class="text-base font-semibold text-gray-900">Your Response</h2>
                            </div>
                            <div class="px-6 py-4">
                                <p class="text-sm text-gray-900 whitespace-pre-line">{{ $inquiry->response }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Response Form -->
                    <div class="rounded-lg border border-gray-200 bg-white">
                        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                            <h2 class="text-base font-semibold text-gray-900">
                                {{ $inquiry->response ? 'Update Response' : 'Respond to Inquiry' }}
                            </h2>
                        </div>
                        <div class="px-6 py-4">
                            <form action="{{ route('dealer.inquiries.update', $inquiry) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <div class="space-y-4">
                                    <!-- Status -->
                                    <div>
                                        <label for="status" class="block text-sm font-medium text-gray-900">Update
                                            Status</label>
                                        <select name="status" id="status" required
                                            class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                            <option value="new" {{ $inquiry->status == 'new' ? 'selected' : '' }}>
                                                New
                                            </option>
                                            <option value="in_progress"
                                                {{ $inquiry->status == 'in_progress' ? 'selected' : '' }}>In Progress
                                            </option>
                                            <option value="resolved"
                                                {{ $inquiry->status == 'resolved' ? 'selected' : '' }}>Resolved
                                            </option>
                                            <option value="converted"
                                                {{ $inquiry->status == 'converted' ? 'selected' : '' }}>Converted to
                                                Sale
                                            </option>
                                        </select>
                                        @error('status')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Response Message -->
                                    <div>
                                        <label for="response" class="block text-sm font-medium text-gray-900">Response
                                            Message</label>
                                        <textarea name="response" id="response" rows="6"
                                            class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"
                                            placeholder="Write your response to the customer...">{{ old('response', $inquiry->response) }}</textarea>
                                        @error('response')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <p class="mt-1 text-xs text-gray-500">Your response will be sent to the customer
                                            via email.</p>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="flex justify-end">
                                        <button type="submit"
                                            class="rounded-md bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                            {{ $inquiry->response ? 'Update Response' : 'Send Response' }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Customer Information -->
                    <div class="rounded-lg border border-gray-200 bg-white">
                        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                            <h2 class="text-base font-semibold text-gray-900">Customer Information</h2>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $inquiry->user->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <a href="mailto:{{ $inquiry->user->email }}"
                                            class="text-indigo-600 hover:text-indigo-900">
                                            {{ $inquiry->user->email }}
                                        </a>
                                    </dd>
                                </div>
                                @if ($inquiry->user->phone)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            <a href="tel:{{ $inquiry->user->phone }}"
                                                class="text-indigo-600 hover:text-indigo-900">
                                                {{ $inquiry->user->phone }}
                                            </a>
                                        </dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Vehicle Information -->
                    <div class="rounded-lg border border-gray-200 bg-white">
                        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                            <h2 class="text-base font-semibold text-gray-900">Vehicle</h2>
                        </div>
                        <div class="px-6 py-4">
                            @if ($inquiry->car->images->first())
                                <img src="{{ Storage::url($inquiry->car->images->first()->image_path) }}"
                                    alt="{{ $inquiry->car->brand->name }} {{ $inquiry->car->carModel->name }}"
                                    class="mb-4 w-full rounded-lg">
                            @endif
                            <h3 class="text-sm font-medium text-gray-900">
                                {{ $inquiry->car->year }} {{ $inquiry->car->brand->name }}
                                {{ $inquiry->car->carModel->name }}
                            </h3>
                            <p class="mt-2 text-sm text-gray-500">
                                {{ $inquiry->car->exterior_color }} • {{ number_format($inquiry->car->mileage) }} km
                            </p>
                            <p class="mt-2 text-lg font-semibold text-gray-900">
                                €{{ number_format($inquiry->car->price, 2) }}
                            </p>
                            <a href="{{ route('dealer.cars.show', $inquiry->car) }}"
                                class="mt-4 block w-full rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                View Vehicle
                            </a>
                        </div>
                    </div>

                    <!-- Inquiry Timeline -->
                    <div class="rounded-lg border border-gray-200 bg-white">
                        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                            <h2 class="text-base font-semibold text-gray-900">Timeline</h2>
                        </div>
                        <div class="px-6 py-4">
                            <ul class="space-y-4">
                                <li class="flex gap-3">
                                    <div class="shrink-0">
                                        <div
                                            class="flex size-8 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                                            <svg class="size-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">Inquiry Created</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $inquiry->created_at->format('M d, Y g:i A') }}
                                        </p>
                                    </div>
                                </li>
                                @if ($inquiry->updated_at != $inquiry->created_at)
                                    <li class="flex gap-3">
                                        <div class="shrink-0">
                                            <div
                                                class="flex size-8 items-center justify-center rounded-full bg-yellow-100 text-yellow-600">
                                                <svg class="size-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900">Last Updated</p>
                                            <p class="text-xs text-gray-500">
                                                {{ $inquiry->updated_at->format('M d, Y g:i A') }}
                                            </p>
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
