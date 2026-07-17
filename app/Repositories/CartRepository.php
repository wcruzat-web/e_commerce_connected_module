<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\CartItem;

class CartRepository
{
    public function findOrCreateByCustomer(int $customerId): Cart
    {
        return Cart::firstOrCreate(
            ['customer_id' => $customerId]
        );
    }

    public function loadItems(Cart $cart): Cart
    {
        return $cart->load('items.product');
    }

    public function findExistingItem(Cart $cart, int $productId): ?CartItem
    {
        return $cart->items()->where('product_id', $productId)->first();
    }

    public function addItem(Cart $cart, array $data): CartItem
    {
        return $cart->items()->create($data);
    }

    public function updateItem(CartItem $item, array $data): CartItem
    {
        $item->update($data);
        return $item;
    }

    public function deleteItem(CartItem $item): void
    {
        $item->delete();
    }
}
