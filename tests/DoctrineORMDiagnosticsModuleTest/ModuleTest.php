<?php

namespace Abacaphiliac\DoctrineORMDiagnosticsModuleTest;

use Abacaphiliac\DoctrineORMDiagnosticsModule\Module;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Zend\EventManager\Event;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Abacaphiliac\DoctrineORMDiagnosticsModule\Module
 */
class ModuleTest extends \PHPUnit_Framework_TestCase
{
    /** @var Module */
    private $sut;

    protected function setUp()
    {
        parent::setUp();

        $this->sut = new Module();
    }
    
    public function testGetConfig()
    {
        $actual = $this->sut->getConfig();
        
        self::assertInternalType('array', unserialize(serialize($actual)));
    }
    
    public function testGetModuleDependencies()
    {
        $actual = $this->sut->getModuleDependencies();
        
        self::assertContains('DoctrineModule', $actual);
        self::assertContains('DoctrineORMModule', $actual);
        self::assertContains('ZFTool', $actual);
    }
    
    public function testAddUpToDateCommandToSymfonyConsole()
    {
        $cli = new Application();
        self::assertFalse($cli->has($name = 'migrations:up-to-date'));
        
        $command = new Command($name);
        
        $serviceManager = new ServiceManager();
        $serviceManager->setService('doctrine.migrations_cmd.uptodate', $command);

        $event = new Event(__METHOD__, $cli, [
            'ServiceManager' => $serviceManager,
        ]);

        $actual = $this->sut->initializeConsole($event);

        self::assertTrue($actual);
        
        self::assertSame($command, $cli->get($name));
    }
    
    public function testNotAddUpToDateCommandToInvalidSymfonyConsole()
    {
        $cli = null;
        
        $serviceManager = new ServiceManager();
        
        $event = new Event(__METHOD__, $cli, [
            'ServiceManager' => $serviceManager,
        ]);
        
        $actual = $this->sut->initializeConsole($event);
        
        self::assertFalse($actual);
    }
    
    public function testNotAddUpToDateCommandToInvalidServiceManager()
    {
        $cli = new Application();
        
        $event = new Event(__METHOD__, $cli, [
            
        ]);

        $actual = $this->sut->initializeConsole($event);

        self::assertFalse($actual);
    }
}
