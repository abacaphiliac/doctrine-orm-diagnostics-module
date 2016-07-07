<?php

namespace Abacaphiliac\DoctrineORMDiagnosticsModule;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\DependencyIndicatorInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Module implements ConfigProviderInterface, DependencyIndicatorInterface, InitProviderInterface
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
            'DoctrineModule',
            'DoctrineORMModule',
            'ZFTool',
        ];
    }
    
    /**
     * @param ModuleManagerInterface $manager
     */
    public function init(ModuleManagerInterface $manager)
    {
        $events = $manager->getEventManager();
        $sharedEventManager = $events->getSharedManager();
        $sharedEventManager->attach('doctrine', 'loadCli.post', [$this, 'initializeConsole']);
    }

    /**
     * Initialize the console with additional commands from (optionally) doctrine\migrations
     * @param EventInterface $event
     * @return bool
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function initializeConsole(EventInterface $event)
    {
        $cli = $event->getTarget();
        if (!$cli instanceof Application) {
            return false;
        }
        
        $serviceLocator = $event->getParam('ServiceManager');
        if (!$serviceLocator instanceof ServiceLocatorInterface) {
            return false;
        }

        if (class_exists('Doctrine\DBAL\Migrations\Tools\Console\Command\UpToDateCommand')) {
            /** @var Command $upToDateCommand */
            $upToDateCommand = $serviceLocator->get('doctrine.migrations_cmd.uptodate');
            
            $cli->addCommands([
                $upToDateCommand,
            ]);
        }
        
        return true;
    }
}
