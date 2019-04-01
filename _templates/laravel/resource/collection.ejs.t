---
to: app/Http/Resources/<%= Name %>/Collection.php
---
<?php

namespace App\Http\Resources\<%= Name %>;

use Illuminate\Http\Resources\Json\JsonResource;

class Collection extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [

        ]);
    }
}
