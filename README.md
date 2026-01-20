# CLI Ticketing System (OO Follow-Up Project)

## Goal
Build a small, interactive CLI ticketing system that reinforces **object-oriented design in PHP** with **minimal scaffolding**. The CLI is just a simple REPL loop; the focus is the **domain model**: tickets, state transitions, history, and rules.

You should be able to run it like:

```bash
php bin/tickets.php
````

…and then type commands until `quit`.

---

## Constraints (important)

### Allowed

* Plain PHP (8.1+ recommended for enums, readonly, etc.)
* A simple `while(true)` REPL loop reading stdin and writing stdout
* Storing state in memory only **OR** persisting to a single JSON file (optional stretch)

### Not allowed / avoid

* No frameworks
* No databases
* No ORMs
* No “utility” functions that do domain work (e.g. `closeTicket($ticket)`); behavior should live on objects

### OO rules of the game

* No public mutable properties on domain objects
* All domain state changes must happen through methods (encapsulation)
* Illegal operations must fail clearly (exceptions or error result objects)

---

## Feature set (MVP)

You are building a *tiny issue tracker*.

### Entities

* **Ticket**
* **Comment**
* **User** (can be minimal: just a username string)
* **TicketRepository** (in-memory for MVP)

### Ticket fields (minimum)

* `id` (int)
* `title` (string)
* `description` (string, optional)
* `status` (see lifecycle below)
* `assignee` (nullable username)
* `createdAt` (DateTimeImmutable or timestamp)
* `updatedAt` (DateTimeImmutable or timestamp)
* `comments` (list of Comment)
* `history` (list of events / changes)

---

## Ticket lifecycle (state machine)

Use statuses that force meaningful transitions:

* `NEW`
* `IN_PROGRESS`
* `BLOCKED`
* `DONE`
* `CANCELLED`

### Allowed transitions

* `NEW` → `IN_PROGRESS`
* `NEW` → `CANCELLED`
* `IN_PROGRESS` → `BLOCKED`
* `IN_PROGRESS` → `DONE`
* `IN_PROGRESS` → `CANCELLED`
* `BLOCKED` → `IN_PROGRESS`
* `BLOCKED` → `CANCELLED`

### Disallowed examples

* `DONE` → anything
* `CANCELLED` → anything
* `NEW` → `DONE` (must go via IN_PROGRESS)

**Rule:** all transitions must be enforced inside the Ticket object (not in the CLI).

---

## CLI Commands (MVP)

All commands are single-word verbs with simple args. Keep parsing minimal: split on whitespace, then handle quoted strings for title/description if you want (or implement a simple `"` parser).

### Required commands

* `new "<title>" ["<description>"]`
  * Creates a ticket with status `NEW`
  * Prints: `created #<id>`
* `list`
  * Prints a concise table of tickets: `id | status | assignee | title`
* `show <id>`
  * Prints full detail: status, title, description, assignee, timestamps, last N comments, history summary
* `assign <id> <username>`
  * Assigns ticket (allowed in any non-terminal state)
* `unassign <id>`
* `start <id>`
  * `NEW → IN_PROGRESS`
* `block <id> "<reason>"`
  * `IN_PROGRESS → BLOCKED` and records the reason in history
* `unblock <id>`
  * `BLOCKED → IN_PROGRESS`
* `done <id>`
  * `IN_PROGRESS → DONE`
* `cancel <id> "<reason>"`
  * Allowed from NEW/IN_PROGRESS/BLOCKED → CANCELLED and records reason
* `comment <id> "<text>"`
  * Adds a comment; must be blocked for terminal tickets? (choose a rule and enforce it)
* `history <id>`
  * Prints change history (status changes, assignment changes, cancel reason, etc.)
* `help`
* `quit`

### Optional quality-of-life commands (pick 1–2)

* `mine <username>` (list tickets assigned to user)
* `search "<text>"` (search in titles)
* `next` (suggest a ticket to work on: e.g. oldest NEW or IN_PROGRESS)

