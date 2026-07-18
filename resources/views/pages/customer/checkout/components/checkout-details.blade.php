{{--
    ERP MODULE: Checkout — Shipping & Contact Details (Checkout Page)
    COMPONENT: Checkout Details Form
    DESCRIPTION: Contact info + shipping address form. Includes sub-components: contact-fields, address-section, order-notes. Inline JS for address modal/card interactions.
    DATA SOURCE: $cart, $summary, $addresses from CheckoutController@index
    ROUTE: POST /checkout (CheckoutController@store)
    TODO: Extract JS to separate file and convert POST to JSON REST API
--}}

<div class="lg:col-span-2 bg-white border border-gray-200 rounded-2xl p-5 shadow-sm h-fit">
    <div class="flex items-center gap-2 mb-5">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="1" y="3" width="15" height="13"></rect>
            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
            <circle cx="5.5" cy="18.5" r="2.5"></circle>
            <circle cx="18.5" cy="18.5" r="2.5"></circle>
        </svg>
        <h2 class="text-sm font-semibold text-gray-900">Checkout Details</h2>
    </div>

    <form method="POST" action="{{ route('checkout.store') }}" class="space-y-4">
        @csrf

        @include('pages.customer.checkout.components.contact-fields')

        <div>
            <label class="block text-xs font-medium text-gray-600 mb-2">Shipping Address</label>
            @include('pages.customer.checkout.components.address-section')
        </div>

        @include('pages.customer.checkout.components.order-notes')
    </form>
</div>


