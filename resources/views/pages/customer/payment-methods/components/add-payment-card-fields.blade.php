<div id="cardFields" class="space-y-4 mb-8">
            <div>
                <label class="text-sm text-gray-600">Cardholder Name</label>
                <input type="text" name="account_name"
                    value="{{ old('account_name', $method->account_name ?? '') }}"
                    placeholder="Alex Morgan"
                    class="border rounded-lg p-3 w-full mt-2 {{ $errors->has('account_name') ? 'border-red-400' : '' }}">
            </div>
            <div>
                <label class="text-sm text-gray-600">Card Number</label>
                <input type="text" name="masked_account_number"
                    value="{{ old('masked_account_number', $method->masked_account_number ?? '') }}"
                    placeholder="0123 4567 8901 2345" maxlength="19"
                    class="border rounded-lg p-3 w-full mt-2 {{ $errors->has('masked_account_number') ? 'border-red-400' : '' }}">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-600">Expiry Date</label>
                    <input type="text" name="expiry_date"
                        value="{{ old('expiry_date', isset($method) && $method->expiry_date ? $method->expiry_date->format('m/y') : '') }}"
                        placeholder="MM/YY" maxlength="5"
                        class="border rounded-lg p-3 w-full mt-2 {{ $errors->has('expiry_date') ? 'border-red-400' : '' }}">
                </div>
                <div>
                    <label class="text-sm text-gray-600">CVV</label>
                    <input type="password" name="cvv" placeholder="•••" maxlength="4"
                        class="border rounded-lg p-3 w-full mt-2 {{ $errors->has('cvv') ? 'border-red-400' : '' }}">
                </div>
            </div>
        </div>
