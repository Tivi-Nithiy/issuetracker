<?php
namespace App;

use App\Commands\Command;
use App\Commands\Assign;
use App\Commands\Block;
use App\Commands\Cancel;
use App\Commands\Comment;
use App\Commands\Done;
use App\Commands\Help;
use App\Commands\History;
use App\Commands\ListAll;
use App\Commands\Mine;
use App\Commands\NewTicket;
use App\Commands\Search;
use App\Commands\Quit;
use App\Commands\Show;
use App\Commands\Start;
use App\Commands\Unassign;
use App\Commands\Unblock;
use Domain\DomainException;
use Domain\TicketManager;

final class CommandRouter
{
    /** @var array<string, Command> */
    private array $commands = [];

    public function __construct()
    {
        $this->register(new Assign());
        $this->register(new Block());
        $this->register(new Cancel());
        $this->register(new Comment());
        $this->register(new Done());
        $this->register(new History());
        $this->register(new ListAll());
        $this->register(new Mine());
        $this->register(new NewTicket());
        $this->register(new Search());
        $this->register(new Show());
        $this->register(new Start());
        $this->register(new Unassign());
        $this->register(new Unblock());

        // Fully implemented utility commands
        $this->register(new Quit());
        $this->register(new Help(fn() => $this->all()));
    }

    public function register(Command $command): void
    {
        $this->commands[$command->name()] = $command;
    }

    /** @return Command[] */
    public function all(): array
    {
        return array_values($this->commands);
    }

    public function dispatch(string $line, TicketManager $system): DispatchResult
    {
        [$verb, $args] = $this->parse($line);

        $command = $this->commands[$verb] ?? null;
        if ($command === null) {
            throw new DomainException("Unknown command '{$verb}'. Try: help");
        }

        return $command->run($system, $args);
    }

    /**
     * @return array{0:string,1:list<string>}
     */
    private function parse(string $line): array
    {
        $parts = str_getcsv($line, ' ', '"');
        $verb = strtolower((string)($parts[0] ?? ''));

        if ($verb === '') {
            throw new DomainException("Empty command. Try: help");
        }

        /** @var list<string> $args */
        $args = array_values(array_slice($parts, 1));
        return [$verb, $args];
    }
}

final class DispatchResult
{
    public function __construct(
        public string $output = '',
        public bool   $shouldQuit = false,
    )
    {
    }

    public static function quit(string $message = ''): self
    {
        return new self($message, true);
    }
}
