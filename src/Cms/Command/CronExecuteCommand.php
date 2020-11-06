<?php

namespace Cms\Command;

use Mmi\Command\CommandAbstract;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CronExecuteCommand extends CommandAbstract
{

    /**
     * Execute
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        \Cms\Model\Cron::run();
        return 0;
    }

}