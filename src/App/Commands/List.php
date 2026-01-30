<?php

namespace App\Commands;

use Domain\TicketManager;
use Domain\DomainException;
use App\DispatchResult;

final class ListAll implements Command
{
    public function name():string
    {
        return "list";
    }

    public function help(): string
    {
        return "list            -list all tickets";
    }

    public function run(TicketManager $ticketManager, array $args): DispatchResult
    {
        $ticketList = $ticketManager->listAll();
        return new DispatchResult(implode(PHP_EOL, $ticketList));
    }
}