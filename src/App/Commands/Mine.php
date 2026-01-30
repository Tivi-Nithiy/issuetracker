<?php

namespace App\Commands;

use Domain\TicketManager;
use Domain\DomainException;
use App\DispatchResult;

final class Mine implements Command
{
    public function name():string
    {
        return "mine";
    }

    public function help(): string
    {
        return "mine           -find tickets assigned to a user";
    }

    public function run(TicketManager $ticketManager, array $args): DispatchResult
    {
        
        if (count($args)<1)
            {
                throw new DomainException("mine requires: username");
            }
        $username = $args[0];
        $results = $ticketManager->searchMine($username);
        if ($results === [])
            {
                return new DispatchResult("No tickets assigned to {$username}");
            }
        return new DispatchResult("Ticket assigned to {$username}:\n". (implode(PHP_EOL, $results)));
        
    }
}