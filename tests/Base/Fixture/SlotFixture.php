<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Fixture;

use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Seed\SlotSeed;


class SlotFixture extends BaseFixture
{
 
    
    public function runFixture(array $aAppConfig)
    {
      
        $oDatabase   = $this->getDatabaseAdapter();
        $aTableNames = $this->getTableNames();
         
        $aTimeSlots = []; 
       
        $oSlotSeed = new SlotFixture($oDatabase, $aTableNames, 5, 1 );
        $aTimeSlots['iFiveMinuteTimeslot'] = $oSlotSeed->executeSeed();
        
        $oSlotSeed = new SlotFixture($oDatabase, $aTableNames, 10, 0 );
        $aTimeSlots['iTenMinuteTimeslot'] = $oSlotSeed->executeSeed();
      
        $oSlotSeed = new SlotFixture($oDatabase, $aTableNames, 7, 1 );
        $aTimeSlots['iSevenMinuteTimeslot'] = $oSlotSeed->executeSeed();
      
        return $aTimeSlots;
        
    }
    
}
/* End of Class */