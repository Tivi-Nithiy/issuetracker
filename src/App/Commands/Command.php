<?php

namespace App\Commands;

use Domain\TicketManager;
use App\DispatchResult;

interface Command
{
    public function name(): string;

    public function help(): string;

    /**
     * Execute the command and return output for the console.
     * Commands should NOT echo/print directly.
     */
    public function run(TicketManager $ticketManager, array $args): DispatchResult;
}
