<?php

namespace Domain;

enum TicketProgress: string
{
    case NEW = 'NEW';
    case IN_PROGRESS = "IN PROGRESS";
    case BLOCKED = "BLOCKED";
    case CANCELLED = "CANCELLED";
    case DONE = "DONE";
}