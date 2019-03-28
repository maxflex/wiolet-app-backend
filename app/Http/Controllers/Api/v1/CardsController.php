<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User\User;
use App\Models\Event\EventType;
use App\Http\Resources\Card\CardResource;

class CardsController extends Controller
{
    /**
     * Показать анкету, которая удовлетворяет критериям и ещё не была показана
     *
     * 1. Функция проверки на желаемый пол = true +
     * 2. Функция проверки на желаемый возраст = true +
     * 3. Функция проверки на встречный пол = true +
     * 4. Функция проверки на встречный возраст = true +
     * 5. Функция наличия в списке «Свидания» = false
     * 6. Функция наличия в списке «Вы хотите встретиться» = false
     * 7. Функция блокировки = false
     * 8. Функция обратной блокировки = false
     * 9. Функция проверки дизлайков = true
     */
    public function show()
    {
        $user = auth()->user();
        $preferences = $user->preferences;
        $age = '(YEAR(CURDATE()) - YEAR(users.birthdate))';

        $query = User::excludeSeen($user->id)
            ->join('user_preferences as preferences', 'preferences.user_id', '=', 'users.id')
            ->where('users.id', '<>', $user->id)
            ->where('users.city_id', $user->city_id)
            ->matchGender($preferences->gender)
            ->matchGenderReverse($user->gender)
            ->matchAge($preferences->age_from, $preferences->age_to)
            ->matchAgeReverse($user->age)
            ->notBanned($user->id)
            ->notBannedReverse($user->id)
            ->notLiked($user->id)
            ->inRandomOrder();

        /**
         * Не находится в списке свидания
         * (По сути, если ты её ещё не лайкнул и она тебя не кинула в бан / не выкинула из своего списка "вы хотите встретиться")
         */
        // $query->whereRaw("NOT (SELECT `type` FROM events WHERE user_id)");
        // $query->whereRaw("NOT (
        //     (SELECT `type` FROM events WHERE user_id_from = {$user->id} AND user_id_to = users.id ORDER BY id DESC LIMIT 1) = '" . EventType::LIKE . "' AND
        //     (SELECT `type` FROM events WHERE user_id_from = users.id AND user_id_to = {$user->id} ORDER BY id DESC LIMIT 1) = '" . EventType::LIKE . "'
        // )");

         /**
         * Она тебя не выкинула из списка (вы хотите встретиться)
         */

        $item = $query->first();

        // Больше нет карт
        if ($item === null) {
            return response(null, 204);
        }

        // Засчитать карту просмотренной
        $user->see($item);

        // Если низя, пропускаем
        if ($user->isDislikedBy($item)) {
            self::show();
        }

        return new CardResource($item);
    }
}
