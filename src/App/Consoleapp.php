<?php

namespace App;

use Domain\TicketManager;
use Domain\DomainException;

final class ConsoleApp
{
    public function __construct(
        private TicketManager $ticket_manager,
        private CommandRouter $router
    )
    {
    }

    public function run(): void
    {
        $this->println('Welcome to this issue tracker. Type "help" for commands. Type "quit" to exit.');

        while (true) {
            $this->print("> ");
            $line = fgets(STDIN);

            if ($line === false) {
                $this->println("\nEOF. Exiting.");
                return;
            }

            $line = trim($line);
            if ($line === '') {
                continue;
            }

            try {
                $result = $this->router->dispatch($line, $this->ticket_manager);

                if ($result->shouldQuit) {
                    $this->println("Bye.");
                    return;
                }

                if ($result->output !== '') {
                    $this->println($result->output);
                }
            } catch (DomainException $e) {
                $this->println("ERR: " . $e->getMessage());
            } catch (\Throwable $t) {
                // Keep REPL alive on unexpected crashes.
                $this->println("CRASH: " . $t->getMessage());
            }
        }

    }

    private function print(string $s): void
    {
        fwrite(STDOUT, $s);
    }

    private function println(string $s): void
    {
        fwrite(STDOUT, $s . PHP_EOL);
    }
}