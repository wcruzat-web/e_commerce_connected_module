{{-- CRUZAT — success-scripts: post-order navigation actions --}}
{{--
    ERP MODULE: Checkout — Order Confirmation (Success Page)
    COMPONENT: Success Page JavaScript
    DESCRIPTION: Post-order navigation actions.
    TODO: Wire trackOrder() to actual tracking page with order data
--}}

<script>
    function trackOrder() {
        window.location.href = '{{ route('tracking') }}';
    }

    document.addEventListener('DOMContentLoaded', function() {
        setInterval(async function() {
            try {
                await fetch('{{ route("cart.summary") }}');
            } catch {}
        }, 300);
    });
</script>
