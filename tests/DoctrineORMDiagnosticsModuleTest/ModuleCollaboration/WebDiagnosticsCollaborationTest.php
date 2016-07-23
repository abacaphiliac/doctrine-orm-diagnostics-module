<?php

namespace AbacaphiliacTest\DoctrineORMDiagnosticsModuleTest\ModuleCollaboration;

use Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Zend\Http\PhpEnvironment\Request;
use Zend\Json\Json;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\ResponseInterface;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class WebDiagnosticsCollaborationTest extends AbstractHttpControllerTestCase
{
    /** @var Application */
    private $cli;

    public function setUp()
    {
        parent::setUp();
        
        \PHPUnit_Framework_Error_Deprecated::$enabled = false;
        
        $this->setApplicationConfig([
            'modules' => [
                'DoctrineModule',
                'DoctrineORMModule',
                'ZFTool',
                'Abacaphiliac\DoctrineORMDiagnosticsModule',
            ],
            'module_listener_options' => [
                'config_static_paths' => [
                    __DIR__ . '/test.config.php',
                ],
                'module_paths' => [],
            ],
        ]);
        
        $serviceLocator = $this->getApplicationServiceLocator();
        \PHPUnit_Framework_Assert::assertInstanceOf(ServiceLocatorInterface::class, $serviceLocator);

        $this->cli = $serviceLocator->get('doctrine.cli');
        \PHPUnit_Framework_Assert::assertInstanceOf(Application::class, $this->cli);
    }
    
    public function testDiagnosticsRouteReturnsJsonWithoutAnyFailures()
    {
        $this->dispatchDiagnostics(200);
        
        $response = $this->getResponse();
        \PHPUnit_Framework_Assert::assertInstanceOf(ResponseInterface::class, $response);
        
        $encoded = $response->getContent();
        \PHPUnit_Framework_Assert::assertNotEmpty($encoded);
        
        $decoded = Json::decode($encoded);
        $encodedPretty = json_encode($decoded, JSON_PRETTY_PRINT);
        \PHPUnit_Framework_Assert::assertAttributeEquals(true, 'passed', $decoded, $encodedPretty);
        \PHPUnit_Framework_Assert::assertObjectHasAttribute('details', $decoded, $encodedPretty);
        $details = $decoded->details;
        
        \PHPUnit_Framework_Assert::assertObjectHasAttribute(
            'DoctrineORMDiagnosticsModule: Database Connection',
            $details,
            $encodedPretty
        );
        
        \PHPUnit_Framework_Assert::assertObjectHasAttribute(
            'DoctrineORMDiagnosticsModule: ORM Info',
            $details,
            $encodedPretty
        );
    }
    
    public function testDiagnosticsRouteWithMigrationsUpToDate()
    {
        $this->mergeConfigFile(__DIR__ . '/../../../config/migrations_schema.global.php.dist');

        $this->dispatchDiagnostics(200);
        
        $response = $this->getResponse();
        \PHPUnit_Framework_Assert::assertInstanceOf(ResponseInterface::class, $response);
        
        $encoded = $response->getContent();
        \PHPUnit_Framework_Assert::assertNotEmpty($encoded);
        
        $decoded = Json::decode($encoded);
        $encodedPretty = json_encode($decoded, JSON_PRETTY_PRINT);
        \PHPUnit_Framework_Assert::assertAttributeEquals(true, 'passed', $decoded, $encodedPretty);
        \PHPUnit_Framework_Assert::assertObjectHasAttribute('details', $decoded, $encodedPretty);
        $details = $decoded->details;
        
        \PHPUnit_Framework_Assert::assertObjectHasAttribute(
            'DoctrineORMDiagnosticsModule: Schema Migrations Up-To-Date',
            $details,
            $encodedPretty
        );
    }
    
    public function testDiagnosticsRouteWithOrmSchemaValidation()
    {
        $this->updateSchema();

        $this->mergeConfigFile(__DIR__ . '/../../../config/orm_schema.global.php.dist');
        
        $this->dispatchDiagnostics(200);
        
        $response = $this->getResponse();
        \PHPUnit_Framework_Assert::assertInstanceOf(ResponseInterface::class, $response);
        
        $encoded = $response->getContent();
        \PHPUnit_Framework_Assert::assertNotEmpty($encoded);
        
        $decoded = Json::decode($encoded);
        $encodedPretty = json_encode($decoded, JSON_PRETTY_PRINT);
        \PHPUnit_Framework_Assert::assertAttributeEquals(true, 'passed', $decoded, $encodedPretty);
        \PHPUnit_Framework_Assert::assertObjectHasAttribute('details', $decoded, $encodedPretty);
        $details = $decoded->details;
        
        \PHPUnit_Framework_Assert::assertObjectHasAttribute(
            'DoctrineORMDiagnosticsModule: ORM Schema Valid',
            $details,
            $encodedPretty
        );
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        $request = parent::getRequest();
        \PHPUnit_Framework_Assert::assertInstanceOf(Request::class, $request);

        return $request;
    }

    /**
     * @param $name
     * @param $value
     * @return void
     */
    private function addHeader($name, $value)
    {
        $request = $this->getRequest();
        $headers = $request->getHeaders();
        $headers->addHeaderLine($name, $value);
    }

    /**
     * @param int $statusCode
     * @return void
     */
    private function dispatchDiagnostics($statusCode = 200)
    {
        $this->addHeader('Accept', 'application/json');
        
        $this->dispatch('/diagnostics', 'GET', [], true);
        
        $this->assertResponseStatusCode($statusCode);
    }

    private function updateSchema()
    {
        $command = $this->cli->get('orm:schema-tool:update');
        \PHPUnit_Framework_Assert::assertInstanceOf(UpdateCommand::class, $command);
        
        $definition = $command->getDefinition();
        $input = new ArrayInput(['--force' => true], $definition);
        $output = new NullOutput();
        
        $exitCode = $command->run($input, $output);
        \PHPUnit_Framework_Assert::assertSame(0, $exitCode);
    }

    /**
     * @param $file
     * @return void
     */
    private function mergeConfigFile($file)
    {
        $override = require $file;
        \PHPUnit_Framework_Assert::assertInternalType('array', $override);
        
        $serviceManager = $this->getApplicationServiceLocator();
        \PHPUnit_Framework_Assert::assertInstanceOf(ServiceManager::class, $serviceManager);
        
        $allowOverride = $serviceManager->getAllowOverride();
        $serviceManager->setAllowOverride(true);
        
        $config = $serviceManager->get('config');
        \PHPUnit_Framework_Assert::assertInternalType('array', $config);
        
        $serviceManager->setService('config', ArrayUtils::merge($config, $override));
        
        $serviceManager->setAllowOverride($allowOverride);
    }
}
