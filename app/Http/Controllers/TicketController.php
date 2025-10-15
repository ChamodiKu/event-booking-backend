<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Class TicketController
 * Handles ticket CRUD operations using TicketService.
 */
class TicketController extends Controller
{
    public function __construct(private TicketService $service)
    {
    }

    public function store(StoreTicketRequest $request, int $event_id)
    {
        try {
            $ticket = $this->service->createTicket($request->user(), $event_id, $request->validated());
            return $this->successResponse($ticket, 'Ticket created', 201);
        } catch (Exception $e) {
            Log::error('TicketController@store | ' . $e->getMessage());
            return 
            $this->errorResponse($e->getMessage() === 'Forbidden' ? 'Unauthorized action' : 'Failed to create ticket');
        }
    }

    public function update(StoreTicketRequest $request, int $id)
    {
        try {
            $ticket = $this->service->updateTicket($request->user(), $id, $request->validated());
            return $this->successResponse($ticket, 'Ticket updated');
        } catch (Exception $e) {
            Log::error('TicketController@update | ' . $e->getMessage());
            return 
            $this->errorResponse($e->getMessage() === 'Forbidden' ? 'Unauthorized action' : 'Failed to update ticket');
        }
    }

    public function destroy(Request $request, int $id)
    {
        try {
            $this->service->deleteTicket($request->user(), $id);
            return $this->successResponse(null, 'Ticket deleted');
        } catch (Exception $e) {
            Log::error('TicketController@destroy | ' . $e->getMessage());
            return 
            $this->errorResponse($e->getMessage() === 'Forbidden' ? 'Unauthorized action' : 'Failed to delete ticket');
        }
    }
}
