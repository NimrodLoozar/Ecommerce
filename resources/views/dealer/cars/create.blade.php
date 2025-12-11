<x-app-layout>
    <div class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <!-- Back button -->
            <div class="mb-8">
                <a href="{{ route('dealer.cars.index') }}"
                    class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                    <svg class="mr-2 size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M17 10a.75.75 0 0 1-.75.75H5.612l4.158 3.96a.75.75 0 1 1-1.04 1.08l-5.5-5.25a.75.75 0 0 1 0-1.08l5.5-5.25a.75.75 0 1 1 1.04 1.08L5.612 9.25H16.25A.75.75 0 0 1 17 10Z"
                            clip-rule="evenodd" />
                    </svg>
                    Back to Inventory
                </a>
            </div>

            <!-- Page header -->
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">Add New Car</h1>
                <p class="mt-2 text-sm text-gray-600">Add a new vehicle to your inventory.</p>
            </div>

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="mt-8 rounded-md bg-red-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="size-5 text-red-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM8.28 7.22a.75.75 0 0 0-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 1 0 1.06 1.06L10 11.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L11.06 10l1.72-1.72a.75.75 0 0 0-1.06-1.06L10 8.94 8.28 7.22Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">There were {{ $errors->count() }} error(s) with your
                                submission</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul role="list" class="list-disc space-y-1 pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('dealer.cars.store') }}" method="POST" enctype="multipart/form-data" class="mt-8"
                x-data="{
                    selectedBrand: '{{ old('brand_id') }}',
                    selectedModel: '{{ old('car_model_id') }}',
                    availableModels: [],
                    loadingModels: false,
                    fuelType: '{{ old('fuel_type', 'gasoline') }}',
                    transmission: '{{ old('transmission', 'manual') }}',
                    
                    async loadModels(brandId, preserveSelection = false) {
                        if (!brandId) {
                            this.availableModels = [];
                            this.selectedModel = '';
                            return;
                        }
                        
                        this.loadingModels = true;
                        const previousModel = preserveSelection ? this.selectedModel : '';
                        
                        if (!preserveSelection) {
                            this.selectedModel = '';
                        }
                        
                        try {
                            const response = await fetch(`/dealer/api/brands/${brandId}/models`);
                            if (response.ok) {
                                this.availableModels = await response.json();
                                // Restore selected model if it exists in the loaded models
                                if (previousModel) {
                                    const modelExists = this.availableModels.some(m => m.id == previousModel);
                                    if (modelExists) {
                                        this.selectedModel = previousModel;
                                    }
                                }
                            } else {
                                console.error('Failed to load models');
                                this.availableModels = [];
                            }
                        } catch (error) {
                            console.error('Error loading models:', error);
                            this.availableModels = [];
                        } finally {
                            this.loadingModels = false;
                        }
                    }
                }"
                x-init="if (selectedBrand) { await loadModels(selectedBrand, true) }">
                @csrf

                <div class="space-y-8">
                    <!-- Basic Information -->
                    <div class="border-b border-gray-900/10 pb-8">
                        <h2 class="text-base font-semibold text-gray-900">Basic Information</h2>

                        <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                            <!-- Brand -->
                            <div class="sm:col-span-3">
                                <label for="brand_id" class="block text-sm font-medium text-gray-900">Brand <span class="text-red-500">*</span></label>
                                <div class="mt-2">
                                    <select name="brand_id" id="brand_id" required 
                                        x-model="selectedBrand"
                                        @change="loadModels(selectedBrand)"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm @error('brand_id') ring-red-500 @enderror">
                                        <option value="">Select a brand first</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}"
                                                {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('brand_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Model -->
                            <div class="sm:col-span-3">
                                <label for="car_model_id" class="block text-sm font-medium text-gray-900">Model <span class="text-red-500">*</span></label>
                                <div class="mt-2">
                                    <select name="car_model_id" id="car_model_id" required
                                        x-model="selectedModel"
                                        :disabled="!selectedBrand || loadingModels || availableModels.length === 0"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 @error('car_model_id') ring-red-500 @enderror">
                                        <option value="" 
                                            x-text="loadingModels ? 'Loading models...' : (!selectedBrand ? 'Select a brand first' : (availableModels.length === 0 ? 'No models available for this brand' : 'Select a model'))"></option>
                                        <template x-for="model in availableModels" :key="model.id">
                                            <option :value="model.id" x-text="model.name"></option>
                                        </template>
                                    </select>
                                    @error('car_model_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500" x-show="!selectedBrand">Please select a brand to see available models</p>
                                    <p class="mt-1 text-xs text-amber-600" x-show="selectedBrand && !loadingModels && availableModels.length === 0">No models are configured for this brand. Please contact an administrator.</p>
                                </div>
                            </div>

                            <!-- Year -->
                            <div class="sm:col-span-2">
                                <label for="year" class="block text-sm font-medium text-gray-900">Year</label>
                                <div class="mt-2">
                                    <input type="number" name="year" id="year"
                                        value="{{ old('year', date('Y')) }}" min="1900" max="{{ date('Y') + 1 }}"
                                        required
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm @error('year') ring-red-500 @enderror">
                                    @error('year')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Category -->
                            <div class="sm:col-span-2">
                                <label for="category_id"
                                    class="block text-sm font-medium text-gray-900">Category</label>
                                <div class="mt-2">
                                    <select name="category_id" id="category_id" required
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm @error('category_id') ring-red-500 @enderror">
                                        <option value="">Select category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Condition -->
                            <div class="sm:col-span-2">
                                <label for="condition_id"
                                    class="block text-sm font-medium text-gray-900">Condition</label>
                                <div class="mt-2">
                                    <select name="condition_id" id="condition_id" required
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm @error('condition_id') ring-red-500 @enderror">
                                        <option value="">Select condition</option>
                                        @foreach ($conditions as $condition)
                                            <option value="{{ $condition->id }}"
                                                {{ old('condition_id') == $condition->id ? 'selected' : '' }}>
                                                {{ $condition->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('condition_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing & Stock -->
                    <div class="border-b border-gray-900/10 pb-8">
                        <h2 class="text-base font-semibold text-gray-900">Pricing & Stock</h2>

                        <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                            <!-- Price -->
                            <div class="sm:col-span-2">
                                <label for="price" class="block text-sm font-medium text-gray-900">Sale Price
                                    (€)</label>
                                <div class="mt-2">
                                    <input type="number" name="price" id="price" value="{{ old('price') }}"
                                        min="0" step="0.01" required
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm @error('price') ring-red-500 @enderror">
                                    @error('price')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Lease Price -->
                            <div class="sm:col-span-2">
                                <label for="lease_price" class="block text-sm font-medium text-gray-900">Lease Price
                                    (€/month)</label>
                                <div class="mt-2">
                                    <input type="number" name="lease_price" id="lease_price"
                                        value="{{ old('lease_price') }}" min="0" step="0.01"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm @error('lease_price') ring-red-500 @enderror">
                                    @error('lease_price')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">Optional - for leasing offers</p>
                                </div>
                            </div>

                            <!-- Stock Quantity -->
                            <div class="sm:col-span-2">
                                <label for="stock_quantity" class="block text-sm font-medium text-gray-900">Stock
                                    Quantity</label>
                                <div class="mt-2">
                                    <input type="number" name="stock_quantity" id="stock_quantity"
                                        value="{{ old('stock_quantity', 1) }}" min="0" required
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm @error('stock_quantity') ring-red-500 @enderror">
                                    @error('stock_quantity')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Vehicle Specifications -->
                    <div class="border-b border-gray-900/10 pb-8">
                        <h2 class="text-base font-semibold text-gray-900">Vehicle Specifications</h2>

                        <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                            <!-- Mileage -->
                            <div class="sm:col-span-2">
                                <label for="mileage" class="block text-sm font-medium text-gray-900">Mileage
                                    (km)</label>
                                <div class="mt-2">
                                    <input type="number" name="mileage" id="mileage"
                                        value="{{ old('mileage', 0) }}" min="0" required
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm @error('mileage') ring-red-500 @enderror">
                                    @error('mileage')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Fuel Type -->
                            <div class="sm:col-span-2">
                                <label for="fuel_type" class="block text-sm font-medium text-gray-900">Fuel
                                    Type</label>
                                <div class="mt-2">
                                    <select name="fuel_type" id="fuel_type" required x-model="fuelType"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm @error('fuel_type') ring-red-500 @enderror">
                                        <option value="gasoline">Gasoline</option>
                                        <option value="diesel">Diesel</option>
                                        <option value="electric">Electric</option>
                                        <option value="hybrid">Hybrid</option>
                                        <option value="plugin_hybrid">Plug-in Hybrid</option>
                                    </select>
                                    @error('fuel_type')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Transmission -->
                            <div class="sm:col-span-2">
                                <label for="transmission"
                                    class="block text-sm font-medium text-gray-900">Transmission</label>
                                <div class="mt-2">
                                    <select name="transmission" id="transmission" required x-model="transmission"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm @error('transmission') ring-red-500 @enderror">
                                        <option value="manual">Manual</option>
                                        <option value="automatic">Automatic</option>
                                        <option value="semi_automatic">Semi-Automatic</option>
                                    </select>
                                    @error('transmission')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Engine Size -->
                            <div class="sm:col-span-2">
                                <label for="engine_size" class="block text-sm font-medium text-gray-900">Engine Size
                                    (L)</label>
                                <div class="mt-2">
                                    <input type="number" name="engine_size" id="engine_size"
                                        value="{{ old('engine_size') }}" min="0" step="0.1"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm @error('engine_size') ring-red-500 @enderror">
                                    @error('engine_size')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">e.g., 2.0</p>
                                </div>
                            </div>

                            <!-- Horsepower -->
                            <div class="sm:col-span-2">
                                <label for="horsepower" class="block text-sm font-medium text-gray-900">Horsepower
                                    (HP)</label>
                                <div class="mt-2">
                                    <input type="number" name="horsepower" id="horsepower"
                                        value="{{ old('horsepower') }}" min="0"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm @error('horsepower') ring-red-500 @enderror">
                                    @error('horsepower')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Doors -->
                            <div class="sm:col-span-2">
                                <label for="doors" class="block text-sm font-medium text-gray-900">Doors</label>
                                <div class="mt-2">
                                    <select name="doors" id="doors" required
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm @error('doors') ring-red-500 @enderror">
                                        <option value="2" {{ old('doors') == 2 ? 'selected' : '' }}>2</option>
                                        <option value="3" {{ old('doors') == 3 ? 'selected' : '' }}>3</option>
                                        <option value="4" {{ old('doors') == 4 ? 'selected' : '' }}>4</option>
                                        <option value="5" {{ old('doors', 5) == 5 ? 'selected' : '' }}>5</option>
                                    </select>
                                    @error('doors')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Seats -->
                            <div class="sm:col-span-2">
                                <label for="seats" class="block text-sm font-medium text-gray-900">Seats</label>
                                <div class="mt-2">
                                    <select name="seats" id="seats" required
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm @error('seats') ring-red-500 @enderror">
                                        <option value="2" {{ old('seats') == 2 ? 'selected' : '' }}>2</option>
                                        <option value="4" {{ old('seats') == 4 ? 'selected' : '' }}>4</option>
                                        <option value="5" {{ old('seats', 5) == 5 ? 'selected' : '' }}>5</option>
                                        <option value="7" {{ old('seats') == 7 ? 'selected' : '' }}>7</option>
                                        <option value="8" {{ old('seats') == 8 ? 'selected' : '' }}>8</option>
                                        <option value="9" {{ old('seats') == 9 ? 'selected' : '' }}>9</option>
                                    </select>
                                    @error('seats')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Exterior Color -->
                            <div class="sm:col-span-2">
                                <label for="exterior_color" class="block text-sm font-medium text-gray-900">Exterior
                                    Color</label>
                                <div class="mt-2">
                                    <input type="text" name="exterior_color" id="exterior_color"
                                        value="{{ old('exterior_color') }}" required
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm @error('exterior_color') ring-red-500 @enderror">
                                    @error('exterior_color')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Interior Color -->
                            <div class="sm:col-span-2">
                                <label for="interior_color" class="block text-sm font-medium text-gray-900">Interior
                                    Color</label>
                                <div class="mt-2">
                                    <input type="text" name="interior_color" id="interior_color"
                                        value="{{ old('interior_color') }}" required
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm @error('interior_color') ring-red-500 @enderror">
                                    @error('interior_color')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Vehicle Identification -->
                    <div class="border-b border-gray-900/10 pb-8">
                        <h2 class="text-base font-semibold text-gray-900">Vehicle Identification</h2>

                        <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                            <!-- VIN Number -->
                            <div class="sm:col-span-3">
                                <label for="vin" class="block text-sm font-medium text-gray-900">VIN
                                    Number</label>
                                <div class="mt-2">
                                    <input type="text" name="vin" id="vin" value="{{ old('vin') }}"
                                        maxlength="17" required
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm @error('vin') ring-red-500 @enderror">
                                    @error('vin')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">17-character Vehicle Identification Number
                                    </p>
                                </div>
                            </div>

                            <!-- License Plate -->
                            <div class="sm:col-span-3">
                                <label for="license_plate" class="block text-sm font-medium text-gray-900">License
                                    Plate
                                    (optional)</label>
                                <div class="mt-2">
                                    <input type="text" name="license_plate" id="license_plate"
                                        value="{{ old('license_plate') }}" maxlength="20"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="border-b border-gray-900/10 pb-8">
                        <h2 class="text-base font-semibold text-gray-900">Description</h2>

                        <div class="mt-6">
                            <label for="description" class="block text-sm font-medium text-gray-900">Vehicle
                                Description</label>
                            <div class="mt-2">
                                <textarea name="description" id="description" rows="4"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"
                                    placeholder="Describe the vehicle's condition, history, and notable features...">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Features -->
                    <div class="border-b border-gray-900/10 pb-8">
                        <h2 class="text-base font-semibold text-gray-900">Features</h2>
                        <p class="mt-1 text-sm text-gray-600">Select all features that apply to this vehicle.</p>

                        <div class="mt-6">
                            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                                @foreach ($features as $feature)
                                    <div class="relative flex items-start">
                                        <div class="flex h-6 items-center">
                                            <input type="checkbox" name="features[]" value="{{ $feature->id }}"
                                                id="feature_{{ $feature->id }}"
                                                {{ in_array($feature->id, old('features', [])) ? 'checked' : '' }}
                                                class="size-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="feature_{{ $feature->id }}"
                                                class="font-medium text-gray-900">{{ $feature->name }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('features')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Images -->
                    <div class="border-b border-gray-900/10 pb-8">
                        <h2 class="text-base font-semibold text-gray-900">Images</h2>
                        <p class="mt-1 text-sm text-gray-600">Upload up to 10 images. First image will be the primary
                            image.</p>

                        <div class="mt-6">
                            <label for="images" class="block text-sm font-medium text-gray-900">Vehicle
                                Images</label>
                            <div class="mt-2">
                                <input type="file" name="images[]" id="images" multiple accept="image/*"
                                    class="block w-full text-sm text-gray-900 file:mr-4 file:rounded-md file:border-0 file:bg-indigo-600 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-indigo-500">
                                @error('images')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                @error('images.*')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Maximum file size: 2MB per image. Use JPG, PNG, or WEBP format.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Options -->
                    <div class="border-b border-gray-900/10 pb-8">
                        <h2 class="text-base font-semibold text-gray-900">Additional Options</h2>

                        <div class="mt-6">
                            <div class="relative flex items-start">
                                <div class="flex h-6 items-center">
                                    <input type="checkbox" name="is_featured" id="is_featured" value="1"
                                        {{ old('is_featured') ? 'checked' : '' }}
                                        class="size-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="is_featured" class="font-medium text-gray-900">Featured
                                        Vehicle</label>
                                    <p class="text-gray-500">Display this vehicle prominently on the homepage</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 flex items-center justify-end gap-x-6">
                    <a href="{{ route('dealer.cars.index') }}"
                        class="text-sm font-semibold text-gray-900 hover:text-gray-700">Cancel</a>
                    <button type="submit"
                        class="rounded-md bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Add Vehicle
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
