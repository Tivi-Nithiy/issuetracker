<?php

namespace App\Commands;

use Domain\TicketManager;
use Domain\DomainException;
use App\DispatchResult;

final class History implements Command
{
    public function name():string
    {
        return "history";
    }

    public function help(): string
    {
        return "history         -view history";
    }

    public function run(TicketManager $ticketManager, array $args): DispatchResult
    {
        if (count($args) < 1) {
            throw new DomainException("history requires: id");
        }
        $id = $args[0];
        $history = $ticketManager->history($id);
        return new DispatchResult(implode(PHP_EOL, $history));
    }
}