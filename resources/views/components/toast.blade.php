@if(session('success') || session('error'))
<script>
    window.__flash = @json(['success' => session('success'), 'error' => session('error')]);
</script>
@endif

<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>
