<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Models\Event;
use App\Services\EventService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class EventController
 *
 * Handles event CRUD operations in a thin-controller style using EventService.
 */
class EventController extends Controller
{
    public function __construct(private EventService $service)
    {
        //
    }

    /**
     * List events with pagination, search and filters.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $events = $this->service->list($request->only(['q','from','to','location']));
            return $this->successResponse($events);
        } catch (\Exception $e) {
            Log::error('EventController@index | ' . $e->getMessage(), ['line' => $e->getLine()]);
            return $this->errorResponse('Failed to fetch events');
        }
    }

    /**
     * Show a single event with tickets.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        try {
            $event = Event::with('tickets')->findOrFail($id);
            return $this->successResponse($event);
        } catch (\Exception $e) {
            Log::error('EventController@show | ' . $e->getMessage(), ['line' => $e->getLine()]);
            return $this->errorResponse('Event not found', null, 404);
        }
    }

    /**
     * Create a new event (organizer only).
     *
     * @param StoreEventRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreEventRequest $request)
    {
        try {
            $data = $request->validated();
            $data['created_by'] = $request->user()->id;
            $event = $this->service->create($data);
            return $this->successResponse($event, 'Event created', 201);
        } catch (\Exception $e) {
            Log::error('EventController@store | ' . $e->getMessage(), ['line' => $e->getLine()]);
            return $this->errorResponse('Failed to create event');
        }
    }

    /**
     * Update an existing event (organizer only).
     *
     * @param StoreEventRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreEventRequest $request, int $id)
    {
        try {
            $event = Event::where('created_by', $request->user()->id)->findOrFail($id);
            $event->update($request->validated());
            return $this->successResponse($event, 'Event updated');
        } catch (\Exception $e) {
            Log::error('EventController@update | ' . $e->getMessage(), ['line' => $e->getLine()]);
            return $this->errorResponse('Failed to update event');
        }
    }

    /**
     * Delete an event (soft delete).
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, int $id)
    {
        try {
            $event = Event::where('created_by', $request->user()->id)->findOrFail($id);
            $event->delete();
            return $this->successResponse(null, 'Event deleted');
        } catch (\Exception $e) {
            Log::error('EventController@destroy | ' . $e->getMessage(), ['line' => $e->getLine()]);
            return $this->errorResponse('Failed to delete event');
        }
    }
}

