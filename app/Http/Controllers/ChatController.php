<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Conversation;
use App\Models\User;
use App\Notifications\ChatMessageReceived;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index(): View
    {
        $user = Auth::guard('web')->user();

        $conversations = Conversation::query()
            ->where(function ($q) use ($user) {
                $q->where('user_one_id', $user->id)
                    ->orWhere('user_two_id', $user->id);
            })
            ->with([
                'userOne:id,name,username,image',
                'userTwo:id,name,username,image',
                'lastMessage' => function ($q) {
                    $q->select('chat_messages.id', 'chat_messages.conversation_id', 'chat_messages.sender_id', 'chat_messages.body', 'chat_messages.created_at');
                },
            ])
            ->withCount([
                'messages as unread_count' => function ($q) use ($user) {
                    $q->whereNull('read_at')->where('sender_id', '!=', $user->id);
                },
            ])
            ->orderByRaw('COALESCE(last_message_at, created_at) DESC')
            ->orderByDesc('id')
            ->get();

        return view('profile.messages.index', [
            'conversations' => $conversations,
            'user' => $user,
        ]);
    }

    public function start(int $sellerId): RedirectResponse
    {
        $user = Auth::guard('web')->user();

        if ($sellerId === $user->id) {
            $notification = trans('translate.Invalid request');
            $notification = ['messege' => $notification, 'alert-type' => 'error'];
            return redirect()->back()->with($notification);
        }

        $seller = User::where('id', $sellerId)
            ->where('status', 'enable')
            ->where('is_banned', 'no')
            ->first();

        if (!$seller) {
            $notification = trans('translate.User not found');
            $notification = ['messege' => $notification, 'alert-type' => 'error'];
            return redirect()->back()->with($notification);
        }

        $userOneId = min($user->id, $sellerId);
        $userTwoId = max($user->id, $sellerId);

        $conversation = Conversation::firstOrCreate([
            'user_one_id' => $userOneId,
            'user_two_id' => $userTwoId,
        ]);

        return redirect()->route('user.messages.show', $conversation->id);
    }

    public function show(int $conversationId): View
    {
        $user = Auth::guard('web')->user();

        $conversation = Conversation::with([
            'messages.sender:id,name',
            'userOne:id,name,username,image',
            'userTwo:id,name,username,image',
        ])->findOrFail($conversationId);

        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403);
        }

        ChatMessage::where('conversation_id', $conversation->id)
            ->whereNull('read_at')
            ->where('sender_id', '!=', $user->id)
            ->update(['read_at' => now()]);

        $otherUser = $conversation->user_one_id === $user->id ? $conversation->userTwo : $conversation->userOne;

        return view('profile.messages.show', [
            'conversation' => $conversation,
            'otherUser' => $otherUser,
            'user' => $user,
        ]);
    }

    public function store(Request $request, int $conversationId): RedirectResponse
    {
        $user = Auth::guard('web')->user();

        $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $conversation = Conversation::findOrFail($conversationId);

        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403);
        }

        $recipientId = $conversation->user_one_id === $user->id ? $conversation->user_two_id : $conversation->user_one_id;

        $message = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'body' => $request->body,
        ]);

        $conversation->last_message_at = now();
        $conversation->save();

        $recipient = User::find($recipientId);
        if ($recipient) {
            $recipient->notify(new ChatMessageReceived(
                $conversation->id,
                $user->id,
                (string) $user->name,
                Str::limit($message->body, 160)
            ));
        }

        return redirect()->route('user.messages.show', $conversation->id);
    }
}
