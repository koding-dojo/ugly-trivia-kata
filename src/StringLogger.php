<?php

namespace Trivia;

class StringLogger implements Logger
{
    private string $buffer = '';

    public function log(string $msg)
    {
        $this->buffer .= $msg . "\n";
    }

    public function flush()
    {
        $output = $this->buffer;
        $buffer = '';
        return $output;
    }
}