<?php

namespace App\Commands;

use Domain\TicketManager;
use Domain\DomainException;
use App\DispatchResult;

final class NewTicket implements Command

{
    public function name():string
    {
        return 'new';
    }

    public function help(): string
    {
        return "new <title> <description> - Creates a new ticket";
    }

    public function run(TicketManager $ticketManager, array $args): DispatchResult
    {
        if (count($args) < 2) {
            throw new DomainException("new requires: title, description");
        }
        
        $title = $args[0];
        $description = $args[1];
        $ticket = $ticketManager->newTicket($title, $description);
        return new DispatchResult("Ticket #{$ticket->getId()} created: {$ticket->getTitle()}");
    } 
}