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
    
    protected $oDatabase;
    
    protected $aTableNames;
    
    public function __construct($oDatabase, DateTime $oNow, array $aTableNames)
    {
        $this->oDatabase   = $oDatabase;
        $this->oNow        = $oNow;
        $this->aTableNames = $aTableNames;
    }
    
    
    public function getNow()
    {
        return $this->oNow;
    }
    
    
    public function getDatabaseAdapter()
    {
        return $this->oDatabase;
    }
    
    
   
    
    public function getTableNames()
    {
        return $this->aTableNames;
    }
    
}
/* End of Class */