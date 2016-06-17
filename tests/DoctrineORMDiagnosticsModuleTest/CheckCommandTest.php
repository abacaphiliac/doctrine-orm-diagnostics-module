<?php

namespace Abacaphiliac\DoctrineORMDiagnosticsModuleTest;

use Abacaphiliac\DoctrineORMDiagnosticsModule\CheckCommand;
use DoctrineModule\Component\Console\Output\PropertyOutput;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ZendDiagnostics\Result\Failure;
use ZendDiagnostics\Result\Success;

/**
 * @covers \Abacaphiliac\DoctrineORMDiagnosticsModule\CheckCommand
 */
class CheckCommandTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject|Command */
    private $command;
    
    /** @var \PHPUnit_Framework_MockObject_MockObject|InputInterface */
    private $input;
    
    /** @var \PHPUnit_Framework_MockObject_MockObject|OutputInterface */
    private $output;
    
    /** @var CheckCommand */
    private $sut;

    protected function setUp()
    {
        parent::setUp();

        $this->command = $this->getMockBuilder(Command::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->input = $this->getMockBuilder(InputInterface::class)->getMock();

        $this->output = $this->getMockBuilder(OutputInterface::class)->getMock();
        
        $this->sut = new CheckCommand($this->command, $this->input, $this->output);
    }
    
    public function testCheckCommandSuccessful()
    {
        $this->command->method('run')->willReturn(0);
        
        $actual = $this->sut->check();
        
        self::assertInstanceOf(Success::class, $actual);
    }
    
    public function testCheckCommandFailed()
    {
        $this->command->method('run')->willReturn(1);
        
        $actual = $this->sut->check();
        
        self::assertInstanceOf(Failure::class, $actual);
    }
    
    public function testCheckWithPropertyOutput()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|PropertyOutput $output */
        $output = $this->getMockBuilder(PropertyOutput::class)->getMock();
        
        $output->method('getMessage')
            ->willReturn("Foo\nBar\n");
        
        $this->command->method('run')->willReturn(1);
        
        $actual = (new CheckCommand($this->command, $this->input, $output))->check();
        
        self::assertInternalType('array', $actual->getData());
        self::assertContains('Foo', $actual->getData());
        self::assertContains('Bar', $actual->getData());
    }
}
