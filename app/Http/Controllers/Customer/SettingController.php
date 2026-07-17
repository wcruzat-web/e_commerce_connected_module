<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\UserSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    private const KEYS = [
        'language', 'currency', 'theme',
        'notify_other_updates', 'notify_promotions', 'notify_product_updates',
    ];

    public function index(Request $request): View
    {
        /** @var Customer $customer */
        $customer = $request->user();

        $settings = $customer->settings()
            ->whereIn('setting_key', self::KEYS)
            ->pluck('setting_value', 'setting_key')
            ->toArray();

        $defaults = [
            'language' => 'English',
            'currency' => 'PHP',
            'theme' => 'Light',
            'notify_other_updates' => '1',
            'notify_promotions' => '1',
            'notify_product_updates' => '0',
        ];

        $settings = array_merge($defaults, $settings);

        $toggles = [
            ['key' => 'notify_other_updates', 'title' => 'Other Updates', 'description' => 'Get notifications about your account and ordering updates'],
            ['key' => 'notify_promotions', 'title' => 'Promotions and Discounts', 'description' => 'Receive updates about promotions and exclusive offers'],
            ['key' => 'notify_product_updates', 'title' => 'Product Updates', 'description' => 'Get notified when there is something new with your order'],
        ];

        return view('pages.customer.settings.settings', compact('settings', 'toggles'));
    }

    public function update(Request $request): RedirectResponse
    {
        /** @var Customer $customer */
        $customer = $request->user();

        $data = $request->validate([
            'language' => ['nullable', 'string', 'max:50'],
            'currency' => ['nullable', 'string', 'max:10'],
            'theme' => ['nullable', 'string', 'max:20'],
            'notify_other_updates' => ['nullable', 'in:0,1'],
            'notify_promotions' => ['nullable', 'in:0,1'],
            'notify_product_updates' => ['nullable', 'in:0,1'],
        ]);

        foreach (self::KEYS as $key) {
            if (str_starts_with($key, 'notify_')) {
                $value = $request->boolean($key) ? '1' : '0';
            } else {
                $value = $request->input($key);
                if ($value === null) {
                    continue;
                }
            }
            UserSetting::updateOrCreate(
                ['customer_id' => $customer->customer_id, 'setting_key' => $key],
                ['setting_value' => $value]
            );
        }

        return redirect()->route('settings')
            ->with('success', 'Settings saved.');
    }
}
