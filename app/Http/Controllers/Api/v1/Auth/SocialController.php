<?php

namespace App\Http\Controllers\Api\v1\Auth;

use Broadcast;
use App\Models\{
    User\User,
    Photo
};
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Socialite;

/**
 * Авторизация через соцсети
 */
class SocialController extends Controller
{
    public function redirect($service)
    {
        return Socialite::with($service)->stateless()->redirect();
    }


    public function register($service)
    {
        $newUser = $this->{"createUser" . ucfirst($service)}(
            jsonRedecode(Socialite::driver($service)->stateless()->user())
        );
        $newUser->service = $service;
        $newUser->save();
        // logger(json_encode($user, JSON_PRETTY_PRINT));
    }

    private function createUserInstagram($user)
    {
        $user = $user->user;
        $newUser = User::create([
            'name' => $user->full_name,
        ]);
        $filename = uniqid() . '.jpg';
        file_put_contents(storage_path('app/public/' . Photo::UPLOAD_PATH . $filename), fopen($user->profile_picture, 'r'));

        $newUser->photos()->create([
            'filename' => $filename
        ]);

        return $newUser;
    }
}
