<?php

namespace Cms\Command;

use Mmi\Command\CommandAbstract;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CronExecuteCommand extends CommandAbstract
{

    public function configure()
    {
        $this->setName('cms:cron:execute');
        $this->setDescription('Execute cron jobs');
        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return integer
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        \Cms\Model\Cron::run();
        return 0;
    }

}