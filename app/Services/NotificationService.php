<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Notification;

class NotificationService
{
    public function notify(
        Customer $customer,
        string $title,
        ?string $message = null,
        string $type = 'System',
        ?string $icon = null,
        ?string $referenceType = null,
        ?int $referenceId = null,
        string $toastType = 'info'
    ): Notification {
        $notification = Notification::create([
            'customer_id' => $customer->customer_id,
            'title' => $title,
            'message' => $message,
            'notification_type' => $type,
            'icon' => $icon,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'is_read' => false,
        ]);

        session()->flash($toastType, $message ?: $title);

        return $notification;
    }

    public function notifySuccess(Customer $customer, string $title, ?string $message = null): Notification
    {
        return $this->notify($customer, $title, $message, 'System', 'system', toastType: 'success');
    }

    public function notifyInfo(Customer $customer, string $title, ?string $message = null): Notification
    {
        return $this->notify($customer, $title, $message, 'System', 'system', toastType: 'info');
    }

    public function notifyWarning(Customer $customer, string $title, ?string $message = null): Notification
    {
        return $this->notify($customer, $title, $message, 'System', 'system', toastType: 'error');
    }
}
