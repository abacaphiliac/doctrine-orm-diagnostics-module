<?php

namespace Abacaphiliac\DoctrineORMDiagnosticsModule;

class CheckSchemaFactory extends AbstractCheckCommandFactory
{
    /**
     * @return string
     */
    public function getCommandServiceName()
    {
        return 'doctrine.orm_cmd.validate_schema';
    }
}
