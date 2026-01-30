<?php

namespace App\Commands;

use Domain\TicketManager;
use Domain\DomainException;
use App\DispatchResult;

final class Assign implements Command
{
    public function name():string
    {
        return "assign";
    }

    public function help(): string
    {
        return "assign          -assign ticket to someone";
    }

    public function run(TicketManager $ticketManager, array $args): DispatchResult
    {
        if (count($args) < 2) {
            throw new DomainException("assign requires: id, username");
        }
        $id = $args[0];
        $username = $args[1];
        $ticketManager->assign($id, $username);
        return new DispatchResult("assigned #$id to $username");
    }
}