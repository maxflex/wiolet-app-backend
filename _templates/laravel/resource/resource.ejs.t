---
to: app/Http/Resources/<%= Name %>/Resource.php
---
<?php

namespace App\Http\Resources\<%= Name %>;

use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [

        ]);
    }
}
