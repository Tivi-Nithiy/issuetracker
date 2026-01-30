<?php

namespace App\Commands;

use Domain\TicketManager;
use Domain\DomainException;
use App\DispatchResult;

final class Search implements Command
{
    public function name():string
    {
        return "search";
    }

    public function help(): string
    {
        return "search           -search for tickets assigned to user";
    }

    public function run(TicketManager $ticketManager, array $args): DispatchResult
    {
        
        if (count($args)<1)
            {
                throw new DomainException("search requires: title");
            }
        $searchTitle = $args[0];
        $resultsTitle = $ticketManager->searchTitles($searchTitle);
        if ($resultsTitle === [])
            {
                return new DispatchResult("No tickets with title {$searchTitle}");
            }
        return new DispatchResult("Ticket with title - {$searchTitle}:\n". (implode(PHP_EOL, $resultsTitle)));
        
    }
    
}