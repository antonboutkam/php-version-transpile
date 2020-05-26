<?php
namespace Test\Mock;

use Hurah\Scraper\Groceries\ScraperFactory;
use Hurah\Scraper\Groceries\IScrapedProduct;
use Hurah\Scraper\Groceries\ScraperOptions;
use phpDocumentor\Reflection\Types\Boolean;

class Mock
{
    public static bool $someBool;
    static public Logger $oLogger;
    static public array $aWhatever = [];
    private IScrapedProduct $fakeResult;
    protected Factory $factory;
    public ScraperOptions $scraperOptions;

    public function __construct()
    {
    }
    public function fakeMethod()
    {
        $oAnotherLogger = new Logger();
    }
    public function anotherFakeMethod(IScrapedProduct $iResult, bool $bSomeArgument = false)
    {

    }
}
