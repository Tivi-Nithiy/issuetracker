<?php

namespace App\Commands;

use Domain\TicketManager;
use Domain\DomainException;
use App\DispatchResult;

final class Unassign implements Command
{
    public function name():string
    {
        return "unassign";
    }

    public function help(): string
    {
        return "unassign        -unassign a ticket";
    }

    public function run(TicketManager $ticketManager, array $args): DispatchResult
    {
        if (count($args) < 1) {
            throw new DomainException("unassign requires: id");
        }
        $id = $args[0];
        $ticketManager->unassign($id);
        return new DispatchResult("#$id unassigned");
    }
}