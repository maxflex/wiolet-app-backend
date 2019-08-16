<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Event\{Event, EventType};
use DB;

trait UserScopes
{
    /**
     * Не скрытые анкеты
     */
    public function scopeNotHidden(Builder $query) : Builder
    {
        return $query->where('is_hidden', 0);
    }

    /**
     * Исключить уже просмотренные анкеты
     */
    public function scopeExcludeSeen(Builder $query, int $userId) : Builder
    {
        return $query
            ->leftJoin('user_seen_cards', function($join) use ($userId) {
                $join
                    ->on('user_seen_cards.card_id', '=', 'users.id')
                    ->whereRaw("user_seen_cards.user_id = {$userId}");
            })
            ->whereNull('user_seen_cards.card_id');
    }

    /**
     * Функция проверки на желаемый пол
     *
     * Если в фильтре user_1 стоит значение all (любой пол) - возвращаем true, если false проверяем по п.2.
     * Желаемый пол в фильтре user_1 совпадает со значением пола user_2
     */
    public function scopeMatchGender(Builder $query, $gender) : Builder
    {
        if ($gender !== null) {
            $query->where('users.gender', $gender);
        }
        return $query;
    }

    /**
     * Функция проверки на встречный пол
     *
     * Если в фильтре user_2 стоит значение all (любой пол) - true, если false проверяем в п.2
     * Желаемый пол user_2 совпадает со значением пола user_1
     */
    public function scopeMatchGenderReverse(Builder $query, $gender) : Builder
    {
        return $query->whereRaw("IF(preferences.gender IS NULL, true, preferences.gender = '{$gender}')");
    }

    /**
     * Функция проверки на желаемый возраст
     *
     * Возраст user_2 находится в диапазоне значений (включительно) фильтра user_1
     */
    public function scopeMatchAge(Builder $query, ?int $ageFrom, ?int $ageTo) : Builder
    {
        $age = '(YEAR(CURDATE()) - YEAR(users.birthdate))';
        if ($ageFrom !== null) {
            $query->whereRaw("{$age} >= {$ageFrom}");
        }
        if ($ageTo !== null) {
            $query->whereRaw("{$age} <= {$ageTo}");
        }
        return $query;
    }

    /**
     * Функция проверки на встречный возраст
     *
     * Возраст user_1 находится в диапазоне значений (включительно) фильтра user_2
     */
    public function scopeMatchAgeReverse(Builder $query, int $age) : Builder
    {
        return $query
            ->whereRaw("IF(preferences.age_from IS NULL, true, preferences.age_from <= {$age})")
            ->whereRaw("IF(preferences.age_to IS NULL, true, preferences.age_to >= {$age})");
    }

    /**
     * Функция блокировки
     *
     * Есть событие user_1 к user_2 - код 3 или код 6
     */
    public function scopeNotBanned(Builder $query, int $userId) : Builder
    {
        return $query->whereRaw("NOT EXISTS (SELECT 1 FROM events WHERE user_id_from = {$userId} AND user_id_to = users.id AND `type` IN (3, 6))");
    }

    /**
     * Функция блокировки
     *
     * Есть событие user_1 к user_2 - код 3 или код 6
     */
    public function scopeBannedBy(Builder $query, int $userId) : Builder
    {
        return $query->whereRaw("EXISTS (SELECT 1 FROM events WHERE user_id_from = {$userId} AND user_id_to = users.id AND `type` IN (3, 6))");
    }

    /**
     * Функция обратной блокировки
     *
     * Есть событие user_2 к user_1 - код 3 или код 6
     */
    public function scopeNotBannedReverse(Builder $query, int $userId) : Builder
    {
        return $query->whereRaw("NOT EXISTS (SELECT 1 FROM events WHERE user_id_from = users.id AND user_id_to = {$userId} AND `type` IN (3, 6))");
    }

