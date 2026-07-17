<a href="{{ route('payment-methods') }}" class="inline-flex items-center gap-2 text-sky-500 text-sm font-medium hover:underline">
        <span>&larr;</span> Back to Payment Methods
    </a>

    <h1 class="text-3xl font-bold mt-4">{{ $isEdit ? 'Edit Payment Method' : 'Add Payment Method' }}</h1>
    <p class="text-gray-500 mt-2">Add a new payment method to make your checkout faster and more secure.</p>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl p-4 mt-4">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl p-4 mt-4">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <div id="serverErrors" data-errors='@json($errors->all())' class="hidden"></div>
    @endif
