<?php

namespace Trivia;

class EchoLogger implements Logger
{
    public function log(string $msg)
    {
        echo $msg . "\n";
    }
}