<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Ticket;
use Exception;

class TicketService
{
    /**
     * Create a new ticket for an event.
     *
     * @throws Exception
     */
    public function createTicket($user, int $eventId, array $data): Ticket
    {
        $event = Event::where('created_by', $user->id)->findOrFail($eventId);
        return $event->tickets()->create($data);
    }

    /**
     * Update a ticket.
     *
     * @throws Exception
     */
    public function updateTicket($user, int $id, array $data): Ticket
    {
        $ticket = Ticket::findOrFail($id);
        $event = $ticket->event;

        if ($event->created_by !== $user->id && $user->role !== 'admin') {
            throw new Exception('Forbidden');
        }

        $ticket->update($data);
        return $ticket;
    }

    /**
     * Delete (soft delete) a ticket.
     *
     * @throws Exception
     */
    public function deleteTicket($user, int $id): void
    {
        $ticket = Ticket::findOrFail($id);
        $event = $ticket->event;

        if ($event->created_by !== $user->id && $user->role !== 'admin') {
            throw new Exception('Forbidden');
        }

        $ticket->delete();
    }
}
