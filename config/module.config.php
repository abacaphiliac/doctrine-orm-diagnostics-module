<?php

return array(
    'service_manager' => array(
        'factories' => array(
            'doctrine.orm_diagnostics.connection' =>
                \Abacaphiliac\DoctrineORMDiagnosticsModule\CheckConnectionFactory::class,
            'doctrine.orm_diagnostics.schema' => \Abacaphiliac\DoctrineORMDiagnosticsModule\CheckSchemaFactory::class,
        ),
    ),
    'diagnostics' => array(
        'DoctrineORMDiagnosticsModule' => array(
            'Database Connection' => 'doctrine.orm_diagnostics.connection',
            //'ORM Validate Schema' => 'doctrine.orm_diagnostics.schema',
        ),
    ),
);
