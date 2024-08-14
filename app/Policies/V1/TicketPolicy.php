<?php

namespace App\Policies\V1;

use App\Models\Ticket;
use App\Models\User;
use App\Permissions\V1\Abilities;

class TicketPolicy
{


    /**
     * Determine whether the user can create models.
     */
    public function store(User $user): bool
    {
        return  $user->tokenCan(Abilities::CreateTicket) ||
                $user->tokenCan(Abilities::CreateOwnTicket);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        if ($user->tokenCan(Abilities::UpdateTicket)) {
            return true;
        } elseif ($user->tokenCan(Abilities::UpdateOwnTicket)) {
            return $user->id === $ticket->user_id;
        }

        return false;
    }

    public function replace(User $user, Ticket $ticket): bool
    {
       return $user->tokenCan(Abilities::ReplaceTicket);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        if ($user->tokenCan(Abilities::DeleteTicket)) {
            return true;
        } elseif ($user->tokenCan(Abilities::DeleteOwnTicket)) {
            return $user->id === $ticket->user_id;
        }

        return false;
    }


}
