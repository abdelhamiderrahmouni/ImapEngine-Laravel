<?php

namespace DirectoryTree\ImapEngine\Laravel\Events;

use Carbon\Carbon;
use Exception;

class MailboxWatchAttemptsExceeded
{
    /**
     * Constructor.
     */
    public function __construct(
        public string $mailbox,
        public int $attempts,
        public Exception $exception,
        public ?Carbon $lastReceivedAt = null,
    ) {}
}
