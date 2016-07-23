<?php

namespace Abacaphiliac\DoctrineORMDiagnosticsModule\CheckOrm;

use Abacaphiliac\DoctrineORMDiagnosticsModule\AbstractCheckCommandFactory;

class CheckOrmInfoFactory extends AbstractCheckCommandFactory
{
    /**
     * @return string
     */
    public function getCommandServiceName()
    {
        return 'doctrine.orm_cmd.info';
    }
}
