<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengaturanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user || !$user->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'success' => true,
            'data' => Pengaturan::all()
        ], 200);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'settings' => 'required|array',
            'settings.*.key' => 'required|exists:pengaturans,key',
            'settings.*.value' => 'required',
        ]);

        foreach ($request->settings as $s) {
            Pengaturan::where('key', $s['key'])->update(['value' => $s['value']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan berhasil diperbarui'
        ], 200);
    }
}
