<?php
namespace Hurah\Transpile;

use Psr\Log\LoggerInterface;

final class TranspileRunner
{
    private $oLogger;
    private $sRootSrc;
    private $sRootDest;
    private $iPhpVersion;

    public function __construct(LoggerInterface $oLogger, string $sRootSrc = '../src', string $sRootDest = '../dist', int $iToPhpversion = Transpiler::PHP7_4)
    {
        $this->sRootDest = $sRootDest;
        $this->sRootSrc = $sRootSrc;
        $this->oLogger = $oLogger;
        $this->iPhpVersion = $iToPhpversion;
    }
    public function getLogger():LoggerInterface
    {
        return $this->oLogger;
    }
    private function makeFileDir(string $sFilePath)
    {
        $sDirName = dirname($sFilePath);
        if(!is_dir($sDirName))
        {
            $this->getLogger()->info("Make dir $sDirName");
            mkdir($sDirName, 0777, true);
        }
    }
    private function saveFile(string $sFile, string $sFileContent)
    {
        $this->getLogger()->info("Saving $sFile");
        file_put_contents($sFile, $sFileContent);
    }
    private function runRecursive(string $sSrc = null):void
    {
        foreach (new \DirectoryIterator($sSrc) as $fileInfo) {
            if($fileInfo->isDot())
            {
                continue;
            }
            if($fileInfo->isDir())
            {
                $this->runRecursive($fileInfo->getPathname());
                continue;
            }
            $sSourceFileName = $fileInfo->getPathname();
            $sDestinationFileName = str_replace($this->sRootSrc, $this->sRootDest, $sSourceFileName);
            $this->makeFileDir($sDestinationFileName);
            $sDestinationFileContent = $this->compile($sSourceFileName, $sDestinationFileName);

            $this->saveFile($sDestinationFileName, $sDestinationFileContent);
        }
    }
    private function compile(string $sSourceFileName, string $sDestFileName):string
    {
        $oCompiler = new Transpiler();
        $this->getLogger()->info("Transpiling $sSourceFileName");
        return $oCompiler->transpileFile($sSourceFileName, $this->iPhpVersion);
    }

    public function run():void
    {
        $this->runRecursive($this->sRootSrc, $this->sRootDest);
        $this->getLogger()->info("All done");
    }
}
