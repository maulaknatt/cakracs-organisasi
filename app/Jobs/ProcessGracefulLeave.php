<?php

namespace App\Jobs;

use App\Events\VoiceStateUpdated;
use App\Models\VoiceSession;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class ProcessGracefulLeave implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;
    protected $channelId;

    public function __construct($userId, $channelId)
    {
        $this->userId = $userId;
        $this->channelId = $channelId;
    }

    public function handle(): void
    {
        $session = VoiceSession::where('user_id', $this->userId)->first();

        // Jika status masih 'disconnecting', berarti user tidak reconnect dalam grace period
        if ($session && $session->status === 'disconnecting' && $session->voice_channel_id == $this->channelId) {
            
            // 1. Update Cache (logic lama)
            $participants = Cache::get('voice_participants', []);
            if (isset($participants[$this->channelId])) {
                $participants[$this->channelId] = array_values(array_filter($participants[$this->channelId], function($u) {
                    return (string)$u['id'] !== (string)$this->userId;
                }));
                Cache::put('voice_participants', $participants);
            }

            // 2. Broadcast Leave
            $user = $session->user;
            broadcast(new VoiceStateUpdated($this->channelId, [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $user->foto_profil ? asset('storage/' . $user->foto_profil) : null,
            ], 'leave'));

            // 3. Delete Session or Mark as disconnected
            $session->delete();
        }
    }
}
