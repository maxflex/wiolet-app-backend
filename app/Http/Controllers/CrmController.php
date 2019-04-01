<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CrmController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $filters = [];
    protected $filterTablePrefix = [];

    protected function filter(Request $request, &$query)
    {
        // if (isset($request->sort_by) && $request->sort_by) {
        //     $query->orderBy($request->sort_by, $request->sort_type);
        // }

        // TODO: доделать
        if (isset($request->sort)) {
            $query->orderBy($request->sort, 'desc');
        }

        foreach($this->filters as $type => $fields) {
            foreach($fields as $key_field => $field) {
                $f = is_array($field) ? $key_field : $field;
                if (isset($request[$f])) {
                    $this->{'filter' . ucfirst($type)}($field, $request->{$f}, $query);
                }
            }
        }
    }

    protected function showBy(Request $request, $query)
    {
        return $query->paginate($request->paginate ?: 9999);
    }

    protected function showAll($query)
    {
        return $query->paginate(9999);
    }

    /**
     * FILTERS TYPES
     */

    protected function filterMultiple(string $field, $value, &$query)
    {
        $query->whereIn($this->getFieldName($field), explode(',', $value));
    }

    protected function filterEquals(string $field, $value, &$query)
    {
        $query->where($this->getFieldName($field), $value);
    }

    protected function filterNotNull(string $field, $value, &$query)
    {
        $query->whereNotNull($this->getFieldName($field));
    }

    protected function filterExclude(string $field, $value, &$query)
    {
        $query->where($field, '<>', $value);
    }

    protected function filterLike(string $field, $value, &$query)
    {
        $query->where($field, 'like', '%' . $value . '%');
    }

    protected function filterEntity(string $field, $value, &$query)
    {
        $query->where($field, getModelClass($value, true));
    }

    protected function filterInterval(string $field, $value, &$query)
    {
        $value = json_decode($value);
        if (isset($value->start)) {
            $query->whereRaw("DATE(`{$field}`) >= '{$value->start}'");
        }
        if (isset($value->end)) {
            $query->whereRaw("DATE(`{$field}`) <= '{$value->end}'");
        }
    }

    protected function filterLikeMultiple(array $fields, $value, &$query)
    {
        $query->where(function ($query) use ($fields, $value) {
            foreach($fields as $field) {
                $query->orWhere($field, 'like', '%' . $value . '%');
            }
        });
    }

    /**
     * Поиск в comma-separated values
     */
    protected function filterFindInSet(string $field, $value, &$query)
    {
        $query->whereRaw("FIND_IN_SET({$value}, {$field})");
    }

    private function getFieldName($field)
    {
        if (count($this->filterTablePrefix) === 0) {
            return $field;
        }
        foreach($this->filterTablePrefix as $table => $fields) {
            if (in_array($field, $fields)) {
                return "{$table}.{$field}";
            }
        }
    }
}
