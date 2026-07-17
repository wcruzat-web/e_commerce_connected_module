<div class="flex items-start gap-2 mb-8">
            <input type="checkbox" name="is_default" value="1"
                {{ old('is_default', $method->is_default ?? false) ? 'checked' : '' }}
                class="mt-1 accent-sky-500 w-4 h-4">
            <div>
                <p class="font-medium text-sm">Set as default payment method</p>
                <p class="text-gray-500 text-xs">This payment method will be used for your future purchases.</p>
            </div>
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('payment-methods') }}" class="border rounded-lg px-6 py-3 text-gray-700 hover:bg-gray-50">Cancel</a>
            <button type="submit" class="bg-sky-500 hover:bg-sky-600 text-white px-6 py-3 rounded-lg inline-flex items-center gap-2">
                {{ $isEdit ? 'Update Payment Method' : 'Save Payment Method' }} <span>&rarr;</span>
            </button>
        </div>
