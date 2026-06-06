<?php

namespace App\Policies;

use App\Models\SupportTicket;
use App\Models\User;
use App\Policies\Concerns\GrantsAdminsFullAccess;

class SupportTicketPolicy
{
    use GrantsAdminsFullAccess;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, SupportTicket $ticket): bool
    {
        return $user->id === $ticket->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function reply(User $user, SupportTicket $ticket): bool
    {
        return $user->id === $ticket->user_id && ! $ticket->isClosed();
    }
}
