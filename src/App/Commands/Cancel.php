<?php

namespace App\Commands;

use Domain\TicketManager;
use Domain\DomainException;
use App\DispatchResult;

final class Cancel implements Command
{
    public function name():string
    {
        return "cancel";
    }

    public function help(): string
    {
        return "cancel          -change ticket status to cancelled";
    }

    public function run(TicketManager $ticketManager, array $args): DispatchResult
    {
        if (count($args)<2)
            {
                throw new DomainException("cancel requires: id, reason");
            }
        $id = $args[0];
        $reason = $args[1];
        $ticketManager->cancel($id, $reason);
        return new DispatchResult("#$id now CANCELLED");

    }
}