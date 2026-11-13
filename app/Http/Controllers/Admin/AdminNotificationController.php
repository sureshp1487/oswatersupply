<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PushSubscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminNotificationController extends Controller
{
    /**
     * Get recent notifications with unread count.
     */
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();

        if (!$user || !$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $unreadCount = $user->unreadNotifications()->count();
        $limit = (int) $request->get('limit', 15);

        $notifications = $user->notifications()
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function ($n) {
                $data = is_array($n->data) ? $n->data : json_decode($n->data, true);
                if (!is_array($data)) {
                    $data = [];
                }

                return [
                    'id' => $n->id,
                    'read' => $n->read_at !== null,
                    'read_at' => $n->read_at ? $n->read_at->toIso8601String() : null,
                    'created_at' => $n->created_at ? $n->created_at->diffForHumans() : '',
                    'created_at_exact' => $n->created_at ? $n->created_at->format('d M Y, h:i A') : '',
                    'data' => $data,
                ];
            });

        return response()->json([
            'success' => true,
            'unread_count' => $unreadCount,
            'notifications' => $notifications,
        ]);
    }

    /**
     * Fast polling/real-time sync endpoint: returns unread count and latest unread notifications.
     */
    public function unreadCount(Request $request): JsonResponse
    {
        $user = Auth::user();

        if (!$user || !$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'unread_count' => 0
            ], 403);
        }

        $unreadCount = $user->unreadNotifications()->count();
        $since = $request->get('since');

        $query = $user->unreadNotifications()->latest();
        if ($since) {
            $query->where('created_at', '>', $since);
        }

        $latest = $query->limit(5)->get()->map(function ($n) {
            $data = is_array($n->data) ? $n->data : json_decode($n->data, true);
            if (!is_array($data)) {
                $data = [];
            }

            return [
                'id' => $n->id,
                'created_at' => $n->created_at ? $n->created_at->diffForHumans() : '',
                'created_at_raw' => $n->created_at ? $n->created_at->toIso8601String() : '',
                'data' => $data,
            ];
        });

        return response()->json([
            'success' => true,
            'unread_count' => $unreadCount,
            'server_time' => now()->toIso8601String(),
            'latest' => $latest,
        ]);
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(Request $request, string $id): JsonResponse
    {
        $user = Auth::user();

        if (!$user || !$user->isAdmin()) {
            return response()->json(['success' => false], 403);
        }

        $notification = $user->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
        }

        $targetUrl = $notification && !empty($notification->data['target_url'])
            ? $notification->data['target_url']
            : null;

        return response()->json([
            'success' => true,
            'unread_count' => $user->unreadNotifications()->count(),
            'target_url' => $targetUrl,
        ]);
    }

    /**
     * Mark all notifications as read for current admin.
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $user = Auth::user();

        if (!$user || !$user->isAdmin()) {
            return response()->json(['success' => false], 403);
        }

        $user->unreadNotifications->markAsRead();

        return response()->json([
            'success' => true,
            'unread_count' => 0,
            'message' => 'All notifications marked as read',
        ]);
    }

    /**
     * Subscribe to Web Push notifications.
     */
    public function subscribePush(Request $request): JsonResponse
    {
        $user = Auth::user();

        if (!$user || !$user->isAdmin()) {
            return response()->json(['success' => false], 403);
        }

        $validated = $request->validate([
            'endpoint' => ['required', 'string'],
            'keys.p256dh' => ['nullable', 'string'],
            'keys.auth' => ['nullable', 'string'],
            'content_encoding' => ['nullable', 'string'],
        ]);

        $endpointHash = hash('sha256', $validated['endpoint']);

        PushSubscription::updateOrCreate(
            [
                'endpoint_hash' => $endpointHash,
            ],
            [
                'user_id' => $user->id,
                'endpoint' => $validated['endpoint'],
                'public_key' => $validated['keys']['p256dh'] ?? null,
                'auth_token' => $validated['keys']['auth'] ?? null,
                'content_encoding' => $validated['content_encoding'] ?? 'aes128gcm',
                'user_agent' => $request->userAgent(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Push notification subscription saved successfully',
        ]);
    }

    /**
     * Unsubscribe from Web Push notifications.
     */
    public function unsubscribePush(Request $request): JsonResponse
    {
        $user = Auth::user();

        if (!$user || !$user->isAdmin()) {
            return response()->json(['success' => false], 403);
        }

        $endpoint = $request->input('endpoint');
        if ($endpoint) {
            $endpointHash = hash('sha256', $endpoint);
            PushSubscription::where('endpoint_hash', $endpointHash)->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Push subscription removed',
        ]);
    }
}
