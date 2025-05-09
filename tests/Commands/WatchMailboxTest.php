<?php

namespace DirectoryTree\ImapEngine\Laravel\Tests;

use DirectoryTree\ImapEngine\Laravel\Commands\WatchMailbox;
use DirectoryTree\ImapEngine\Laravel\Events\MessageReceived;
use DirectoryTree\ImapEngine\Laravel\Facades\Imap;
use DirectoryTree\ImapEngine\Laravel\Support\LoopFake;
use DirectoryTree\ImapEngine\Laravel\Support\LoopInterface;
use DirectoryTree\ImapEngine\Testing\FakeFolder;
use DirectoryTree\ImapEngine\Testing\FakeMessage;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use InvalidArgumentException;

use function Pest\Laravel\artisan;

it('throws exception when mailbox is not configured', function () {
    artisan(WatchMailbox::class, ['mailbox' => 'invalid']);
})->throws(
    InvalidArgumentException::class,
    'Mailbox [invalid] is not defined. Please check your IMAP configuration.'
);

it('can watch mailbox', function () {
    Config::set('imap.mailboxes.test', [
        'host' => 'localhost',
        'port' => 993,
        'encryption' => 'ssl',
        'username' => '',
        'password' => '',
    ]);

    Imap::fake('test', folders: [
        new FakeFolder('inbox', messages: [
            $message = new FakeMessage(uid: 1),
        ]),
    ]);

    App::bind(LoopInterface::class, LoopFake::class);

    Event::fake();

    artisan(WatchMailbox::class, ['mailbox' => 'test'])->assertSuccessful();

    Event::assertDispatched(
        fn (MessageReceived $event) => $event->message->is($message)
    );
});
