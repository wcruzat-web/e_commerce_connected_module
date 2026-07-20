<?php

namespace App\Http\Controllers\Admin\Api;

use App\Models\PromoBanner;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

// ESTEBAN — PromoBannerController: CRUD for promo banners (copied from original)
class PromoBannerController extends \App\Http\Controllers\Controller
{
    public function index(): JsonResponse
    {
        $banner = PromoBanner::orderByDesc('banner_id')->first();
        $banners = $banner ? [$banner] : [];
        return response()->json($banners);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'subtitle' => 'required|string|max:100',
        ]);

        $banner = PromoBanner::orderByDesc('banner_id')->first();

        if ($banner) {
            $banner->update([
                'title' => $validated['title'],
                'subtitle' => $validated['subtitle'],
                'is_active' => true,
            ]);
            $statusCode = 200;
        } else {
            $banner = PromoBanner::create([
                'title' => $validated['title'],
                'subtitle' => $validated['subtitle'],
                'is_active' => true,
            ]);
            $statusCode = 201;
        }

        PromoBanner::where('banner_id', '!=', $banner->banner_id)->delete();

        return response()->json([
            'success' => true,
            'banner' => $banner,
        ], $statusCode);
    }

    public function destroy(int $id): JsonResponse
    {
        PromoBanner::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Promo banner deleted',
        ]);
    }
}
