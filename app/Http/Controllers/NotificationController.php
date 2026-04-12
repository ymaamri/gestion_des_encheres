<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $client = Auth::user()->client;

        if (!$client) {
            return redirect()->route('dashboard')->with('error', 'Client profile not found.');
        }

        $notifications = Notification::where('client_id', $client->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $unreadCount = Notification::where('client_id', $client->id)
            ->where('lue', false)
            ->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markAsRead(Notification $notification)
    {
        // Check if notification belongs to current user
        if ($notification->client_id !== Auth::user()->client->id) {
            abort(403);
        }

        $notification->marquerCommeLue();

        return back()->with('success', 'Notification marquée comme lue.');
    }

    public function markAllAsRead(Request $request)
    {
        $client = Auth::user()->client;

        if ($client) {
            Notification::where('client_id', $client->id)
                ->where('lue', false)
                ->update(['lue' => true]);
        }

        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }
}