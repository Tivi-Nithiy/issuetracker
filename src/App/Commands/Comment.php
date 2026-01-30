<?php

namespace App\Commands;

use Domain\TicketManager;
use Domain\DomainException;
use App\DispatchResult;

final class Comment implements Command
{
    public function name():string
    {
        return "comment";
    }

    public function help(): string
    {
        return "comment         -add comment";
    }

    public function run(TicketManager $ticketManager, array $args): DispatchResult
    {
        if (count($args) < 2) {
            throw new DomainException("comment requires: id, comment");
        }
        $id = $args[0];
        $comment = implode(' ', array_slice($args, 1));
        $ticketManager->comment($id, $comment);
        return new DispatchResult("Comment added");
    }
}