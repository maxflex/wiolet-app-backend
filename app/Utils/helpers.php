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

function errorResponse(string $message, int $code = 406)
{
    return response()->json(compact('message'), $code);
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
