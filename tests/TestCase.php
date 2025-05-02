<?php

namespace DirectoryTree\ImapEngine\Laravel\Tests;

use DirectoryTree\ImapEngine\Laravel\ImapServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            ImapServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
    }
}
