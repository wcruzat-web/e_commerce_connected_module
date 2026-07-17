<h2 class="font-bold text-lg mb-4">1. Choose Payment Method</h2>

        <div class="grid grid-cols-3 gap-4 mb-8" id="payment-method-options">
            @foreach(['Visa', 'Mastercard', 'GCash'] as $type)
                <label class="payment-option relative border-2 rounded-xl p-5 flex flex-col justify-center cursor-pointer hover:border-sky-300
                    {{ $selectedType === $type ? 'border-sky-500 bg-sky-50' : 'border-gray-200' }}">
                    <input type="radio" name="payment_type" value="{{ $type }}"
                        class="accent-sky-500 w-4 h-4 absolute top-4 left-4"
                        {{ $selectedType === $type ? 'checked' : '' }}>
                    <span class="font-semibold text-gray-800 text-center mt-2">{{ $type }}</span>
                </label>
            @endforeach
            @error('payment_type') <p class="text-xs text-red-500 mt-1 col-span-3">{{ $message }}</p> @enderror
        </div>
