<h2 class="font-semibold text-gray-700 mb-4">
Saved Payment Methods
</h2>

<div class="space-y-4">

@forelse($paymentMethods as $index => $method)

<div class="flex items-center justify-between border rounded-lg p-5">

<div class="flex items-center gap-6">

<span class="text-2xl">
🛡️
</span>

<div class="w-24">
<p class="font-bold">
{{ $method->provider ?? $method->payment_type }}
</p>
</div>

@if($method->masked_account_number)
<p class="text-sm text-gray-500">
{{ $method->masked_account_number }}
</p>
@endif

<div>
<p class="text-xs text-gray-400">
Cardholder / Account Name
</p>
<p class="text-sm">
{{ $method->account_name }}
</p>
</div>

@if($method->expiryLabel)
<div>
<p class="text-xs text-gray-400">
Expiry Date
</p>
<p class="text-sm">
{{ $method->expiryLabel }}
</p>
</div>
@endif

</div>

<div class="flex items-center gap-4 relative">

@if($method->is_default)
<span class="bg-sky-100 text-sky-600 text-xs font-semibold px-3 py-1 rounded-full">
⭐ Default
</span>
@endif

<button
onclick="toggleMenu({{ $index }})"
class="text-gray-400 text-xl px-2">
&#9776;
</button>

<div
id="menu-{{ $index }}"
class="hidden absolute right-0 top-8 w-40 bg-white border rounded-lg shadow-lg text-sm z-10">

@if(!$method->is_default)
<form method="POST" action="{{ route('payment-methods.default', $method->payment_method_id) }}">
@csrf
<button class="w-full text-left px-4 py-2 hover:bg-gray-50">Set as Default</button>
</form>
@endif

<a href="{{ route('payment-methods.edit', $method->payment_method_id) }}"
class="block w-full text-left px-4 py-2 hover:bg-gray-50">Edit</a>

<form method="POST" action="{{ route('payment-methods.destroy', $method->payment_method_id) }}"
onsubmit="return confirm('Remove this payment method?');">
@csrf
@method('DELETE')
<button class="w-full text-left px-4 py-2 hover:bg-gray-50 text-red-500">Remove</button>
</form>

</div>

</div>

</div>

@empty

<div class="border-2 border-dashed border-sky-300 rounded-lg p-5 text-center text-gray-400">
No saved payment methods yet.
</div>

@endforelse

<a href="{{ route('add-payment') }}"
class="block w-full border-2 border-dashed border-sky-300 text-sky-500 rounded-lg p-5 mt-4 flex items-center gap-3 hover:bg-sky-50">

<span class="w-8 h-8 flex items-center justify-center rounded-full border-2 border-sky-300">
+
</span>

<span class="text-left">
<span class="block font-semibold text-sm">
Add Payment Method
</span>
<span class="block text-xs text-sky-400">
Save a new payment for faster checkout
</span>
</span>

</a>

<div class="bg-sky-50 border border-sky-100 rounded-lg p-5 flex items-center gap-4 mt-4">

<span class="w-9 h-9 rounded-full bg-sky-500 text-white flex items-center justify-center">
🛡️
</span>

<div class="flex-1">
<p class="font-semibold text-sm">
Secure Payments
</p>
<p class="text-xs text-gray-500">
Your payment information is encrypted and secure. We do not store your CVV.
</p>
</div>

<a href="#" class="text-sky-500 text-sm font-semibold">
Learn More
</a>

</div>

</div>
