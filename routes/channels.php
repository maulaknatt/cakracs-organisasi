<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int)$user->id === (int)$id;
});

Broadcast::channel('chat', function ($user) {
    return ['id' => $user->id, 'name' => $user->name];
});

Broadcast::channel('voice.{id}', function ($user, $id) {
    return ['id' => $user->id, 'name' => $user->name];
});

Broadcast::channel('global-presence', function ($user) {
    return ['id' => $user->id, 'name' => $user->name];
});
