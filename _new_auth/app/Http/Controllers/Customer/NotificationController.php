<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        /** @var Customer $customer */
        $customer = $request->user();

        $notifications = $customer->notifications()
            ->orderByDesc('created_at')
            ->get();

        $unreadCount = $notifications->where('is_read', false)->count();

        return view('customer.notifications', compact('notifications', 'unreadCount'));
    }

    public function markAllRead(Request $request): RedirectResponse
    {
        /** @var Customer $customer */
        $customer = $request->user();
        $customer->notifications()->update(['is_read' => true]);

        return redirect()->route('notifications')
            ->with('success', 'All notifications marked as read.');
    }

    public function markRead(Request $request, int $notification): RedirectResponse
    {
        /** @var Customer $customer */
        $customer = $request->user();
        $customer->notifications()->where('notification_id', $notification)->update(['is_read' => true]);

        return redirect()->route('notifications');
    }
}
