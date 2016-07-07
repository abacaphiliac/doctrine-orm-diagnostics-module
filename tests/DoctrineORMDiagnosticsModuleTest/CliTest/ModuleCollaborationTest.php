<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <http://www.doctrine-project.org>.
 */
namespace Abacaphiliac\DoctrineORMDiagnosticsModuleTest\CliTest;

use Doctrine\DBAL\Migrations\Tools\Console\Command\UpToDateCommand;
use Doctrine\ORM\EntityManager;
use DoctrineORMModuleTest\Util\ServiceManagerFactory;
use Symfony\Component\Console\Application as SymfonyConsoleApplication;
use Zend\EventManager\SharedEventManagerInterface;
use Zend\Mvc\Application;
use Zend\ServiceManager\ServiceLocatorInterface;

class ModuleCollaborationTest extends \PHPUnit_Framework_TestCase
{
    /** @var ServiceLocatorInterface */
    private $serviceLocator;
    
    /** @var SymfonyConsoleApplication */
    protected $cli;

    /** @var EntityManager */
    protected $entityManager;

    /**
     * @return void
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        
        $config = require __DIR__ . '/TestConfiguration.php.dist';
        
        ServiceManagerFactory::setConfig($config);
    }

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->serviceLocator = ServiceManagerFactory::getServiceManager();
        \PHPUnit_Framework_Assert::assertInstanceOf(ServiceLocatorInterface::class, $this->serviceLocator);
        
        /* @var $sharedEventManager SharedEventManagerInterface */
        $sharedEventManager = $this->serviceLocator->get('SharedEventManager');
        \PHPUnit_Framework_Assert::assertInstanceOf(SharedEventManagerInterface::class, $sharedEventManager);
        
        /* @var $application Application */
        $application = $this->serviceLocator->get('Application');
        \PHPUnit_Framework_Assert::assertInstanceOf(Application::class, $application);
        
        $invocations = 0;
        $sharedEventManager->attach('doctrine', 'loadCli.post', function () use (&$invocations) {
            $invocations++;
        });

        $application->bootstrap();
        
        $this->entityManager = $this->serviceLocator->get('doctrine.entitymanager.orm_default');
        \PHPUnit_Framework_Assert::assertInstanceOf(EntityManager::class, $this->entityManager);
        
        $this->cli = $this->serviceLocator->get('doctrine.cli');
        \PHPUnit_Framework_Assert::assertInstanceOf(SymfonyConsoleApplication::class, $this->cli);
        
        self::assertSame(1, $invocations);
    }
    
    public function testValidUpToDateCommandFromServiceLocator()
    {
        $actual = $this->serviceLocator->get('doctrine.migrations_cmd.uptodate');
        
        self::assertInstanceOf(UpToDateCommand::class, $actual);
    }
    
    public function testValidUpToDateCommandFromSymfonyConsole()
    {
        $actual = $this->cli->get('migrations:up-to-date');
        
        self::assertInstanceOf(UpToDateCommand::class, $actual);
    }
}
