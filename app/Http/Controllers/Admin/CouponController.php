<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CouponController extends Controller
{
    public function index(): View
    {
        $coupons = Coupon::latest()->paginate(15);
        return view('pages.admin.coupons.index', compact('coupons'));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'type' => 'required|in:discount,free_shipping',
            'discount_percentage' => 'nullable|integer|min:1|max:100',
            'max_uses' => 'nullable|integer|min:1',
            'expires_at' => 'required|date',
        ]);

        $coupon = Coupon::create($data);

        return response()->json(['success' => true, 'coupon' => $coupon]);
    }

    public function destroy(Coupon $coupon): JsonResponse
    {
        $coupon->usages()->delete();
        $coupon->delete();

        return response()->json(['success' => true]);
    }
}