<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('display_order', 'asc')->latest()->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:150'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'link_url' => ['nullable', 'string', 'max:255'],
            'display_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'is_active' => ['nullable'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:4096'],
            'image_url' => ['nullable', 'url', 'max:500'],
        ]);

        if (!$request->hasFile('image') && empty($validated['image_url'])) {
            return back()->withInput()->with('error', 'Please upload a banner image file or provide an image URL.');
        }

        $imagePath = $validated['image_url'] ?? '';

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'banner_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/banners'), $filename);
            $imagePath = '/uploads/banners/' . $filename;
        }

        Banner::create([
            'title' => $validated['title'] ?? null,
            'subtitle' => $validated['subtitle'] ?? null,
            'image_path' => $imagePath,
            'link_url' => $validated['link_url'] ?? null,
            'display_order' => (int)($validated['display_order'] ?? 0),
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Hero banner added successfully!');
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:150'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'link_url' => ['nullable', 'string', 'max:255'],
            'display_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'is_active' => ['nullable'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:4096'],
            'image_url' => ['nullable', 'url', 'max:500'],
        ]);

        if ($request->hasFile('image')) {
            // Remove old file if it was a local upload
            if (str_starts_with($banner->image_path, '/uploads/banners/')) {
                $oldPath = public_path(ltrim($banner->image_path, '/'));
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }
            $file = $request->file('image');
            $filename = 'banner_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/banners'), $filename);
            $banner->image_path = '/uploads/banners/' . $filename;
        } elseif (!empty($validated['image_url'])) {
            $banner->image_path = $validated['image_url'];
        }

        $banner->title = $validated['title'] ?? null;
        $banner->subtitle = $validated['subtitle'] ?? null;
        $banner->link_url = $validated['link_url'] ?? null;
        $banner->display_order = (int)($validated['display_order'] ?? 0);
        $banner->is_active = $request->has('is_active');
        $banner->save();

        return back()->with('success', 'Banner updated successfully!');
    }

    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);

        if (str_starts_with($banner->image_path, '/uploads/banners/')) {
            $path = public_path(ltrim($banner->image_path, '/'));
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        $banner->delete();

        return back()->with('success', 'Banner deleted successfully!');
    }
}
