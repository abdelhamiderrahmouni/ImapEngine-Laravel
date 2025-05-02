<?php

namespace DirectoryTree\ImapEngine\Laravel\Commands;

use DirectoryTree\ImapEngine\Laravel\Events\MessageReceived;
use DirectoryTree\ImapEngine\Laravel\Facades\Imap;
use DirectoryTree\ImapEngine\Message;
use DirectoryTree\ImapEngine\MessageQuery;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;

class WatchMailbox extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'imap:watch {mailbox} {folder?} {--with=} {--timeout=30} {--debug=false}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Watch a mailbox for new messages.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $mailbox = Imap::mailbox($name = $this->argument('mailbox'));

        $with = explode(',', $this->option('with'));

        $this->info("Watching mailbox $name...");

        $attempts = 0;

        while (true) {
            try {
                $inbox = ($folder = $this->argument('folder'))
                    ? $mailbox->folders()->findOrFail($folder)
                    : $mailbox->inbox();

                $attempts = 0;

                $inbox->idle(function (Message $message) {
                    $this->info("Message received: [{$message->uid()}]");

                    Event::dispatch(new MessageReceived($message));
                }, function (MessageQuery $query) use ($with) {
                    if (in_array('flags', $with)) {
                        $query->withFlags();
                    }

                    if (in_array('body', $with)) {
                        $query->withBody();
                    }

                    if (in_array('headers', $with)) {
                        $query->withHeaders();
                    }

                    return $query;
                }, $this->option('timeout'));
            } catch (Exception $e) {
                if ($this->isMessageMissing($e)) {
                    continue;
                }

                if ($this->isDisconnection($e)) {
                    sleep(2);

                    continue;
                }

                if ($attempts >= 5) {
                    $this->info("Exception: {$e->getMessage()}");

                    throw $e;
                }

                $attempts++;
            }
        }
    }

    /**
     * Determine if the exception is due to a message missing error.
     */
    protected function isMessageMissing(Exception $e): bool
    {
        return Str::contains($e->getMessage(), [
            'no longer exist',
        ], true);
    }

    /**
     * Determine if the exception is caused by a disconnection.
     */
    protected function isDisconnection(Exception $e): bool
    {
        return Str::contains($e->getMessage(), [
            'connection reset by peer',
            'temporary system problem',
            'failed to fetch content',
            'connection failed',
            'empty response',
            'not connected',
            'no response',
            'broken pipe',
            'unavailable',
        ], true);
    }
}
