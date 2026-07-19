<?php

namespace App\Services;

use App\DTOs\CartSummaryDTO;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use App\Repositories\CartRepository;

class CartService
{
    public function __construct(
        private CartRepository $cartRepository,
    ) {}

    public function getOrCreateCart(int $customerId): Cart
    {
        $cart = $this->cartRepository->findOrCreateByCustomer($customerId);

        return $this->cartRepository->loadItems($cart);
    }

    public function addItem(Cart $cart, int $productId, int $quantity): CartItem
    {
        $product = Product::findOrFail($productId);

        $unitPrice = $product->sale_price ?? $product->price;

        $existing = $this->cartRepository->findExistingItem($cart, $productId);

        if ($existing) {
            $newQty = $existing->quantity + $quantity;

            if ($newQty > $product->stock) {
                abort(422, "Requested quantity ($newQty) exceeds available stock ({$product->stock}).");
            }

            return $this->cartRepository->updateItem($existing, [
                'quantity' => $newQty,
                'subtotal' => $newQty * $existing->unit_price,
            ]);
        }

        if ($quantity > $product->stock) {
            abort(422, "Requested quantity ($quantity) exceeds available stock ({$product->stock}).");
        }

        return $this->cartRepository->addItem($cart, [
            'product_id' => $productId,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'subtotal' => $quantity * $unitPrice,
        ]);
    }

    public function updateQuantity(CartItem $item, int $quantity): CartItem
    {
        $qty = max(1, $quantity);

        if ($qty > ($item->product->stock ?? 0)) {
            abort(422, "Requested quantity ($qty) exceeds available stock ({$item->product->stock}).");
        }

        return $this->cartRepository->updateItem($item, [
            'quantity' => $qty,
            'subtotal' => $qty * $item->unit_price,
        ]);
    }

    public function removeItem(CartItem $item): void
    {
        $this->cartRepository->deleteItem($item);
    }

    public function getSummary(Cart $cart): CartSummaryDTO
    {
        $items = $cart->items;
        $subtotal = $items->sum('subtotal');
        $shippingFee = $subtotal >= 3000 ? 0 : 120;
        $tax = round($subtotal * 0.08, 2);

        $discount = 0;
        $couponCode = null;
        $isFreeShipping = false;
        $couponLabel = '';
        $voucherStatus = null;
        $voucherMessage = null;

        if ($cart->coupon_code) {
            $coupon = Coupon::where('code', trim(strtoupper($cart->coupon_code)))->first();

            if ($coupon && $coupon->isValid()) {
                $couponCode = $coupon->code;
                $voucherStatus = 'valid';

                if ($coupon->isFreeShipping()) {
                    $isFreeShipping = true;
                    $couponLabel = 'FREE SHIPPING';
                    $shippingFee = 0;
                } else {
                    $discount = $coupon->calculateDiscount($subtotal);
                    $couponLabel = $coupon->discount_percentage . '% off';
                }
            } else {
                $voucherStatus = 'invalid';

                if (!$coupon) {
                    $voucherMessage = 'This voucher does not exist.';
                } elseif (!$coupon->is_active) {
                    $voucherMessage = 'This voucher is no longer active.';
                    $voucherStatus = 'inactive';
                } elseif ($coupon->expires_at && $coupon->expires_at->isPast()) {
                    $voucherMessage = 'This voucher has already expired.';
                    $voucherStatus = 'expired';
                } elseif ($coupon->max_uses && $coupon->used_count >= $coupon->max_uses) {
                    $voucherMessage = 'This voucher has reached its usage limit.';
                    $voucherStatus = 'limit';
                } else {
                    $voucherMessage = 'This voucher is no longer valid.';
                }

                $cart->update(['coupon_code' => null]);
            }
        }

        $grandTotal = round($subtotal + $shippingFee + $tax - $discount, 2);

        return new CartSummaryDTO(
            itemsCount: $items->sum('quantity'),
            subtotal: $subtotal,
            shippingFee: $shippingFee,
            tax: $tax,
            discount: $discount,
            grandTotal: $grandTotal,
            couponCode: $couponCode,
            isFreeShipping: $isFreeShipping,
            couponLabel: $couponLabel,
            voucherStatus: $voucherStatus,
            voucherMessage: $voucherMessage,
        );
    }

    public function applyCoupon(Cart $cart, string $code): array
    {
        $coupon = Coupon::where('code', trim(strtoupper($code)))->first();

        if (!$coupon) {
            return ['success' => false, 'message' => 'Voucher does not exist.'];
        }

        if (!$coupon->isValid()) {
            if (!$coupon->is_active) {
                return ['success' => false, 'message' => 'This voucher is no longer active.'];
            }
            if ($coupon->expires_at && $coupon->expires_at->isPast()) {
                return ['success' => false, 'message' => 'This voucher has already expired.'];
            }
            if ($coupon->max_uses && $coupon->used_count >= $coupon->max_uses) {
                return ['success' => false, 'message' => 'This voucher has reached its usage limit.'];
            }
            return ['success' => false, 'message' => 'This voucher is no longer valid.'];
        }

        $cart->update(['coupon_code' => $coupon->code]);

        return ['success' => true, 'message' => 'Voucher applied successfully!'];
    }

    public function removeCoupon(Cart $cart): void
    {
        $cart->update(['coupon_code' => null]);
    }
}
