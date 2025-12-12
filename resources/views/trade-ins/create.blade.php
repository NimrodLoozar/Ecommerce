<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Trade-In Your Vehicle') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Info Banner -->
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
                <div class="flex">
                    <svg class="h-6 w-6 text-blue-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Get an Instant Quote</h3>
                        <p class="mt-2 text-sm text-blue-700">
                            Fill out the form below to get a quote for your vehicle. We'll review your submission and get back to you within 24 hours with an offer.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-lg">
                <form method="POST" action="{{ route('trade-ins.store') }}" enctype="multipart/form-data"
                    x-data="{
                        selectedBrand: '{{ old('brand_id') }}',
                        selectedModel: '{{ old('car_model_id') }}',
                        availableModels: [],
                        loadingModels: false,
                        
                        async loadModels(brandId, preserveSelection = false) {
                            if (!brandId) {
                                this.availableModels = [];
                                this.selectedModel = '';
                                return;
                            }
                            
                            this.loadingModels = true;
                            const previousModel = preserveSelection ? this.selectedModel : '';
                            
                            try {
                                const response = await fetch(`/dealer/api/brands/${brandId}/models`);
                                if (response.ok) {
                                    this.availableModels = await response.json();
                                    if (previousModel && this.availableModels.some(m => m.id == previousModel)) {
                                        this.selectedModel = previousModel;
                                    }
                                }
                            } finally {
                                this.loadingModels = false;
                            }
                        }
                    }"
                    x-init="if (selectedBrand) { await loadModels(selectedBrand, true) }">
                    @csrf

                    <div class="p-6 space-y-8">
                        <!-- Vehicle Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Vehicle Information</h3>
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <!-- Brand -->
                                <div>
                                    <label for="brand_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Brand <span class="text-red-500">*</span>
                                    </label>
                                    <select id="brand_id" name="brand_id" x-model="selectedBrand"
                                        @change="loadModels(selectedBrand)" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('brand_id') border-red-300 @enderror">
                                        <option value="">Select a brand</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('brand_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Model -->
                                <div>
                                    <label for="car_model_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Model <span class="text-red-500">*</span>
                                    </label>
                                    <select id="car_model_id" name="car_model_id" x-model="selectedModel"
                                        :disabled="!selectedBrand || loadingModels" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-100 disabled:cursor-not-allowed @error('car_model_id') border-red-300 @enderror">
                                        <option value="" x-text="loadingModels ? 'Loading models...' : 'Select a model'"></option>
                                        <template x-for="model in availableModels" :key="model.id">
                                            <option :value="model.id" x-text="model.name"></option>
                                        </template>
                                    </select>
                                    @error('car_model_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Year -->
                                <div>
                                    <label for="year" class="block text-sm font-medium text-gray-700 mb-2">
                                        Year <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" id="year" name="year" value="{{ old('year') }}"
                                        min="1900" max="{{ date('Y') + 1 }}" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('year') border-red-300 @enderror">
                                    @error('year')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Mileage -->
                                <div>
                                    <label for="mileage" class="block text-sm font-medium text-gray-700 mb-2">
                                        Mileage (km) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" id="mileage" name="mileage" value="{{ old('mileage') }}"
                                        min="0" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('mileage') border-red-300 @enderror">
                                    @error('mileage')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Condition -->
                                <div>
                                    <label for="condition" class="block text-sm font-medium text-gray-700 mb-2">
                                        Condition <span class="text-red-500">*</span>
                                    </label>
                                    <select id="condition" name="condition" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('condition') border-red-300 @enderror">
                                        <option value="">Select condition</option>
                                        <option value="excellent" {{ old('condition') == 'excellent' ? 'selected' : '' }}>Excellent</option>
                                        <option value="good" {{ old('condition') == 'good' ? 'selected' : '' }}>Good</option>
                                        <option value="fair" {{ old('condition') == 'fair' ? 'selected' : '' }}>Fair</option>
                                        <option value="poor" {{ old('condition') == 'poor' ? 'selected' : '' }}>Poor</option>
                                    </select>
                                    @error('condition')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Exterior Color -->
                                <div>
                                    <label for="exterior_color" class="block text-sm font-medium text-gray-700 mb-2">
                                        Exterior Color <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="exterior_color" name="exterior_color" value="{{ old('exterior_color') }}"
                                        required maxlength="50"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('exterior_color') border-red-300 @enderror">
                                    @error('exterior_color')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Interior Color -->
                                <div>
                                    <label for="interior_color" class="block text-sm font-medium text-gray-700 mb-2">
                                        Interior Color <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="interior_color" name="interior_color" value="{{ old('interior_color') }}"
                                        required maxlength="50"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('interior_color') border-red-300 @enderror">
                                    @error('interior_color')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- VIN Number -->
                                <div>
                                    <label for="vin_number" class="block text-sm font-medium text-gray-700 mb-2">
                                        VIN Number
                                    </label>
                                    <input type="text" id="vin_number" name="vin_number" value="{{ old('vin_number') }}"
                                        maxlength="17" placeholder="Optional"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <!-- License Plate -->
                                <div>
                                    <label for="license_plate" class="block text-sm font-medium text-gray-700 mb-2">
                                        License Plate
                                    </label>
                                    <input type="text" id="license_plate" name="license_plate" value="{{ old('license_plate') }}"
                                        maxlength="20" placeholder="Optional"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        <!-- Ownership & History -->
                        <div class="pt-6 border-t border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ownership & History</h3>
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <!-- Ownership Status -->
                                <div>
                                    <label for="ownership_status" class="block text-sm font-medium text-gray-700 mb-2">
                                        Ownership Status <span class="text-red-500">*</span>
                                    </label>
                                    <select id="ownership_status" name="ownership_status" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('ownership_status') border-red-300 @enderror">
                                        <option value="">Select status</option>
                                        <option value="owned" {{ old('ownership_status') == 'owned' ? 'selected' : '' }}>Owned (Paid Off)</option>
                                        <option value="financed" {{ old('ownership_status') == 'financed' ? 'selected' : '' }}>Financed (Loan)</option>
                                        <option value="leased" {{ old('ownership_status') == 'leased' ? 'selected' : '' }}>Leased</option>
                                    </select>
                                    @error('ownership_status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Service History -->
                                <div>
                                    <label for="service_history" class="block text-sm font-medium text-gray-700 mb-2">
                                        Service History <span class="text-red-500">*</span>
                                    </label>
                                    <select id="service_history" name="service_history" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('service_history') border-red-300 @enderror">
                                        <option value="">Select history</option>
                                        <option value="full" {{ old('service_history') == 'full' ? 'selected' : '' }}>Full Service History</option>
                                        <option value="partial" {{ old('service_history') == 'partial' ? 'selected' : '' }}>Partial Service History</option>
                                        <option value="none" {{ old('service_history') == 'none' ? 'selected' : '' }}>No Service History</option>
                                    </select>
                                    @error('service_history')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Accidents -->
                                <div class="sm:col-span-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="accidents" value="1" 
                                            {{ old('accidents') ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">This vehicle has been in an accident</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="pt-6 border-t border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Additional Information</h3>
                            
                            <!-- Description -->
                            <div class="mb-6">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Vehicle Description
                                </label>
                                <textarea id="description" name="description" rows="4"
                                    placeholder="Describe your vehicle's condition, features, recent repairs, or anything else we should know..."
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('description') border-red-300 @enderror">{{ old('description') }}</textarea>
                                <p class="mt-1 text-sm text-gray-500">Optional - Maximum 2000 characters</p>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Estimated Value -->
                            <div>
                                <label for="estimated_value" class="block text-sm font-medium text-gray-700 mb-2">
                                    Your Estimated Value (€)
                                </label>
                                <input type="number" id="estimated_value" name="estimated_value" value="{{ old('estimated_value') }}"
                                    min="0" step="0.01" placeholder="Optional"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <p class="mt-1 text-sm text-gray-500">Optional - What do you think your vehicle is worth?</p>
                            </div>
                        </div>

                        <!-- Photos -->
                        <div class="pt-6 border-t border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Vehicle Photos</h3>
                            <p class="text-sm text-gray-600 mb-4">Upload clear photos of your vehicle (optional but recommended)</p>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Upload Images
                                </label>
                                <input type="file" name="images[]" multiple accept="image/*"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                <p class="mt-1 text-sm text-gray-500">Maximum 5MB per image</p>
                                @error('images.*')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t border-gray-200">
                        <a href="{{ route('trade-ins.index') }}"
                            class="text-sm font-medium text-gray-700 hover:text-gray-900">
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Submit Trade-In Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
