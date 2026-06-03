<?php

namespace App\Policies;

use App\Models\SupportTicket;
use App\Models\User;

class SupportTicketPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

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
        return ! $user->isAdmin();
    }

    public function reply(User $user, SupportTicket $ticket): bool
    {
        return $user->id === $ticket->user_id && ! $ticket->isClosed();
    }
}
