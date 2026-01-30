<?php

namespace App\Commands;

use Domain\TicketManager;
use App\DispatchResult;

/**
 * Fully implemented.
 */
final class Quit implements Command
{
    public function name(): string
    {
        return 'quit';
    }

    public function help(): string
    {
        return "quit            - exit";
    }

    public function run(TicketManager $ticketManager, array $args): DispatchResult
    {
        return DispatchResult::quit();
    }
}
