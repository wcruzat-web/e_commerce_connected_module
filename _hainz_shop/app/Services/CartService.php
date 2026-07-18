<?php

namespace App\Services;

use App\DTOs\CartSummaryDTO;
use App\Models\Cart;
use App\Models\CartItem;
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

            return $this->cartRepository->updateItem($existing, [
                'quantity' => $newQty,
                'subtotal' => $newQty * $existing->unit_price,
            ]);
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
        $tax = round($subtotal * 0.08, 2);

        return new CartSummaryDTO(
            itemsCount: $items->sum('quantity'),
            subtotal: $subtotal,
            tax: $tax,
            grandTotal: round($subtotal + $tax, 2),
        );
    }

}
