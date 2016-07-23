<?php

namespace Abacaphiliac\DoctrineORMDiagnosticsModuleTest;

use Abacaphiliac\DoctrineORMDiagnosticsModule\CheckCommand;
use Abacaphiliac\DoctrineORMDiagnosticsModule\CheckSchemaFactory;
use Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand;
use Symfony\Component\Console\Application;
use Zend\Console\Request;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Abacaphiliac\DoctrineORMDiagnosticsModule\CheckSchemaFactory
 * @covers \Abacaphiliac\DoctrineORMDiagnosticsModule\AbstractCheckCommandFactory
 */
class CheckSchemaFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var Request */
    private $request;
    
    /** @var \PHPUnit_Framework_MockObject_MockObject|ValidateSchemaCommand */
    private $command;
    
    /** @var ServiceManager */
    private $serviceLocator;
    
    /** @var CheckSchemaFactory */
    private $sut;

    protected function setUp()
    {
        parent::setUp();

        $this->serviceLocator = new ServiceManager();
        
        $this->command = $this->getMockBuilder(ValidateSchemaCommand::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->request = new Request();
        
        $this->sut = new CheckSchemaFactory();
    }
    
    public function testCreateService()
    {
        $this->serviceLocator->setService('doctrine.cli', new Application());
        $this->serviceLocator->setService('doctrine.orm_cmd.validate_schema', $this->command);
        $this->serviceLocator->setService('Request', $this->request);
        
        $actual = $this->sut->createService($this->serviceLocator);
        
        self::assertInstanceOf(CheckCommand::class, $actual);
    }
}
