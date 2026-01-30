<?php

namespace App\Commands;

use Domain\TicketManager;
use Domain\DomainException;
use App\DispatchResult;

final class Done implements Command
{
    public function name():string
    {
        return "done";
    }

    public function help(): string
    {
        return "done            -change ticket status to done";
    }

    public function run(TicketManager $ticketManager, array $args): DispatchResult
    {
        if (count($args)<1)
            {
                throw new DomainException("done requires: id");
            }
        $id = $args[0];
        $ticketManager->done($id);
        return new DispatchResult("Ticket status changed");

    }
}