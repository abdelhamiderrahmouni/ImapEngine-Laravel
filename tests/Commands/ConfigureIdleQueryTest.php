<?php

use DirectoryTree\ImapEngine\Laravel\Commands\ConfigureIdleQuery;
use DirectoryTree\ImapEngine\MessageQueryInterface;
use DirectoryTree\ImapEngine\Testing\FakeFolder;

test('it does nothing when "with" is empty', function () {
    $folder = new FakeFolder;

    $configure = new ConfigureIdleQuery;

    $query = $configure($folder->messages());

    expect($query)->toBeInstanceOf(MessageQueryInterface::class);
});

test('it configures query when "with" is present', function () {
    $folder = new FakeFolder;

    $configure = new ConfigureIdleQuery([
        'flags', 'body', 'headers',
    ]);

    $query = $configure($folder->messages());

    expect($query->isFetchingBody())->toBeTrue();
    expect($query->isFetchingFlags())->toBeTrue();
    expect($query->isFetchingHeaders())->toBeTrue();
});
