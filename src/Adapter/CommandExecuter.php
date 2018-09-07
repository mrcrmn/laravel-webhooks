<?php

namespace mrcrmn\Webhook\Adapter;

class CommandExecuter
{
    protected $commands = [];

    protected $output = [];

    public function __construct($commands)
    {
        $this->commands = $commands;
    }

    public function execute()
    {
        foreach ($this->commands as $command) {
            exec($command, $output);

            foreach ($output as $line) {
                $this->output[] = $line;
            }
        }

        return $this->output;
    }
}