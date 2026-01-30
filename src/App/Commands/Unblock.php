<?php

namespace App\Commands;

use Domain\TicketManager;
use Domain\DomainException;
use App\DispatchResult;

final class Unblock implements Command
{
    public function name():string
    {
        return "unblock";
    }

    public function help(): string
    {
        return "unblock         -change ticket status from block to in progress";
    }

    public function run(TicketManager $ticketManager, array $args): DispatchResult
    {
        if (count($args) < 1) {
            throw new DomainException("unblock requires: id");
        }
        $id = $args[0];
        $ticketManager->unblock($id);
        return new DispatchResult("Ticket status changed");
    }
}