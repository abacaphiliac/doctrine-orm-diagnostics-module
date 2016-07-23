<?php

namespace Abacaphiliac\DoctrineORMDiagnosticsModule\CheckMigrations;

use Abacaphiliac\DoctrineORMDiagnosticsModule\AbstractCheckCommandFactory;

class CheckMigrationsUpToDateFactory extends AbstractCheckCommandFactory
{
    /**
     * @return string
     */
    public function getCommandServiceName()
    {
        return 'doctrine.migrations_cmd.uptodate';
    }
}
