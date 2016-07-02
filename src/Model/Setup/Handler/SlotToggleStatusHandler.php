<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\Handler;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Setup\Command\SlotToggleStatusCommand;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\SetupException;


/**
 * Used to toggle a slot enabled/disabled flag
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class SlotToggleStatusHandler 
{
    
    /**
     * @var array   a map internal table names to external names
     */ 
    protected $aTableNames;
    
    /**
     * @var Doctrine\DBAL\Connection    the database adpater
     */ 
    protected $oDatabaseAdapter;
    
    
    
    public function __construct(array $aTableNames, Connection $oDatabaseAdapter)
    {
        $this->oDatabaseAdapter = $oDatabaseAdapter;
        $this->aTableNames      = $aTableNames;
        
        
    }
    
    
    public function handle(SlotToggleStatusCommand $oCommand)
    {
        $oDatabase              = $this->oDatabaseAdapter;
        $sTimeSlotTableName     = $this->aTableNames['bm_timeslot'];
        $iTimeSlotId            = $oCommand->getTimeSlotId();
        $aSql                   = [];
        
        
        $aSql[] = " UPDATE $sTimeSlotTableName  ";
        $aSql[] = " SET  is_active_slot = IF(is_active_slot = true,false,true)";
        $aSql[] = " WHERE timeslot_id = ? ";
             
        
        $sSql = implode(PHP_EOL,$aSql);
        
        
        try {
	    
	        $oIntType = Type::getType(Type::INTEGER);
	    
	        $oDatabase->executeUpdate($sSql, [$iTimeSlotId], [$oIntType]);
                 
	    }
	    catch(DBALException $e) {
	        throw SetupException::hasFailedToToggleStatus($oCommand, $e);
	    }
        
        
        return true;
    }
     
    
}
/* End of File */