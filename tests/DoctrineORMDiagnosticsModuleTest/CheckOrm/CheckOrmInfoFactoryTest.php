<?php

namespace Abacaphiliac\DoctrineORMDiagnosticsModuleTest;

use Abacaphiliac\DoctrineORMDiagnosticsModule\CheckCommand;
use Abacaphiliac\DoctrineORMDiagnosticsModule\CheckOrm\CheckOrmInfoFactory;
use Doctrine\ORM\Tools\Console\Command\InfoCommand;
use Symfony\Component\Console\Application;
use Zend\Console\Request;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Abacaphiliac\DoctrineORMDiagnosticsModule\CheckOrm\CheckOrmInfoFactory
 * @covers \Abacaphiliac\DoctrineORMDiagnosticsModule\AbstractCheckCommandFactory
 */
class CheckOrmInfoFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var Request */
    private $request;
    
    /** @var \PHPUnit_Framework_MockObject_MockObject|InfoCommand */
    private $command;
    
    /** @var ServiceManager */
    private $serviceLocator;
    
    /** @var CheckOrmInfoFactory */
    private $sut;

    protected function setUp()
    {
        parent::setUp();

        $this->serviceLocator = new ServiceManager();
        
        $this->command = $this->getMockBuilder(InfoCommand::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->request = new Request();
        
        $this->sut = new CheckOrmInfoFactory();
    }
    
    public function testCreateService()
    {
        $this->serviceLocator->setService('doctrine.cli', new Application());
        $this->serviceLocator->setService('doctrine.orm_cmd.info', $this->command);
        $this->serviceLocator->setService('Request', $this->request);
        
        $actual = $this->sut->createService($this->serviceLocator);
        
        self::assertInstanceOf(CheckCommand::class, $actual);
    }
}
