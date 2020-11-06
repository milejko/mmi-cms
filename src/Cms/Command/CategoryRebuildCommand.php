<?php

namespace Cms\Command;

use Cms\Orm\CmsCategoryQuery;
use Mmi\Command\CommandAbstract;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CategoryRebuildCommand extends CommandAbstract
{
    const TEMP_NAME_SUFFIX = '#suffix#';

    /**
     * Execute
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        //doklejanie suffixa
        foreach ((new CmsCategoryQuery)
            ->whereParentId()->equals(null)->find() as $mainCategory) {
            $mainCategory->name .= self::TEMP_NAME_SUFFIX;
            $mainCategory->save();
            $mainCategory->name = str_replace(self::TEMP_NAME_SUFFIX, '', $mainCategory->name);
            $mainCategory->save();
        }
        $output->writeln('Category uris and paths rebuilt successfully.');
        return 0;
    }

}