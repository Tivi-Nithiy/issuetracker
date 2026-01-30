<?php

namespace App\Commands;

use Domain\TicketManager;
use Domain\DomainException;
use App\DispatchResult;

final class Show implements Command
{
    public function name():string
    {
        return "show";
    }

    public function help(): string
    {
        return "show            -show all tickets";
    }

    public function run(TicketManager $ticketManager, array $args): DispatchResult
    {
        if (count($args) < 1)
        {
            throw new DomainException ("show requires: id, reason");
        }
        $id = $args[0];
        $ticket = $ticketManager->show($id);
        return new DispatchResult("Ticket #{$ticket->getId()} \nTitle: {$ticket->getTitle()}\nDescription: {$ticket->getDescription()}\nStatus: {$ticket->getStatus()->value}\nLast Update: {$ticket->getUpdatedAt()}\nAssignee: {$ticket->getAssignee()}\nComments: \n". (implode(PHP_EOL, $ticket->getComments()))
        );
    }
}