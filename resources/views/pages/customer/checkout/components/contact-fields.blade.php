{{-- CRUZAT — contact-fields: pre-fill from registered user (ERPV0.2) --}}
<div class="grid grid-cols-2 gap-4">
    <div>
        <label for="first_name" class="block text-xs font-medium text-gray-600 mb-1.5">First Name</label>
        {{-- CHANGES HERE: pre-fill from registered user data --}}
        <input type="text" name="first_name" id="first_name" placeholder="Alex" required
            value="{{ old('first_name', auth()->user()->first_name) }}"
            class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-200 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent @error('first_name') border-red-400 @enderror">
        @error('first_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label for="last_name" class="block text-xs font-medium text-gray-600 mb-1.5">Last Name</label>
        <input type="text" name="last_name" id="last_name" placeholder="Morgan" required
            value="{{ old('last_name', auth()->user()->last_name) }}"
            class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-200 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent @error('last_name') border-red-400 @enderror">
        @error('last_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>
</div>

{{-- Contact --}}
<div class="grid grid-cols-2 gap-4">
    <div>
        <label for="shipping_email" class="block text-xs font-medium text-gray-600 mb-1.5">Email</label>
        <input type="email" name="shipping_email" id="shipping_email" placeholder="alex@gmail.com" required
            value="{{ old('shipping_email', auth()->user()->email) }}"
            class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-200 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent @error('shipping_email') border-red-400 @enderror">
        @error('shipping_email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label for="shipping_phone" class="block text-xs font-medium text-gray-600 mb-1.5">Phone</label>
        <input type="tel" name="shipping_phone" id="shipping_phone" placeholder="+1 (555) 000-0000"
            value="{{ old('shipping_phone', auth()->user()->phone_number) }}"
            class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-200 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent @error('shipping_phone') border-red-400 @enderror">
        @error('shipping_phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>
</div>
