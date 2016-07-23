<?php

namespace Abacaphiliac\DoctrineORMDiagnosticsModuleTest;

use Abacaphiliac\DoctrineORMDiagnosticsModule\AbstractCheckCommandFactory;
use Abacaphiliac\DoctrineORMDiagnosticsModule\CheckCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Abacaphiliac\DoctrineORMDiagnosticsModule\AbstractCheckCommandFactory
 */
class AbstractCheckCommandFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var ServiceManager */
    private $serviceLocator;
    
    /** @var \PHPUnit_Framework_MockObject_MockObject|AbstractCheckCommandFactory */
    private $sut;

    protected function setUp()
    {
        parent::setUp();

        $this->serviceLocator = new ServiceManager();
        
        $this->sut = $this->getMockForAbstractClass(AbstractCheckCommandFactory::class);
    }
    
    public function testCreateService()
    {
        $this->sut->method('getCommandServiceName')->willReturn('FooBar');
        
        $this->serviceLocator->setService('doctrine.cli', new Application());
        $this->serviceLocator->setService('FooBar', new Command('FooBar'));

        $actual = $this->sut->createService($this->serviceLocator);
        
        self::assertInstanceOf(CheckCommand::class, $actual);
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testNotCreateServiceDueToInvalidCommandType()
    {
        $this->sut->method('getCommandServiceName')->willReturn('FooBar');

        $this->serviceLocator->setService('doctrine.cli', new Application());
        $this->serviceLocator->setService('FooBar', new \stdClass());

        $this->sut->createService($this->serviceLocator);
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testNotCreateServiceDueToInvalidCommandLineInterface()
    {
        $this->serviceLocator->setService('doctrine.cli', new \stdClass());

        $this->sut->createService($this->serviceLocator);
    }
}
