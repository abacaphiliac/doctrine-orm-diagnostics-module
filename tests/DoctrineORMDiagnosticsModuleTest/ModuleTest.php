<?php

namespace Abacaphiliac\DoctrineORMDiagnosticsModuleTest;

use Abacaphiliac\DoctrineORMDiagnosticsModule\Module;

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
        
        self::assertContains('DoctrineORMModule', $actual);
        self::assertContains('ZFTool', $actual);
    }
}
