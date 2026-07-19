<?php

// [ESTEBAN] — REST API, logic unchanged from original
namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Models\PromoBanner;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PromoBannerController extends Controller
{
    public function index(): JsonResponse
    {
        $banners = PromoBanner::orderBy('banner_id', 'desc')->get();
        return response()->json($banners);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'subtitle' => 'required|string|max:100',
        ]);

        $banner = PromoBanner::create([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'],
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'banner' => $banner,
        ], 201);
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
