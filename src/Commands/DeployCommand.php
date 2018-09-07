<?php

namespace mrcrmn\Webhook\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeployCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deploys the application and auto runs several scripts.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Prepends the artisan down command and
     * appends artisan up to the commands array.
     *
     * @param array $commands
     *
     * @return array
     */
    protected function putInMaintenanceMode($commands)
    {
        array_unshift($commands, 'php artisan down');
        array_push($commands, 'php artisan up');

        return $commands;
    }

    /**
     * Changes the path to the root laravel folder.
     *
     * @return void
     */
    protected function changePath()
    {
        chdir(base_path());
    }

    /**
     * Executes a command and logs its output.
     *
     * @param string $command
     *
     * @return void
     */
    protected function executeCommand($command)
    {
        Log::info('Running Command \'' . $command . '\'');
        exec($command, $output, $status);
        Log::info($output);

        return $status;
    }

    /**
     * Setup command execution.
     *
     * @param array $commands
     *
     * @return void
     */
    protected function executeCommands($commands)
    {
        Log::info("Deployment started.\n");
        $this->changePath();

        foreach ($commands as $command) {
            $status = $this->executeCommand($command);

            if ($status > 0) {
                Log::error("Command '".$command. "' failed with status code ".$status.". Check Logs for console output. Aborting.");
                return false;
            }
        }

        return true;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $commands = config('webhook.commands');

        if (config('webhook.maintenance-mode')) {
            $commands = $this->putInMaintenanceMode($commands);
        }

        if ($this->executeCommands($commands)) {
            $message = "Deployent successful";
            Log::info($message . "\n");
        } else {
            $message = "Deployent failed";
            Log::error($message . "\n");
        }

        return $message;
    }
}
