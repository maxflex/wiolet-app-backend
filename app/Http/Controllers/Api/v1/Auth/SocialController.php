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
        $user = $this->{"createUser" . ucfirst($service)}($service, $request);
        if ($user->wasRecentlyCreated) {
            $user->service = $service;
            $user->save();
        }
        $token = auth()->login($user);
        return [
            'user' => new ProfileResource($user->fresh()),
            'access_token' => $token,
        ];
    }

    private function createUserInstagram($service, Request $request)
    {
        $client = new Client(['base_uri' => 'https://api.instagram.com/v1/']);
        $response = $client->get('users/self', [
            'query' => [
                'access_token' => $request->access_token
            ]
        ]);
        $response = json_decode($response->getBody());

        // сначала пытаемся найти по servece_id
        $user = User::query()
            ->where('service_id', $response->data->id)
            ->where('service', $service)
            ->first();

        // если не нашелся
        if ($user === null) {
            $user = User::create([
                'name' => $this->getName($response->data->full_name),
                'service_id' => $response->data->id,
            ]);

            // $filename = uniqid() . '.jpg';
            // file_put_contents(storage_path('app/public/' . Photo::UPLOAD_PATH . $filename), fopen($response->data->profile_picture, 'r'));

            // $user->photos()->create([
            //     'filename' => $filename
            // ]);
        }

        return $user;
    }

    private function getName($name)
    {
        $name = preg_replace('/[^a-zA-Zа-яА-Я0-9]/ui', '', $name);
        if (strlen($name) > 2 && strlen($name) <= 15) {
            return $name;
        }
        return null;
    }
}
