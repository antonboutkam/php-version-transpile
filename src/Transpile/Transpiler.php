<?php
namespace Hurah\Transpile;

use Hurah\Transpile\Exceptions\FileNotFoundException;
use Hurah\Transpile\Exceptions\InvalidArgumentException;

final class Transpiler
{
    const PHP7_4 = 1;
    const PHP7_3 = 0;

    /**
     * @param string $sFile
     * @param int $mode
     * @return string
     * @throws InvalidArgumentException
     */
    public function transpileString(string $sFileContents, int $mode = 0):string
    {
        if(empty($sFileContents))
        {
            throw new InvalidArgumentException("File seems to be empty");
        }
        if($mode === self::PHP7_3)
        {
            return $this->to7_3($sFileContents);
        }
        else if($mode === self::PHP7_4)
        {
            return $this->to7_4($sFileContents);
        }
        throw new InvalidArgumentException("Mode must be one of Transpiler::PHP7_4, Transpiler::PHP7_3");

    }

    /**
     * @param string $sFile
     * @param int $mode
     * @return string
     * @throws FileNotFoundException
     * @throws InvalidArgumentException
     */
    public function transpileFile(string $sFile, int $mode = 0):string
    {
        if(!preg_match('/\.php$/', $sFile))
        {
            throw new InvalidArgumentException("Only php files are supported");
        }
        else if(!file_exists($sFile))
        {
            throw new FileNotFoundException("File not found " . $sFile);
        }

        $sFileContents = file_get_contents($sFile);
        return $this->transpileString($sFileContents, $mode);
    }
    private function to7_4(string $sFileContent):string
    {
        preg_match_all('/(?<full>(?<modifiers>((public|private|protected|static|final|abstract)\s)+)\/\*(?<type>[a-zA-Z0-9_]+)\*\/\s(?<var>\$.+);)/', $sFileContent, $aMatches);

        $sOutFile = $sFileContent;
        if(isset($aMatches['full']) && count($aMatches['full']))
        {
            foreach ($aMatches['full'] as $iIndex => $sMatch)
            {
                $sReplacement = $aMatches['modifiers'][$iIndex] . $aMatches['type'][$iIndex] . ' ' .  $aMatches['var'][$iIndex].';';
                $sOutFile = str_replace($sMatch, $sReplacement, $sOutFile);
            }
        }
        return $sOutFile;
    }
    private function to7_3(string $sFileContent):string
    {
        // ?[a-zA-Z0-9_]+\$[a-zA-Z0-9_]+;
        preg_match_all('/(?<full>(?<modifiers>((public|private|protected|static|final|abstract)\s)+)(?<type>[a-zA-Z0-9_]+)\s(?<var>\$.+);)/', $sFileContent, $aMatches);

        $sOutFile = $sFileContent;
        if(isset($aMatches['full']) && count($aMatches['full']))
        {
            foreach ($aMatches['full'] as $iIndex => $sMatch)
            {
                if(!in_array($aMatches['type'][$iIndex], ['static', 'final', 'abstract', 'public', 'protected', 'private']))
                {
                    $sReplacement = $aMatches['modifiers'][$iIndex] . '/*' . $aMatches['type'][$iIndex] . '*/ ' .  $aMatches['var'][$iIndex].';';
                    $sOutFile = str_replace($sMatch, $sReplacement, $sOutFile);
                }
            }
        }
        return $sOutFile;
    }
}
