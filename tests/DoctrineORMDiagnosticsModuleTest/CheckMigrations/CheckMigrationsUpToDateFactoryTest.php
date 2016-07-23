<?php

namespace Abacaphiliac\DoctrineORMDiagnosticsModuleTest;

use Abacaphiliac\DoctrineORMDiagnosticsModule\CheckCommand;
use Abacaphiliac\DoctrineORMDiagnosticsModule\CheckMigrations\CheckMigrationsUpToDateFactory;
use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Doctrine\DBAL\Migrations\Tools\Console\Command\UpToDateCommand;
use DoctrineModule\ServiceFactory\AbstractDoctrineServiceFactory;
use DoctrineORMModule\Service\MigrationsCommandFactory;
use Symfony\Component\Console\Application;
use Zend\Console\Request;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers \Abacaphiliac\DoctrineORMDiagnosticsModule\CheckMigrations\CheckMigrationsUpToDateFactory
 * @covers \Abacaphiliac\DoctrineORMDiagnosticsModule\AbstractCheckCommandFactory
 */
class CheckMigrationsUpToDateFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject|Configuration */
    private $migrationsConfiguration;
    
    /** @var Request */
    private $request;
    
    /** @var \PHPUnit_Framework_MockObject_MockObject|UpToDateCommand */
    private $command;
    
    /** @var ServiceManager */
    private $serviceLocator;
    
    /** @var CheckMigrationsUpToDateFactory */
    private $sut;

    protected function setUp()
    {
        parent::setUp();

        $this->serviceLocator = new ServiceManager();
        $this->serviceLocator->addAbstractFactory(AbstractDoctrineServiceFactory::class);
        
        $this->command = $this->getMockBuilder(UpToDateCommand::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->migrationsConfiguration = $this->getMockBuilder(Configuration::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->request = new Request();
        
        $this->sut = new CheckMigrationsUpToDateFactory();
    }
    
    public function testCreateService()
    {
        $this->serviceLocator->setService('doctrine.cli', new Application());
        $this->serviceLocator->setService('doctrine.migrations_cmd.uptodate', $this->command);
        $this->serviceLocator->setService('Request', $this->request);
        
        $actual = $this->sut->createService($this->serviceLocator);
        
        self::assertInstanceOf(CheckCommand::class, $actual);
    }
    
    public function testCreateServiceCollaborationTest()
    {
        $this->serviceLocator->setService('doctrine.cli', new Application());
        $this->serviceLocator->setService('config', [
            'doctrine_factories' => [
                'migrations_cmd' => MigrationsCommandFactory::class,
            ],
            'doctrine' => [
                'migrations_cmd' => [
                    'uptodate' => [],
                ],
            ],
        ]);
        $this->serviceLocator->setService(
            'doctrine.migrations_configuration.orm_default',
            $this->migrationsConfiguration
        );
        $this->serviceLocator->setService('Request', $this->request);
        
        $actual = $this->sut->createService($this->serviceLocator);
        
        self::assertInstanceOf(CheckCommand::class, $actual);
    }
}
