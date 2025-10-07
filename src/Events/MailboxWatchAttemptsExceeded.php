<?php

namespace DirectoryTree\ImapEngine\Laravel\Events;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
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
        public null|Carbon|CarbonImmutable $lastReceivedAt = null,
    ) {}
}
