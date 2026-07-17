@if(session('success') || session('error'))
<script>
    window.__flash = @json(['success' => session('success'), 'error' => session('error')]);
</script>
@endif

{{-- CHANGES HERE: renamed id to toastContainer to match JS references --}}
<div id="toastContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>
