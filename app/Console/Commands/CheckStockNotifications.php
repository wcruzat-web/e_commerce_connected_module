<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\WishlistItem;
use App\Services\NotificationService;
use Illuminate\Console\Attribute\Signature;
use Illuminate\Console\Command;

#[Signature('notifications:check-stock')]
#[Description('Check wishlist and cart items for products that went out of stock or were restocked, then notify customers')]
class CheckStockNotifications extends Command
{
    protected $signature = 'notifications:check-stock';
    protected $description = 'Check wishlist/cart items for stock changes and notify customers';

    public function handle(NotificationService $notificationService): int
    {
        $this->info('Checking stock changes for wishlist and cart items...');
        $notified = 0;

        $wishlistItems = WishlistItem::with('customer', 'product')->get();

        foreach ($wishlistItems as $item) {
            if (!$item->customer || !$item->product) continue;

            $inStock = $item->product->stock > 0;

            if ($item->in_stock && !$inStock) {
                $notificationService->notifyWarning(
                    $item->customer,
                    'Out of stock',
                    "{$item->product_name} from your wishlist is currently out of stock."
                );
                $item->update(['in_stock' => false]);
                $notified++;
            } elseif (!$item->in_stock && $inStock) {
                $notificationService->notifySuccess(
                    $item->customer,
                    'Back in stock',
                    "{$item->product_name} from your wishlist is now back in stock!"
                );
                $item->update(['in_stock' => true]);
                $notified++;
            }
        }

        $this->info("Done. {$notified} customers notified about stock changes.");
        return 0;
    }
}
