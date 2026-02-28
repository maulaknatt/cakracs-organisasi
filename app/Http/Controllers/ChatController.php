<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Events\MessageUpdated;
use App\Events\MessageDeleted;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Notifications\MentionNotification;

use App\Events\PollVoted;
use App\Models\Poll;
use App\Models\PollOption;
use App\Models\PollVote;
use App\Models\UserSticker;

class ChatController extends Controller
{
    public function index()
    {
        auth()->user()->update(['last_chat_read_at' => now()]);
        $messages = ChatMessage::with(['user.role', 'parent.user', 'poll.options', 'poll.votes'])->latest()->take(100)->get()->reverse()->values();
        $users = User::with('role')->orderBy('name')->get();
        // Load user's custom stickers
        $userStickers = auth()->user()->stickers()->latest()->get();
        return view('dashboard.chat.index', compact('messages', 'users', 'userStickers'));
    }

    public function uploadSticker(Request $request)
    {
        $request->validate([
            'file' => 'required|file|image|max:2048', // 2MB
        ]);

        $path = $request->file('file')->store('chat/stickers', 'public');
        $url = Storage::url($path);

        $sticker = auth()->user()->stickers()->create([
            'sticker_url' => $url
        ]);

        return response()->json([
            'status' => 'success',
            'sticker' => $sticker
        ]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required_without_all:audio,file,poll_question,sticker_url|string|nullable',
            'audio' => 'nullable|file|mimes:webm,mp3,wav,ogg|max:10240',
            'file' => 'nullable|file|max:20480', // 20MB
            'type' => 'nullable|string|in:text,voice,image,video,document,sticker,poll',
            'sticker_url' => 'nullable|string',
            'parent_id' => 'nullable|exists:chat_messages,id',
            // Poll validation
            'poll_question' => 'required_if:type,poll|string|nullable',
            'poll_options' => 'required_if:type,poll|array|min:2',
            'multiple_choice' => 'boolean'
        ]);

        $type = $request->input('type', 'text');
        $filePath = null;
        $content = $request->message;
        $pollId = null;

        // Handle pre-defined Sticker URL
        if ($type === 'sticker' && $request->sticker_url) {
            $filePath = $request->sticker_url;
            $content = '[Stiker]';
        }
        // Handle Audio (Voice Note)
        elseif ($request->hasFile('audio')) {
            $type = 'voice';
            $path = $request->file('audio')->store('chat/voice-notes', 'public');
            $filePath = Storage::url($path);
            $content = '[Voice Note]';
        }
        // Handle General File Uploads
        elseif ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = strtolower($file->getClientOriginalExtension());
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $videoExtensions = ['mp4', 'mov', 'avi', 'webm'];

            // Determine type if not explicitly set
            if ($type === 'text') {
                if (in_array($extension, $imageExtensions))
                    $type = 'image';
                elseif (in_array($extension, $videoExtensions))
                    $type = 'video';
                else
                    $type = 'document';
            }

            $folder = 'chat/' . ($type === 'image' ? 'images' : ($type === 'video' ? 'videos' : ($type === 'sticker' ? 'stickers' : 'documents')));
            $path = $file->store($folder, 'public');
            $filePath = Storage::url($path);

            if (!$content) {
                $content = $type === 'image' ? '[Foto]' : ($type === 'video' ? '[Video]' : ($type === 'sticker' ? '[Stiker]' : $file->getClientOriginalName()));
            }
        }
        // Handle Polling
        if ($type === 'poll') {
            $poll = \App\Models\Poll::create([
                'user_id' => auth()->id(),
                'question' => $request->poll_question,
                'multiple_choice' => $request->multiple_choice ?? false
            ]);

            foreach ($request->poll_options as $optText) {
                if (trim($optText)) {
                    $poll->options()->create(['option_text' => $optText]);
                }
            }

            $pollId = $poll->id;
            $content = '[Polling: ' . $request->poll_question . ']';
        }

        $message = ChatMessage::create([
            'user_id' => auth()->id(),
            'parent_id' => $request->parent_id,
            'poll_id' => $pollId,
            'message' => $content,
            'type' => $type,
            'file_path' => $filePath,
        ]);

        // Detect Mentions
        $allOtherUsers = User::where('id', '!=', auth()->id())->get();
        $notifiedUserIds = [];

        // 1. Prioritas @everyone
        if (str_contains($content, '@everyone')) {
            foreach ($allOtherUsers as $u) {
                $u->notify(new MentionNotification($content, auth()->user(), $message->id, true));
                $notifiedUserIds[] = $u->id;
            }
        }

        // 2. Tag Individu (baik ada @everyone atau tidak, selama belum ternotifikasi)
        foreach ($allOtherUsers as $u) {
            if (in_array($u->id, $notifiedUserIds)) continue;

            if (str_contains($content, '@' . $u->name)) {
                $u->notify(new MentionNotification($content, auth()->user(), $message->id, false));
                $notifiedUserIds[] = $u->id;
            }
        }

        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => $message->load(['user.role', 'parent.user', 'poll.options', 'poll.votes']),
        ]);
    }

    public function update(Request $request, ChatMessage $chat)
    {
        if ($chat->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string',
        ]);

        $chat->update([
            'message' => $request->message,
        ]);

        broadcast(new MessageUpdated($chat))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => $chat->load('user'),
        ]);
    }

    public function votePoll(Request $request, Poll $poll)
    {
        $request->validate([
            'option_id' => 'required|exists:poll_options,id',
        ]);

        $userId = auth()->id();
        $optionId = $request->option_id;

        // Check if user already voted for this option
        $existingVote = PollVote::where('poll_id', $poll->id)
            ->where('user_id', $userId)
            ->where('poll_option_id', $optionId)
            ->first();

        if ($existingVote) {
            // Toggle off (unvote)
            $existingVote->delete();
        }
        else {
            // Check for multiple choice
            if (!$poll->multiple_choice) {
                // Remove previous votes from this poll for this user if single choice
                PollVote::where('poll_id', $poll->id)
                    ->where('user_id', $userId)
                    ->delete();
            }

            PollVote::create([
                'poll_id' => $poll->id,
                'poll_option_id' => $optionId,
                'user_id' => $userId,
            ]);
        }

        broadcast(new PollVoted($poll->load(['options', 'votes'])))->toOthers();

        return response()->json([
            'status' => 'success',
            'poll' => $poll->load(['options', 'votes']),
        ]);
    }

    public function destroy(ChatMessage $chat)
    {
        if ($chat->user_id !== auth()->id()) {
            abort(403);
        }

        $messageId = $chat->id;
        $chat->delete();

        broadcast(new MessageDeleted($messageId))->toOthers();

        return response()->json([
            'status' => 'success',
            'messageId' => $messageId,
        ]);
    }
}
