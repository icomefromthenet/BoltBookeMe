<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Fixture;

use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Seed\CalendarSeed;


class CalendarFixture extends BaseFixture
{
 
    
    public function runFixture(array $aAppConfig)
    {
      
        $oDatabase   = $this->getDatabaseAdapter();
        $aTableNames = $this->getTableNames();
      
        $iStartYear =  $this->getNow()->format('Y');
        $iEndYear   = $iStartYear +1; 
         
        $oCalendarSeed = new CalendarSeed($oDatabase, $aTableNames, $iStartYear, $iEndYear );
      
        return $oCalendarSeed->executeSeed();
        
    }
    
}
/* End of Class */