<?php

namespace Eightfold\Clip;

class Command
{
    private $command = '';

    private $extra = '';

    private $mode = 'r';

    static public function open(string $openingCommand)
    {
        return new Command($openingCommand);
    }

    public function __construct(string $openingCommand)
    {
        $this->command = $openingCommand;
    }

    public function extra(string $extra)
    {
        $this->extra = $extra;
        return $this;
    }

    public function close(string $workingPath = '.')
    {
        $proc = proc_open("{$this->command} {$this->extra}",
            [
                ['pipe', 'r'],
                ['pipe', 'w'],
                ['pipe', 'w']
            ],
            $pipes);

        $success = stream_get_contents($pipes[1]);
        $possibleError = stream_get_contents($pipes[2]);

        proc_close($proc);

        if (strlen($possibleError) > 0) {
            return (object) [
                'type' => 'error',
                'content' => $possibleError
            ];
        }

        return (object) [
            'type' => 'ok',
            'content' => $success
        ];
    }
}