---

## Output expectations

Keep output consistent and human-readable. Example:

```
> new "Login broken" "500 error on /auth"
created #1

> list
1 | NEW         | -     | Login broken

> assign 1 alice
assigned #1 to alice

> start 1
#1 now IN_PROGRESS

> block 1 "Waiting on vendor API key"
#1 now BLOCKED

> history 1
- [2026-01-20 12:30] CREATED: "Login broken"
- [2026-01-20 12:31] ASSIGNED: alice
- [2026-01-20 12:32] STATUS: NEW -> IN_PROGRESS
- [2026-01-20 12:33] STATUS: IN_PROGRESS -> BLOCKED (Waiting on vendor API key)
```

---

## Suggested (not mandatory) class map

This is intentionally light guidance—design it.

### Domain

* `Ticket`
  * methods: `assign()`, `unassign()`, `start()`, `block()`, `unblock()`, `done()`, `cancel()`, `addComment()`
  * enforces transition rules
* `TicketStatus` (enum)
* `TicketId` (optional value object)
* `Comment`
* `HistoryEvent` (value object)
* `Clock` (optional interface for testability; can use system time directly for MVP)

### Application

* `TicketService` (optional)
  * coordinates repository + ticket operations
  * **must not** contain rules that belong on Ticket
* `TicketRepository` interface
* `InMemoryTicketRepository`

### CLI

* `ConsoleApp` (REPL loop)
* `CommandHandler` (optional: switch statement is OK)

---

## Error handling rules

All invalid actions must produce a clear error message:

* Unknown command
* Unknown ticket id
* Invalid transition (e.g. `done` on NEW)
* Invalid input format

You can implement errors either as:

* Exceptions (`DomainException`) caught by CLI, or
* Result objects (e.g. `CommandResult{ok, message}`)

Pick one approach and keep it consistent.

---

## Milestones (recommended)

### Milestone 1: Core ticket + list/show

* `new`, `list`, `show`, `help`, `quit`
* Ticket stores status + timestamps

### Milestone 2: Lifecycle transitions

* `start`, `done`, `cancel`
* Enforce transition rules on the Ticket

### Milestone 3: Assignment + history

* `assign`, `unassign`, `history`
* Every significant change records a HistoryEvent

### Milestone 4: Block/unblock + comments

* `block`, `unblock`, `comment`
* Decide rule: allow comments on DONE/CANCELLED or not

### Milestone 5 (stretch): Persistence

* Save/load to a single JSON file
* Keep domain free of JSON logic (repository handles serialization)

---

## Test scenarios (acceptance)

Implement these as manual scripts or PHPUnit tests.

1. Create ticket → status NEW
2. Start NEW → IN_PROGRESS works
3. Done NEW fails (must start first)
4. Block IN_PROGRESS works with reason captured
5. Unblock BLOCKED → IN_PROGRESS works
6. Cancel from NEW works; further transitions fail
7. Assign/unassign works; assignment change recorded in history
8. Unknown id errors cleanly
9. `list` shows terminal tickets too (or decide filter behavior and document it)
10. `history` ordering is chronological and includes status changes

---

## Design “gotchas” to avoid

* Don’t let CLI mutate ticket internals directly
* Don’t represent the ticket as a loose array
* Don’t implement transitions as `if` chains in the CLI
* Avoid “TicketService does everything” — services coordinate, entities enforce rules

---

## Stretch goals (choose 1)

* Persistence to JSON file
* `search` or `mine` command
* Add `PRIORITY` (LOW/MED/HIGH) with rules (e.g. DONE tickets can’t change priority)
* Add `labels` with add/remove commands
* Add a `WorkflowPolicy` strategy so lifecycle rules can be swapped

---

## Deliverables

* A runnable CLI script
* Clean domain model with encapsulated rules
* Basic help output documenting commands
* A short README describing how to run and 2–3 example sessions
