{{-- CRUZAT — checkout-stepper: Cart → Checkout → Payment → Success progress indicator --}}
{{--
    ==================================================================
    ERP MODULE: Shopping Cart (Cart Page)

    COMPONENT: Checkout Stepper

    DESCRIPTION:
    Horizontal 4-step progress indicator:
    Cart → Checkout → Payment → Success.

    Supports three states per step based on $activeStep:
      - 'done' (green checkmark, steps before active)
      - 'active' (blue number, current step)
      - 'upcoming' (gray, future steps)

    USAGE:
      @include('pages.customer.cart.components.checkout-stepper', ['activeStep' => 'payment'])

    ==================================================================

    TODO (Backend Integration):
    - Derive active step from current route name dynamically
    - Link each step label to its corresponding route

    ==================================================================
--}}

@php
    $steps = [
        ['label' => 'Cart', 'key' => 'cart'],
        ['label' => 'Checkout', 'key' => 'checkout'],
        ['label' => 'Payment', 'key' => 'payment'],
        ['label' => 'Success', 'key' => 'success'],
    ];

    $activeStep = $activeStep ?? 'cart';
    $found = false;
@endphp

<div class="flex items-center justify-center mb-10">
    @foreach ($steps as $index => $step)
        @php
            $isPast = !$found && $step['key'] !== $activeStep;
            if ($step['key'] === $activeStep) {
                $found = true;
            }
            $isDone = !$found && $step['key'] !== $activeStep;
            // Simplify: key order determines past/future
            $keys = array_column($steps, 'key');
            $activeIdx = array_search($activeStep, $keys);
            $currentIdx = array_search($step['key'], $keys);
            $state = $currentIdx < $activeIdx ? 'done' : ($currentIdx === $activeIdx ? 'active' : 'upcoming');
        @endphp
        <div class="flex items-center {{ !$loop->last ? 'flex-1 max-w-[160px]' : '' }}">
            <div class="flex flex-col items-center">
                <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold
                    @if($state === 'done') bg-green-700 text-white
                    @elseif($state === 'active') bg-blue-900 text-white
                    @else bg-gray-200 text-gray-500
                    @endif">
                    @if($state === 'done')
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    @else
                        {{ $index + 1 }}
                    @endif
                </div>
                <span class="text-xs mt-2 {{ $state === 'upcoming' ? 'text-gray-400' : 'text-gray-900 font-medium' }}">
                    {{ $step['label'] }}
                </span>
            </div>
            @if(!$loop->last)
                <div class="flex-1 h-0.5 mx-2 mb-5 {{ $state === 'done' ? 'bg-green-700 text-green-700' : 'bg-gray-200' }}"></div>
            @endif
        </div>
    @endforeach
</div>
