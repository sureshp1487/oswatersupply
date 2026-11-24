<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('display_order', 'asc')->latest()->get();
        return view('admin.services.index', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:100'],
            'badge_text' => ['nullable', 'string', 'max:50'],
            'features' => ['nullable', 'string'],
            'action_text' => ['nullable', 'string', 'max:100'],
            'action_url' => ['nullable', 'string', 'max:255'],
            'display_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'is_active' => ['nullable'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:4096'],
            'image_url' => ['nullable', 'url', 'max:500'],
        ]);

        if (!$request->hasFile('image') && empty($validated['image_url'])) {
            return back()->withInput()->with('error', 'Please upload a service image file or provide an image URL.');
        }

        $imagePath = $validated['image_url'] ?? '';

        if ($request->hasFile('image')) {
            $uploadDir = public_path('uploads/services');
            if (!File::exists($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true);
            }
            $file = $request->file('image');
            $filename = 'service_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);
            $imagePath = '/uploads/services/' . $filename;
        }

        // Parse features (comma separated or newline separated)
        $featuresArray = [];
        if (!empty($validated['features'])) {
            $lines = preg_split('/[\r\n,]+/', $validated['features']);
            foreach ($lines as $line) {
                $trimmed = trim($line);
                if (!empty($trimmed)) {
                    $featuresArray[] = $trimmed;
                }
            }
        }

        // Unique slug
        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;
        while (Service::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        Service::create([
            'name' => trim($validated['name']),
            'slug' => $slug,
            'tagline' => $validated['tagline'] ? trim($validated['tagline']) : null,
            'description' => $validated['description'] ? trim($validated['description']) : null,
            'icon' => $validated['icon'] ? trim($validated['icon']) : 'fa-solid fa-briefcase',
            'image_path' => $imagePath,
            'badge_text' => $validated['badge_text'] ? trim($validated['badge_text']) : null,
            'features' => $featuresArray,
            'action_text' => $validated['action_text'] ? trim($validated['action_text']) : 'Book Service',
            'action_url' => $validated['action_url'] ? trim($validated['action_url']) : '#serviceInquiry',
            'display_order' => (int)($validated['display_order'] ?? 0),
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Service added successfully!');
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:100'],
            'badge_text' => ['nullable', 'string', 'max:50'],
            'features' => ['nullable', 'string'],
            'action_text' => ['nullable', 'string', 'max:100'],
            'action_url' => ['nullable', 'string', 'max:255'],
            'display_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'is_active' => ['nullable'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:4096'],
            'image_url' => ['nullable', 'url', 'max:500'],
        ]);

        if ($request->hasFile('image')) {
            $uploadDir = public_path('uploads/services');
            if (!File::exists($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true);
            }
            if (str_starts_with($service->image_path, '/uploads/services/')) {
                $oldPath = public_path(ltrim($service->image_path, '/'));
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }
            $file = $request->file('image');
            $filename = 'service_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);
            $service->image_path = '/uploads/services/' . $filename;
        } elseif (!empty($validated['image_url'])) {
            $service->image_path = $validated['image_url'];
        }

        // Parse features
        if ($request->has('features')) {
            $featuresArray = [];
            if (!empty($validated['features'])) {
                $lines = preg_split('/[\r\n,]+/', $validated['features']);
                foreach ($lines as $line) {
                    $trimmed = trim($line);
                    if (!empty($trimmed)) {
                        $featuresArray[] = $trimmed;
                    }
                }
            }
            $service->features = $featuresArray;
        }

        $service->name = trim($validated['name']);
        $service->tagline = $validated['tagline'] ? trim($validated['tagline']) : null;
        $service->description = $validated['description'] ? trim($validated['description']) : null;
        $service->icon = $validated['icon'] ? trim($validated['icon']) : 'fa-solid fa-briefcase';
        $service->badge_text = $validated['badge_text'] ? trim($validated['badge_text']) : null;
        $service->action_text = $validated['action_text'] ? trim($validated['action_text']) : 'Book Service';
        $service->action_url = $validated['action_url'] ? trim($validated['action_url']) : '#serviceInquiry';
        $service->display_order = (int)($validated['display_order'] ?? 0);
        $service->is_active = $request->has('is_active');
        $service->save();

        return back()->with('success', 'Service updated successfully!');
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);

        if (str_starts_with($service->image_path, '/uploads/services/')) {
            $path = public_path(ltrim($service->image_path, '/'));
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        $service->delete();

        return back()->with('success', 'Service deleted successfully!');
    }
}
