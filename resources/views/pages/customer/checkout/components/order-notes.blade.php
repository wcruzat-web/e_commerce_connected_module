{{--
    ERP MODULE: Checkout — Order Notes & Submit
    COMPONENT: OrderNotes
    DESCRIPTION: Optional notes textarea and "Continue to Payment" submit button. Submits the entire checkout form.
    ROUTE: POST /checkout
--}}
<div>
    <label for="notes" class="block text-xs font-medium text-gray-600 mb-1.5">Order Notes (optional)</label>
    <textarea name="notes" id="notes" rows="2" placeholder="Any special instructions..." class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-200 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent">{{ old('notes') }}</textarea>
</div>

<button type="submit"
    class="w-full flex items-center justify-center gap-2 bg-cyan-500 hover:bg-cyan-600 transition-colors text-white text-sm font-semibold py-3 rounded-xl">
    Continue to Payment
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="5" y1="12" x2="19" y2="12"></line>
        <polyline points="12 5 19 12 12 19"></polyline>
    </svg>
</button>
