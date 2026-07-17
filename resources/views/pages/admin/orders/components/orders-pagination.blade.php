<div class="flex items-center justify-between pt-4">
    <p class="text-sm text-gray-400">
        Showing {{ $orders->firstItem() }}–{{ $orders->lastItem() }} of {{ $orders->total() }} orders
    </p>
    <div class="flex items-center gap-1">
        {{ $orders->appends(request()->query())->links('pagination::tailwind') }}
    </div>
</div>
