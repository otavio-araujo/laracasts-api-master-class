<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\Api\TicketResource;
use App\Models\Ticket;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Gate;

class TicketController extends ApiController
{
    protected string $policyNamespace = 'App\\Policies\\V1';

    /**
     * Display a listing of the resource.
     */
    public function index(TicketFilter $filters)
    {
        return TicketResource::collection(Ticket::filter($filters)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        try {

            Gate::authorize('store', Ticket::class);

            return TicketResource::make(Ticket::create($request->mappedAttributes()));

        } catch (AuthorizationException $e) {
            return $this->error(  "You are not authorized to create that resource", 401);
        }


    }

    /**
     * Display the specified resource.
     */
    public function show($ticket_id)
    {
        try {

            $ticket = Ticket::findOrFail($ticket_id);

            if ($this->include('author')){
                return TicketResource::make($ticket->load('user'));
            }

            return TicketResource::make($ticket);

        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket cannot be found.', 404);
        }

    }

    /**
     * Replace the specified resource in storage.
     */
    public function replace(ReplaceTicketRequest $request, $ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            Gate::authorize('replace', [$ticket]);

            $ticket->update($request->mappedAttributes());

            return TicketResource::make($ticket);

        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket cannot be found.', 404);
        } catch (AuthorizationException $e) {
            return $this->error(  "You are not authorized to replace that resource", 401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, $ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            Gate::authorize('update', [$ticket]);

            $ticket->update($request->mappedAttributes());

            return TicketResource::make($ticket);

        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket cannot be found.', 404);
        } catch (AuthorizationException $e) {
            return $this->error(  "You are not authorized to update that resource", 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            Gate::authorize('delete', [$ticket]);

            $ticket->delete();

            return $this->ok('Ticket successfully deleted.');
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket cannot be found.', 404);
        } catch (AuthorizationException $e) {
            return $this->error(  "You are not authorized to delete that resource", 401);
        }
    }
}
