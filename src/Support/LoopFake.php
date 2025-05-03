<?php

namespace DirectoryTree\ImapEngine\Laravel\Support;

class LoopFake implements LoopInterface
{
    /**
     * {@inheritDoc}
     */
    public function run(callable $tick): void
    {
        $tick();
    }
}
