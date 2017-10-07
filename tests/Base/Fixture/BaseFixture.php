<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Fixture;

use DateTime;
use Doctrine\DBAL\Types\Type;
use Bolt\Tests\BoltUnitTest;
use Bolt\Extension\IComeFromTheNet\BookMe\BookMeExtension;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\BookMeService;
use Bolt\Application;
use Bolt\Configuration\Standard;
use Cocur\Slugify\Slugify;


abstract class BaseFixture
{
    
    protected $oNow;
    
    protected $oBookMeApi;
    
    protected $oDatabase;
    
    
    
    public function __construct($oDatabase, $oBookMeApi, DateTime $oNow)
    {
        $this->oDatabase = $oDatabase;
        $this->oBookMeApi = $oBookMeApi;
        $this->oNow       = $oNow;
        
    }
    
    
    public function getNow()
    {
        return $this->oNow;
    }
    
    
    public function getDatabaseAdapter()
    {
        return $this->oDatabase;
    }
    
    
    public function getTestAPI()
    {
        return $this->oBookMeApi;
    }
    
    
}
/* End of Class */