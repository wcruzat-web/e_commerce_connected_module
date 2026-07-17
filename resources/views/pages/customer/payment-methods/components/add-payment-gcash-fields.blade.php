<div id="gcashFields" class="space-y-4 mb-8 hidden">
            <div>
                <label class="text-sm text-gray-600">GCash Name</label>
                <input type="text" name="gcash_name"
                    value="{{ old('gcash_name', $method->account_name ?? '') }}"
                    placeholder="Alex Morgan"
                    class="border rounded-lg p-3 w-full mt-2 {{ $errors->has('gcash_name') ? 'border-red-400' : '' }}">
            </div>
            <div>
                <label class="text-sm text-gray-600">GCash Number</label>
                <div class="flex mt-2">
                    <span class="inline-flex items-center px-3 py-3 text-sm rounded-l-lg border border-r-0 border-gray-200 bg-gray-50 text-gray-500 font-medium">+63</span>
                    <input type="text" name="gcash_number"
                        value="{{ old('gcash_number', '') }}"
                        placeholder="9123456789" maxlength="10"
                        class="w-full px-4 py-2.5 text-sm rounded-r-lg border border-gray-200 bg-gray-100 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent {{ $errors->has('gcash_number') ? 'border-red-400' : '' }}">
                </div>
            </div>
        </div>
