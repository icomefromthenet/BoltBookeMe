<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Seed;

use DateTime;
use RuntimeException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;


class NewBookingSeed extends BaseSeed
{
    
   
    protected $aBookings;
    
    
    protected function updateSchedules()
    {
        $oDatabase              = $this->getDatabase();
        $aTableNames            = $this->getTableNames();
        $sScheduleSlotTableName = $aTableNames['bm_schedule_slot'];
        $sBookingTableName      = $aTableNames['bm_booking'];
        
         # Step 3 Update the schedule with a booking
        
        $aCreateBookSql[] = " UPDATE $sScheduleSlotTableName  a";
        $aCreateBookSql[] = " INNER JOIN ".$sBookingTableName." b ON `b`.`schedule_id` = `a`.`schedule_id` " ;
        $aCreateBookSql[] = " SET  `a`.`booking_id` = `b`.`booking_id` ";
        $aCreateBookSql[] = " WHERE `a`.`slot_open` >= `b`.`slot_open` AND `a`.`slot_close` <= `b`.`slot_close` ";
          
        $sCreateBookSql = implode(PHP_EOL,$aCreateBookSql);
        
        $oDatabase->executeUpdate($sCreateBookSql,[],[]);
        
    }
    
    
    protected function createBooking($iScheduleId, DateTime $oOpenDate, DateTime $oCloseDate)
    {
        $oDatabase         = $this->getDatabase();
        $aTableNames       = $this->getTableNames();
        $sBookingTableName = $aTableNames['bm_booking'];
         
        $aTakeBookSql[] = " INSERT INTO $sBookingTableName (`booking_id`,`schedule_id`,`slot_open`,`slot_close`,`registered_date`) ";
        $aTakeBookSql[] = " VALUES (NULL, :iScheduleId, :oSlotOpen, :oSlotClose, NOW()) ";
        
        $sTakeBookSql = implode(PHP_EOL, $aTakeBookSql);
      
        $oDateType = Type::getType(Type::DATETIME);
        $oIntType  = Type::getType(Type::INTEGER);
        
        $aParams = [
            ':iScheduleId'  => $iScheduleId,
            ':oSlotOpen'    => $oOpenDate,
            ':oSlotClose' => $oCloseDate,
            
        ];
        
        $aTypes = [
            ':iScheduleId'  => $oIntType,
            ':oSlotOpen'    => $oDateType,
            ':oSlotClose' => $oDateType,
           
        ];
        
        
        $oDatabase->executeUpdate($sTakeBookSql, $aParams, $aTypes);
        
        $iBookingId = $oDatabase->lastInsertId();
        
        
        
        if(empty($iBookingId)) {
           throw new RuntimeException('Unable to insert booking into database');
        }
	       
	        
	    return $iBookingId;    
             
    }
   
    
    
    protected function doExecuteSeed()
    {
        $aNewBookings = [];
        
        foreach($this->aBookings as $sKey => $aBooking) {
           $aNewBookings[$sKey] = $this->createBooking($aBooking['SCHEDULE_ID'], $aBooking['SLOT_OPEN'], $aBooking['SLOT_CLOSE']);
            
        }
        
        $this->updateSchedules();
        
        return $aNewBookings;
    }
    
    
    public function __construct(Connection $oDatabase, array $aTableNames, array $aBookings)
    {
       
        parent::__construct($oDatabase, $aTableNames);
        
       
        $this->aBookings = $aBookings;
   
    }
    
    
}
/* End of Class */