    /**
     * Ещё не лайкнутые анкеты пользователем $userId
     */
    public function scopeNotLikedBy(Builder $query, int $userId) : Builder
    {
        return $query->lastActionNotEquals($userId, EventType::LIKE());
        // return $query->whereRaw("NOT((SELECT `type` FROM events WHERE user_id_from = {$userId} AND user_id_to = users.id ORDER BY id DESC LIMIT 1) <=> '" . EventType::LIKE . "')");
    }

    /**
     * Последнее действие от user_1 к user_2 равно $eventType
     * (или НЕ равно $eventType, eсли $negate = true)
     * ($reverse = true – действите от user_2 к user_1)
     */
    public function scopeLastAction(
        Builder $query,
        int $userId,
        EventType $eventType,
        bool $negate = false,
        bool $reverse = false
    ) : Builder
    {
        $condition = "(SELECT `type` FROM events WHERE " .
            ($reverse ? 'user_id_to' : 'user_id_from') . " = {$userId} AND " .
            ($reverse ? 'user_id_from' : 'user_id_to') . " = users.id ORDER BY id DESC LIMIT 1) <=> '" . $eventType->getValue() . "'";

        if ($negate) {
            $condition = "NOT( {$condition} )";
        }
        return $query->whereRaw($condition);
    }

    /**
     * Последнее действие от user_2 к user_1
     */
    public function scopeLastActionReverse(
        Builder $query,
        int $userId,
        EventType $eventType
    )
    {
        return $query->lastAction($userId, $eventType, false, true);
    }

    /**
     * Последнее действие от user_1 к user_2 не равно $eventType
     */
    public function scopeLastActionNotEquals(
        Builder $query,
        int $userId,
        EventType $eventType
    ) : Builder
    {
        return $query->lastAction($userId, $eventType, true);
    }

    /**
     * Последнее действие от user_2 к user_1 не равно $eventType
     */
    public function scopeLastActionNotEqualsReverse(
        Builder $query,
        int $userId,
        EventType $eventType
    ) : Builder
    {
        return $query->lastAction($userId, $eventType, true, true);
    }

