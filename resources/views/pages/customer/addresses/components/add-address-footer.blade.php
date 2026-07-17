<div class="flex items-start gap-2 mb-8">
            <input type="checkbox" name="is_default" value="1" class="mt-1 accent-sky-500 w-4 h-4">
            <div>
                <p class="font-medium text-sm">Set as default address</p>
                <p class="text-gray-500 text-xs">This address will be used as your default shipping address.</p>
            </div>
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('addresses') }}" class="border rounded-lg px-6 py-3 text-gray-700 hover:bg-gray-50">Cancel</a>
            <button type="submit" class="bg-sky-500 hover:bg-sky-600 text-white px-6 py-3 rounded-lg inline-flex items-center gap-2">
                Save Address <span>&rarr;</span>
            </button>
        </div>
