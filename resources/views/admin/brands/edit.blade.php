<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Brand') }}: {{ $brand->name }}
            </h2>
            <a href="{{ route('admin.brands.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-md transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Brands
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.brands.update', $brand) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <!-- Brand Name -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Brand Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $brand->name) }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                                required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div class="mb-6">
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                                Slug (URL-friendly name)
                            </label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug', $brand->slug) }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('slug') border-red-500 @enderror"
                                placeholder="Leave empty to auto-generate">
                            @error('slug')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">If left empty, will be auto-generated from the brand
                                name.</p>
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea name="description" id="description" rows="4"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                                placeholder="Enter brand description...">{{ old('description', $brand->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Logo -->
                        @if ($brand->logo)
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Current Logo
                                </label>
                                <div class="flex items-center gap-4">
                                    <img src="{{ Storage::url($brand->logo) }}" alt="{{ $brand->name }}"
                                        class="w-24 h-24 object-contain rounded border border-gray-200 p-2">
                                    <p class="text-sm text-gray-500">Upload a new image to replace the current logo.</p>
                                </div>
                            </div>
                        @endif

                        <!-- Logo Upload -->
                        <div class="mb-6">
                            <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $brand->logo ? 'Update Brand Logo' : 'Brand Logo' }}
                            </label>
                            <input type="file" name="logo" id="logo" accept="image/*"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('logo') border-red-500 @enderror">
                            @error('logo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Maximum file size: 2MB. Recommended: Square image
                                (e.g., 200x200px).</p>
                        </div>

                        <!-- Statistics -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Brand Statistics</h3>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600">Total Cars:</span>
                                    <span class="font-semibold text-gray-900">{{ $brand->cars()->count() }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Created:</span>
                                    <span
                                        class="font-semibold text-gray-900">{{ $brand->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.brands.index') }}"
                                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-md transition">
                                Cancel
                            </a>
                            <button type="submit"
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition">
                                Update Brand
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Auto-generate slug from name (only if manually triggered)
            const nameInput = document.getElementById('name');
            const slugInput = document.getElementById('slug');
            const originalSlug = slugInput.value;

            nameInput.addEventListener('input', function(e) {
                if (!slugInput.value || slugInput.dataset.autoGenerated === 'true') {
                    const slug = e.target.value
                        .toLowerCase()
                        .replace(/[^\w\s-]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-')
                        .trim();
                    slugInput.value = slug;
                    slugInput.dataset.autoGenerated = 'true';
                }
            });

            slugInput.addEventListener('input', function() {
                if (this.value !== originalSlug) {
                    this.dataset.autoGenerated = 'false';
                }
            });
        </script>
    @endpush
</x-app-layout>
