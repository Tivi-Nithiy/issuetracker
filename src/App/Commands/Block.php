<?php

namespace App\Commands;

use Domain\TicketManager;
use Domain\DomainException;
use App\DispatchResult;

final class Block implements Command
{
    public function name():string
    {
        return "block";
    }

    public function help(): string
    {
        return "block           -change ticket status to block";
    }

    public function run(TicketManager $ticketManager, array $args): DispatchResult
    {
        if (count($args) < 2) {
            throw new DomainException("block requires: id, reason");
        }
        $id = $args[0];
        $reason = $args[1];
        $ticketManager->block($id, $reason);
        return new DispatchResult("#$id now BLOCKED");
    }
}