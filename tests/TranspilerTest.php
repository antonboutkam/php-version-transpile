<?php
namespace Test;

use Hurah\Transpile\Transpiler;
use PHPUnit\Framework\TestCase;

class TranspilerTest extends TestCase
{
    protected $php7_3file;
    protected $php7_4file;
    protected $php7_3content;
    protected $php7_4content;

    public function setUp(): void
    {
        $sFixtureDir = __DIR__ . '/Fixtures/';
        $this->php7_3file = $sFixtureDir . 'Mock7_3.php';
        $this->php7_4file = $sFixtureDir . 'Mock7_4.php';

        $this->php7_3content = file_get_contents($this->php7_3file);
        $this->php7_4content = file_get_contents($this->php7_4file);
    }

    public function testTranspileStringTo7_3()
    {
        $oTranspiler = new Transpiler();
        $sPhp7_3_content = $oTranspiler->transpileFile($this->php7_4file, Transpiler::PHP7_3);
        $this->assertTrue($sPhp7_3_content === $this->php7_3content);
    }

    public function testTranspileStringTo7_4()
    {
        $oTranspiler = new Transpiler();
        $sPhp7_4_content = $oTranspiler->transpileString($this->php7_3content, Transpiler::PHP7_4);
        $this->assertTrue($sPhp7_4_content === $this->php7_4content);
    }

    public function testTranspileFileTo7_4()
    {
        $oCompiler = new Transpiler();
        $sPhp7_4_content = $oCompiler->transpileFile($this->php7_3file, Transpiler::PHP7_4);
        $this->assertTrue($sPhp7_4_content === $this->php7_4content);
    }

    public function testTranspileFileTo7_3()
    {
        $oCompiler = new Transpiler();
        $sPhp7_3_content = $oCompiler->transpileString($this->php7_4content, Transpiler::PHP7_3);
        $this->assertTrue($sPhp7_3_content === $this->php7_3content);
    }
}
