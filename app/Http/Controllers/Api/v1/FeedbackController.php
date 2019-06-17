<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Feedback\{Feedback, FeedbackType};

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'text' => ['required', 'string'],
            'type' => [
                'required',
                function($attribute, $value, $fail) {
                    if (! FeedbackType::isValid($value)) {
                        return $fail(__('validation.events.wrong-type'));
                    }
                }
            ],
        ]);
        auth()->user()->feedback()->create($request->all());
        return emptyResponse();
    }
}
