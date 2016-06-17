<?php

namespace Abacaphiliac\DoctrineORMDiagnosticsModule;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\DependencyIndicatorInterface;

class Module implements ConfigProviderInterface, DependencyIndicatorInterface
{
    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return require dirname(dirname(__DIR__)) . '/config/module.config.php';
    }

    /**
     * Expected to return an array of modules on which the current one depends on
     *
     * @return array
     */
    public function getModuleDependencies()
    {
        return [
            'DoctrineORMModule',
            'ZFTool',
        ];
    }
}
