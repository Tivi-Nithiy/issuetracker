<?php

require_once __DIR__ . '/../src/Domain/DomainException.php';
require_once __DIR__ . '/../src/Domain/Ticket.php';
require_once __DIR__ . '/../src/Domain/TicketManager.php';
require_once __DIR__ . '/../src/Domain/TicketProgress.php';

require_once __DIR__ . '/../src/Infrastructure/TicketRepository.php';

require_once __DIR__ . '/../src/App/Commands/Command.php';
require_once __DIR__ . '/../src/App/Commands/Assign.php';
require_once __DIR__ . '/../src/App/Commands/Block.php';
require_once __DIR__ . '/../src/App/Commands/Cancel.php';
require_once __DIR__ . '/../src/App/Commands/Comment.php';
require_once __DIR__ . '/../src/App/Commands/Done.php';
require_once __DIR__ . '/../src/App/Commands/Help.php';
require_once __DIR__ . '/../src/App/Commands/History.php';
require_once __DIR__ . '/../src/App/Commands/List.php';
require_once __DIR__ . '/../src/App/Commands/Mine.php';
require_once __DIR__ . '/../src/App/Commands/NewTicket.php';
require_once __DIR__ . '/../src/App/Commands/Quit.php';
require_once __DIR__ . '/../src/App/Commands/Search.php';
require_once __DIR__ . '/../src/App/Commands/Show.php';
require_once __DIR__ . '/../src/App/Commands/Start.php';
require_once __DIR__ . '/../src/App/Commands/Unassign.php';
require_once __DIR__ . '/../src/App/Commands/Unblock.php';

require_once __DIR__ . '/../src/App/CommandRouter.php';
require_once __DIR__ . '/../src/App/ConsoleApp.php';

use App\ConsoleApp;
use App\CommandRouter;
use Domain\TicketManager;
use Domain\Ticket;
use Infrastructure\TicketRepository;

$ticketManager = new TicketManager();
$router = new CommandRouter();
$app = new ConsoleApp($ticketManager, $router);#


$app->run();