    /**
     * Действие от user_1 к user_2 было когда-либо
     *
     * @param mixed $events если не указан, то проверка есть ли хоть какое-нибудь событие
     */
    public function scopeActionExists(
        Builder $query,
        int $userId,
        $events = null,
        $reverse = false,
        $negate = false
    ) : Builder
    {
        $userIdFrom = $reverse ? 'users.id' : $userId;
        $userIdTo   = $reverse ? $userId    : 'users.id';
        if ($events !== null) {
            $condition = is_array($events) ?  "events.type in (" . wrapInQuotes($events) . ")" : "events.type = '{$events}'";
        } else {
            $condition = 'true';
        }
        return $query->whereRaw(($negate ? 'not' : '') . "
            exists (select 1 from events where events.user_id_from = {$userIdFrom} and user_id_to = {$userIdTo} and {$condition})
        ");
    }

    public function scopeActionExistsReverse($query, $userId, $events = null)
    {
        return $query->actionExists($userId, $events, true);
    }

    public function scopeActionNotExists($query, $userId, $events = null)
    {
        return $query->actionExists($userId, $events, false, true);
    }

    public function scopeActionNotExistsReverse($query, $userId, $events = null)
    {
        return $query->actionExists($userId, $events, true, true);
    }


    /**
     * Cписок «Вы хотите встретиться»
     * UserList::YOU_WANT_TO_MEET
     * you-want-to-meet
     *
     * Последнее событие от user_1  к user_2 - лайк (1)
     * От user_2 к user_1 - во всей таблице отсутствует событие лайк(1)
     */
    public function scopeYouWantToMeetList(Builder $query, int $userId) : Builder
    {
        return $query
            ->lastAction($userId, EventType::LIKE())
            ->actionNotExistsReverse($userId, EventType::LIKE());
    }

    /**
     * Спискок «С вами хотят встретиться»
     * UserList::WANT_TO_MEET_YOU
     * want-to-meet-you
     *
     * Во всей таблице есть событие от user_2  к user_1 - лайк (1)
     * Последнее событие от user_1  к user_2 - код 2,  либо отсутствие событий.
     */
    public function scopeWantToMeetYouList(Builder $query, int $userId) : Builder
    {
        return $query
            ->actionExistsReverse($userId, EventType::LIKE)
            ->where(function ($query) use ($userId) {
                return $query->lastAction($userId, EventType::LIKE())->orWhere(function ($query) use ($userId) {
                    return $query->actionNotExists($userId);
                });
            });
    }

    /**
     * Список «Свидания»
     * UserList::DATES
     * dates
     *
     * Последнее событие в таблице Event_Stream от user_1 к user_2 - код 1 (лайк) -true
     * Последнее событие в таблице Event_Stream от user_2 к user_1 - код 1 (лайк) -true
     * Block_function - false
     */
    public function scopeDatesList(Builder $query, int $userId) : Builder
    {
        return $query
            ->lastAction($userId, EventType::LIKE())
            ->lastActionReverse($userId, EventType::LIKE())
            ->notBanned($userId);
    }

    /**
     * Показывать только новых (от меня ему не было никаких событий)
     */
    public function scopeOnlyNew(Builder $query, int $userId) : Builder
    {
        return $query
            ->whereRaw("NOT EXISTS (SELECT 1 FROM events WHERE events.user_id_from = {$userId} AND events.user_id_to = users.id)");
    }

    /**
     * Не включать в выборку пользователей, которые тебя дислайкнули
     */
    public function scopeExcludeDisliked(Builder $query, int $userId) : Builder
    {
        return $query
            ->lastActionNotEqualsReverse($userId, EventType::DISLIKE());
    }

    /**
     * Функция проверки дизлайков
     *
     * Получаем стек всех записей от user_1 к user_2
     * Если в стеке за последние 48 часов присутствует код 2, 4, 5 возвращаем false (анкету не показываем). Если true, то проверяем по пункту 3.
     * Если в стеке за последние 7 суток присутствует код 2, 4, 5 и их количество равно или больше 2-х - возвращаем false. Если true, то проверяем по пункту 4.
     * Если в стеке за последний 1 месяц присутствует код 2, 4, 5 и их количество равно или больше 3-х - возвращаем false. Если true, то проверяем по пункту 5.
     * Если в стеке за последние 6 месяцев присутствует код 2, 4, 5 и их количество равно или больше 4-х - возвращаем false. В противном случае функция возвращает true (анкету показываем)
     */
    public function isDislikedBy(User $user) : bool
    {
        // [кол-во суток, допустимое кол-во записей]
        $checks = [
            [30, 3],
            [7, 2],
            [2, 0],
        ];

        // Получаем все даты событий 2, 4, 5
        $dates = Event::query()
            ->where('user_id_from', $user->id)
            ->where('user_id_to', $this->id)
            ->whereIn('type', [
                EventType::DISLIKE,
                EventType::REMOVED_FROM_YOU_WANT_TO_MEET_LIST,
                EventType::REMOVED_FROM_WANT_TO_MEET_YOU_LIST
            ])
            ->where('created_at', '>', DB::raw('DATE(NOW() - INTERVAL 6 MONTH)'))
            ->orderBy('created_at', 'asc')
            ->pluck('created_at');

        if ($dates->count() > 4) {
            return true;
        }

        foreach($checks as $check) {
            list($days, $allowed) = $check;
            if ($dates->where('created_at', '>', now()->sub(new \DateInterval("P{$days}D"))->format(FORMAT_DATE_TIME))->count() >= $allowed) {
                return true;
            }
        }

        return false;
    }

    // FIXME: на случай, если придется выбрать одного из двух пользователей (НЕ СЕБЯ)
    // ->selectRaw(sprintf("IF(user_id_from = %d, user_id_to, user_id_from) as user_id", auth()->id()))
}
