<?php

return [
    'service_manager' => [
        'factories' => [
            'doctrine.orm_diagnostics.connection' =>
                \Abacaphiliac\DoctrineORMDiagnosticsModule\CheckConnectionFactory::class,
            'doctrine.orm_diagnostics.info' =>
                \Abacaphiliac\DoctrineORMDiagnosticsModule\CheckOrm\CheckOrmInfoFactory::class,
            'doctrine.orm_diagnostics.schema' =>
                \Abacaphiliac\DoctrineORMDiagnosticsModule\CheckSchemaFactory::class,
            'doctrine.migrations_diagnostics.uptodate' =>
                \Abacaphiliac\DoctrineORMDiagnosticsModule\CheckMigrations\CheckMigrationsUpToDateFactory::class,
        ],
    ],
    'diagnostics' => [
        'DoctrineORMDiagnosticsModule' => [
            'Database Connection' => 'doctrine.orm_diagnostics.connection',
            'ORM Info' => 'doctrine.orm_diagnostics.info',
        ],
    ],
    'doctrine' => [
        'migrations_cmd' => [
            'uptodate' => [], // TODO Remove this once it has been added to doctrine/doctrine-orm-module.
        ],
    ],
];
