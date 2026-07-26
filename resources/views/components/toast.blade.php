@php
$__flash = [];
if (session('success')) $__flash['success'] = session('success');
if (session('error')) $__flash['error'] = session('error');
if (session('info')) $__flash['info'] = session('info');
@endphp
@if($__flash)
<script>
    window.__flash = @json($__flash);
</script>
@endif

<div id="toastContainer" class="fixed bottom-4 right-4 z-50 flex flex-col-reverse gap-2"></div>
