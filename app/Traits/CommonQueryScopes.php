<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

trait CommonQueryScopes
{
    public function scopeFilterByDate(Builder $q, $from = null, $to = null)
    {
        if ($from) $q->where('date', '>=', Carbon::parse($from));
        if ($to) $q->where('date', '<=', Carbon::parse($to));
        return $q;
    }

    public function scopeSearchByTitle(Builder $q, $term = null)
    {
        if ($term) $q->where('title', 'like', "%" . $term . "%");
        return $q;
    }
}
