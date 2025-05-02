<?php

namespace DirectoryTree\ImapEngine\Laravel\Events;

use DirectoryTree\ImapEngine\Message;

class MessageReceived
{
    /**
     * Create a new event instance.
     */
    public function __construct(
        public Message $message
    ) {}
}
