<?php

namespace DirectoryTree\ImapEngine\Laravel\Support;

class Loop implements LoopInterface
{
    /**
     * {@inheritDoc}
     */
    public function run(callable $tick): void
    {
        while (true) {
            $tick();
        }
    }
}
