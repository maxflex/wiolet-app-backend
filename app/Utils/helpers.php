<?php

function cacheKey(...$args)
{
    return  implode(':', array_merge([strtolower(__('app.name'))], $args));
}


function extractFields($object, $fields, $merge = [])
{
    $return = [];
    foreach ($fields as $field) {
        $return[$field] = $object->{$field};
    }
    return array_merge($return, $merge);
}

function errorResponse($errors, int $code = 406)
{
    return response()->json([
        'errors' => $errors
    ], $code);
}

function errorMessageResponse(string $message, int $code = 406)
{
    return response()->json([
        'errors' => [
            'messages' => [$message],
        ]
    ], $code);
}

function emptyReponse()
{
    return response(null, 204);
}

function successResponse(string $message)
{
    return response()->json(compact('message'), 200);
}


// want-to-meet-you => wantToMeetYou
function toCamelCase(string $string, string $separator = '-') : string
{
    return lcfirst(implode('', array_map(function($e) { return ucfirst($e); }, explode($separator, $string))));
}
