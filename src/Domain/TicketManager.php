<?php

namespace Domain;

use Domain\Ticket;

final class TicketManager 
{
    private array $tickets = [];

    public function newTicket(string $title, string $description): Ticket
    {
        $ticket = new Ticket($title, $description);
        $this->tickets[$ticket->getId()] = $ticket;
        $ticket->setCreatedAt();
        $ticket->addHistory("CREATED:", $title);
        $ticket->setUpdatedAt();
       
        return $ticket;
    }

    public function listAll()
    {
        $ticketList = [];
        foreach ($this->tickets as $ticket)
            {
                $ticketList[] = "{$ticket->getId()} | {$ticket->getStatus()->value} | {$ticket->getAssignee()} | {$ticket->getTitle()}, ";
            }
        return $ticketList;
    }

    public function show(int $id)
    {
        foreach ($this->tickets as $ticket)
            {
                if ($ticket->getId() === $id)
                {
                    return $ticket;
                }
            }
        throw new DomainException("Ticket with ID $id not found");
    }

    public function block(int $id, string $reason): Ticket
    {
        foreach ($this->tickets as $ticket)
            {
                if ($ticket->getId() === $id)
                {
                    $ticket->statusBlocked();
                    $ticket->addHistory("STATUS:", "IN PROGRESS -> BLOCKED");
                    $ticket->setUpdatedAt();

                    return $ticket;
                }
            }
        throw new DomainException("Ticket with ID $id not found");
    }

    public function cancel(int $id, string $reason): Ticket
    {
        foreach ($this->tickets as $ticket)
            {
                if ($ticket->getId() === $id)
                    {
                        $prevStatus = $ticket->getStatus()->value;
                        $statusTransition = "$prevStatus" . "->CANCELLED";
                        $ticket->statusCancelled();
                        $ticket->addHistory("STATUS:", $statusTransition);
                        $ticket->setUpdatedAt();
                        return $ticket;
                    }
            }
        throw new DomainException("Ticket with ID $id not found");
    }

    public function done(int $id): Ticket
    {
        foreach ($this->tickets as $ticket)
            {
                if ($ticket->getId() === $id)
                    {
                        $prevStatus = $ticket->getStatus()->value;
                        $statusTransition = "$prevStatus" . "->DONE";
                        $ticket->statusDone();
                        $title = $ticket->getTitle();
                        $ticket->addHistory("STATUS:", $statusTransition);
                        $ticket->setUpdatedAt();
                        return $ticket;
                    }
            }
        throw new DomainException("Ticket with ID $id not found");
    }

    public function start(int $id): Ticket
    {
        foreach ($this->tickets as $ticket)
            {
                if ($ticket->getId() === $id)
                    {
                        $ticket->statusInProgress();
                        $title = $ticket->getTitle();
                        $ticket->addHistory("STATUS:", "NEW->INPROGRESS");
                        $ticket->setUpdatedAt();
                        return $ticket;
                    }
            }
        throw new DomainException("Ticket with ID $id not found");
    }

    public function unblock(int $id): Ticket
    {
        foreach ($this->tickets as $ticket)
            {
                if ($ticket->getId() === $id)
                    {
                        $ticket->statusUnblock();
                        $title = $ticket->getTitle();
                        $ticket->addHistory("STATUS:", "BLOCKED->UNBLOCKED");
                        $ticket->setUpdatedAt();
                        return $ticket;
                    }
            }
        throw new DomainException("Ticket with ID $id not found");
    }

    public function assign(int $id, string $username): Ticket
    {
        foreach ($this->tickets as $ticket)
            {
                if ($ticket->getId() === $id)
                    {
                        $ticket->setAssignee($username);
                        $ticket->addHistory("ASSIGNED:", $username);
                        $ticket->setUpdatedAt();
                        return $ticket;
                    }
            }
        throw new DomainException("Ticket with ID $id not found");
    }

    public function unassign(int $id): Ticket
    {
        foreach ($this->tickets as $ticket)
            {
                if ($ticket->getId() === $id)
                    {
                        $ticket->setAssignee("-");
                        $ticket->addHistory("UNASSIGNED:", "-");
                        $ticket->setUpdatedAt();
                        return $ticket;
                    }
            }
        throw new DomainException("Ticket with ID $id not found");
    }

    public function history(int $id): array
    {
        foreach ($this->tickets as $ticket)
            {
                if ($ticket->getId() === $id)
                    {
                        $history  = $ticket->getHistory();
                        return $history;
                    }
            }
        throw new DomainException("Ticket with ID $id not found");
    }

    public function comment(int $id, string $comment): string
    {
        foreach ($this->tickets as $ticket)
            {
                if ($ticket->getId() === $id)
                    {
                        $ticket->addComment($comment);
                        $ticket->setUpdatedAt();
                        return $comment;
                    }
            }
        throw new DomainException("Ticket with ID $id not found");

    }

    public function searchMine(string $username): array
    {
        $results = [];
        foreach ($this->tickets as $ticket)
            {
                if ($ticket->getAssignee() === $username)
                    {
                        $results[] = "{$ticket->getId()} | {$ticket->getTitle()}";
                    }
            }
        return $results;
        throw new DomainException("No tickets assigned to $username");

    }

    public function searchTitles(string $searchTitle): array
    {
        $resultsTitle = [];
        foreach ($this->tickets as $ticket)
            {
                if ($ticket->getTitle() === $searchTitle)
                    {
                        $resultsTitle[] = "{$ticket->getId()} | {$ticket->getTitle()}";
                    }
            }
        return $resultsTitle;
        throw new DomainException("No tickets with title $searchTitle");

    }    
}