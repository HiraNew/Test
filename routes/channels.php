<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/
Broadcast::routes(['middleware' => ['auth:sanctum']]);


Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    \Log::info('Broadcast auth check user', ['user' => $user ? $user->id : null, 'id' => $id]);
    return (int) $user->id === (int) $id;
});


// For global notifications
Broadcast::channel('notifications', function ($user) {
    return true;
});
