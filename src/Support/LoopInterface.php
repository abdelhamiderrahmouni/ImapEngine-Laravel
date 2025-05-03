<?php

namespace DirectoryTree\ImapEngine\Laravel\Support;

interface LoopInterface
{
    /**
     * Execute the loop.
     */
    public function run(callable $tick): void;
}
