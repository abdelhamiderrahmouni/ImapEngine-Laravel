<?php

use DirectoryTree\ImapEngine\Laravel\Commands\HandleMessageReceived;
use DirectoryTree\ImapEngine\Laravel\Commands\WatchMailbox;
use DirectoryTree\ImapEngine\Testing\FakeMessage;

it('dispatches event', function () {
    $command = mock(WatchMailbox::class);

    $command->shouldReceive('info')->once()->with(
        'Message received: [123]'
    );

    $handle = new HandleMessageReceived($command);

    $handle(new FakeMessage(123));
});
