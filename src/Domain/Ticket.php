<?php

namespace Domain;

use Domain\TicketProgress;
use Domain\DomainException;
use App\DispatchResult;


final class Ticket {
    private static int $nextId = 1;

    private int $id;
    private string $title;
    private string $description = '';
    private TicketProgress $status;
    private string $assignee;
    private string $createdAt;
    private string $updatedAt;
    private array $comments;
    private array $history;
    
    public function __construct($title, $description)
    {
        $this->id =  self:: $nextId++;
        $this->title = $title;
        $this->description = $description;
        $this->status = TicketProgress:: NEW;
        $this->assignee = '-';
        $this->createdAt = '';
        $this->updatedAt = '';
        $this->comments = [];
        $this->history = [];
    }

    public function getId():int
    {
        return $this->id;
    }
    public function getTitle():string
    {
        return $this->title;
    }
    public function getStatus():TicketProgress
    {
        return $this->status;
    }

    public function getDescription(): string{
        return $this->description;
    }

    public function getAssignee(): string{
        return $this->assignee;
    }

    public function getUpdatedAt(): string{
        return $this->updatedAt;
    }

    public function getComments(): array{
        return $this->comments;
    }


    public function getHistory(): array{
        return $this->history;
    }

    public function addHistory(string $event, string $explain): void{
        $DateTime = date("Y-m-d H:i");
        $this->history[] = "[$DateTime] | $event $explain";
    }

    public function setAssignee(string $username): void{
        $this->assignee = $username;
    }

    public function setCreatedAt(): void{
        $this->createdAt = date("Y-m-d H:i");
        //return $this->createdAt;
    }

    public function setUpdatedAt(): void{
        $this->updatedAt = date("Y-m-d H:i");;
    }

    public function addComment(string $comment): void{
        $this->comments[] = $comment;
    }


    public function statusInProgress(): void 
    {
        if (($this->status === TicketProgress:: DONE) || ($this->status === TicketProgress:: CANCELLED) || ($this->status === TicketProgress:: BLOCKED)){
            throw new DomainException("Cannot change status of this ticket");
        }
        else if ($this->status === TicketProgress:: IN_PROGRESS){
            throw new DomainException("Status already set to IN PROGRESS");
        }
        $this->status = TicketProgress:: IN_PROGRESS;
    }

    public function statusCancelled(): void 
    {
        if (($this->status === TicketProgress:: NEW) || ($this->status === TicketProgress:: DONE)){
            throw new DomainException("Cannot change status of this ticket");
        }else if ($this->status === TicketProgress:: CANCELLED){
            throw new DomainException("Status already set to CANCELLED");
        }
        $this->status = TicketProgress:: CANCELLED;
    }

    public function statusBlocked(): void 
    {
        if ($this->status != TicketProgress:: IN_PROGRESS){
            throw new DomainException("Cannot change status of this ticket");
        }else if ($this->status === TicketProgress:: BLOCKED){
            throw new DomainException("Status already set to BLOCKED");
        }
        $this->status = TicketProgress:: BLOCKED;
    }

    public function statusUnblock(): void 
    {
        if ($this->status != TicketProgress:: BLOCKED){
            throw new DomainException("Cannot change status of this ticket");
        }
        $this->status = TicketProgress:: IN_PROGRESS;
    }

    public function statusDone(): void 
    {
        if (($this->status === TicketProgress:: NEW) || ($this->status === TicketProgress:: BLOCKED) || ($this->status === TicketProgress:: CANCELLED)){
            throw new DomainException("Cannot change status of this ticket");
        }
        else if ($this->status === TicketProgress:: DONE){
            throw new DomainException("Status already set to DONE");
        }
        $this->status = TicketProgress:: DONE;
    }
}