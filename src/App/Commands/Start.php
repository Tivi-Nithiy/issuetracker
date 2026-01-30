<?php

namespace App\Commands;

use Domain\TicketManager;
use Domain\DomainException;
use App\DispatchResult;

final class Start implements Command
{
    public function name():string
    {
        return "start";
    }

    public function help(): string
    {
        return "start           -change ticket status to in Progress";
    }

    public function run(TicketManager $ticketManager, array $args): DispatchResult
    {
        if (count($args)<1)
            {
                throw new DomainException("start requires: id, reason");
            }
        $id = $args[0];
        $ticketManager->start($id);
        return new DispatchResult("Ticket status changed");

    }
}