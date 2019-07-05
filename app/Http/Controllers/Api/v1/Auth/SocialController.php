<?php

namespace App\Http\Controllers\Api\v1\Auth;

use Broadcast;
use App\Models\{
    User\User,
    Photo
};
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Profile\ProfileResource;
use GuzzleHttp\Client;

/**
 * Авторизация через соцсети
 */
class SocialController extends Controller
{
    public function register($service, Request $request)
    {
        $user = $this->{"createUser" . ucfirst($service)}($request);
        $user->service = $service;
        $user->save();
        $token = auth()->login($user);
        return [
            'user' => new ProfileResource($user->fresh()),
            'access_token' => $token,
        ];
    }

    private function createUserInstagram(Request $request)
    {
        $client = new Client(['base_uri' => 'https://api.instagram.com/v1/']);
        $response = $client->get('users/self', [
            'query' => [
                'access_token' => $request->access_token
            ]
        ]);
        $response = json_decode($response->getBody());
        $user = User::create([
            'name' => $response->data->full_name,
            'service_id' => $response->data->id,
        ]);
        $filename = uniqid() . '.jpg';
        file_put_contents(storage_path('app/public/' . Photo::UPLOAD_PATH . $filename), fopen($response->data->profile_picture, 'r'));

        $user->photos()->create([
            'filename' => $filename
        ]);

        return $user;
    }
}
