<?php

namespace App\Services;

use App\Models\Event;

class EventService
{
    public function list(array $filters = [])
    {
        $q = Event::query()->with('tickets');
        if (!empty($filters['q'])) $q->searchByTitle($filters['q']);
        $q->filterByDate($filters['from'] ?? null, $filters['to'] ?? null);
        if (!empty($filters['location'])) $q->where('location', 'like', '%' . $filters['location'] . '%');
        return $q->paginate(10);
    }
    public function create(array $data)
    {
        return Event::create($data);
    }
}
