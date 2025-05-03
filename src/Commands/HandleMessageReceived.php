<?php

namespace DirectoryTree\ImapEngine\Laravel\Commands;

use DirectoryTree\ImapEngine\Laravel\Events\MessageReceived;
use DirectoryTree\ImapEngine\MessageInterface;
use Illuminate\Support\Facades\Event;

class HandleMessageReceived
{
    /**
     * Constructor.
     */
    public function __construct(
        protected WatchMailbox $command
    ) {}

    /**
     * Handle the message received event.
     */
    public function __invoke(MessageInterface $message): void
    {
        $this->command->info(
            "Message received: [{$message->uid()}]"
        );

        Event::dispatch(new MessageReceived($message));
    }
}
