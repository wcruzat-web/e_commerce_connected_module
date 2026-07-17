<h2 class="font-bold text-gray-800 mb-4">Contact Information</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
            <div>
                <label class="text-sm text-gray-600">Full Name</label>
                <input type="text" name="recipient_name" value="{{ old('recipient_name', $addressModel->recipient_name) }}" class="border rounded-lg p-3 w-full mt-2">
            </div>
            <div>
                <label class="text-sm text-gray-600">Phone Number</label>
                <input type="text" name="phone_number" value="{{ old('phone_number', $addressModel->phone_number) }}" class="border rounded-lg p-3 w-full mt-2">
            </div>
            <div>
                <label class="text-sm text-gray-600">Address Type</label>
                <select name="address_type" class="border rounded-lg p-3 w-full mt-2 bg-white">
                    <option value="Home" {{ $addressModel->address_type === 'Home' ? 'selected' : '' }}>Home</option>
                    <option value="Work" {{ $addressModel->address_type === 'Work' ? 'selected' : '' }}>Work</option>
                    <option value="Other" {{ $addressModel->address_type === 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div>
                <label class="text-sm text-gray-600">Country / Region</label>
                <select name="country" class="border rounded-lg p-3 w-full mt-2 bg-white">
                    <option value="Philippines" {{ ($addressModel->country ?? 'Philippines') === 'Philippines' ? 'selected' : '' }}>Philippines</option>
                    <option value="United States" {{ ($addressModel->country ?? '') === 'United States' ? 'selected' : '' }}>United States</option>
                    <option value="Singapore" {{ ($addressModel->country ?? '') === 'Singapore' ? 'selected' : '' }}>Singapore</option>
                </select>
            </div>
        </div>
