<?php

namespace App\Commands;

use Domain\TicketManager;
use App\DispatchResult;

/**
 * Fully implemented.
 * Prints help for all registered commands.
 */
final class Help implements Command
{
    /**
     * @param callable():array<int,Command> $commandProvider
     */
    public function __construct(private $commandProvider)
    {
    }

    public function name(): string
    {
        return 'help';
    }

    public function help(): string
    {
        return "help            - show this help";
    }

    public function run(TicketManager $ticketManager, array $args): DispatchResult
    {
        $commands = ($this->commandProvider)();

        $lines = ["Commands:"];
        foreach ($commands as $cmd) {
            $lines[] = "  " . $cmd->help();
        }

        return new DispatchResult(implode(PHP_EOL, $lines));
    }
}