<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->get('limit', 15);
        $notifications = Auth::user()->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
        return response()->json($notifications);
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        
        if ($notification) {
            $notification->markAsRead();
            return response()->json(['message' => 'Notification marked as read']);
        }
        
        return response()->json(['message' => 'Notification not found'], 404);
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['message' => 'All notifications marked as read']);
    }

    public function destroy($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        
        if ($notification) {
            $notification->delete();
            return response()->json(['message' => 'Notification deleted']);
        }
        
        return response()->json(['message' => 'Notification not found'], 404);
    }

    public function send(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'title' => 'required|string',
                'message' => 'required|string',
                'type' => 'in:info,success,warning,error',
                'channels' => 'array',
                'channels.*' => 'in:database,broadcast',
                'display' => 'array',
                'display.*' => 'in:toast,browser'
            ]);

            $user = User::find($request->user_id);
            $user->notify(new GeneralNotification(
                $request->title,
                $request->message,
                $request->type ?? 'info',
                $request->channels ?? ['database'],
                $request->display ?? ['toast', 'browser']
            ));

            return response()->json(['message' => 'Notification sent successfully']);
        } catch (\Exception $e) {
            \Log::error('Notification error: ' . $e->getMessage());
            return response()->json(['message' => 'Errore: ' . $e->getMessage()], 500);
        }
    }

    public function unreadCount()
    {
        $count = Auth::user()->unreadNotifications()->count();
        return response()->json(['count' => $count]);
    }

    public function sendToAll(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string',
                'message' => 'required|string',
                'type' => 'in:info,success,warning,error',
                'channels' => 'array',
                'channels.*' => 'in:database,broadcast',
                'display' => 'array',
                'display.*' => 'in:toast,browser'
            ]);

            $users = User::all();
            $notification = new GeneralNotification(
                $request->title,
                $request->message,
                $request->type ?? 'info',
                $request->channels ?? ['database'],
                $request->display ?? ['toast', 'browser']
            );

            foreach ($users as $user) {
                $user->notify($notification);
            }

            return response()->json(['message' => 'Notifica inviata a tutti gli utenti']);
        } catch (\Exception $e) {
            \Log::error('Notification error: ' . $e->getMessage());
            return response()->json(['message' => 'Errore: ' . $e->getMessage()], 500);
        }
    }

    public function sendToUsers(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'title' => 'required|string',
            'message' => 'required|string',
            'type' => 'in:info,success,warning,error',
            'channels' => 'array',
            'channels.*' => 'in:database,broadcast',
            'display' => 'array',
            'display.*' => 'in:toast,browser'
        ]);

        $users = User::whereIn('id', $request->user_ids)->get();
        $notification = new GeneralNotification(
            $request->title,
            $request->message,
            $request->type ?? 'info',
            $request->channels ?? ['database'],
            $request->display ?? ['toast', 'browser']
        );

        foreach ($users as $user) {
            $user->notify($notification);
        }

        return response()->json(['message' => 'Notifica inviata agli utenti selezionati']);
    }
    
    /**
     * Mostra la pagina con tutte le notifiche
     */
    public function listPage()
    {
        return view('notifications.index');
    }
}
