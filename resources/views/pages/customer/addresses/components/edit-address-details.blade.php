<h2 class="font-bold text-gray-800 mb-4">Address Details</h2>

        <div class="mb-5">
            <label class="text-sm text-gray-600">Street Address</label>
            <input type="text" name="street" value="{{ old('street', $addressModel->street) }}" class="border rounded-lg p-3 w-full mt-2">
        </div>

        <div class="mb-5">
            <label class="text-sm text-gray-600">Barangay / Apartment, Suite (Optional)</label>
            <input type="text" name="barangay" value="{{ old('barangay', $addressModel->barangay) }}" class="border rounded-lg p-3 w-full mt-2">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
            <div>
                <label class="text-sm text-gray-600">City / Municipality</label>
                <input type="text" name="city" value="{{ old('city', $addressModel->city) }}" class="border rounded-lg p-3 w-full mt-2">
            </div>
            <div>
                <label class="text-sm text-gray-600">Province / State</label>
                <input type="text" name="province" value="{{ old('province', $addressModel->province) }}" class="border rounded-lg p-3 w-full mt-2">
            </div>
            <div>
                <label class="text-sm text-gray-600">ZIP / Postal Code</label>
                <input type="text" name="postal_code" value="{{ old('postal_code', $addressModel->postal_code) }}" class="border rounded-lg p-3 w-full mt-2">
            </div>
        </div>
