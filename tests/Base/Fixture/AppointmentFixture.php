<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Fixture;

use DateTime;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Seed\NewBookingSeed;

class AppointmentFixture extends BaseFixture
{
 
 
    
 
    
    public function runFixture(array $aAppConfig, DateTime $oNow)
    {
        
        $oDatabase   = $this->getDatabaseAdapter();
        $aTableNames = $this->getTableNames();
    
        $iMemberOneSchedule = $aAppConfig['schedule_member_one'];
      
        
        $oOpenOne  =  clone $oNow;
        $oOpenOne->setDate($oNow->format('Y'),1,14);
        $oOpenOne->setTime(17,0,0);
        
        $oCloseOne = clone $oNow;
        $oCloseOne->setDate($oNow->format('Y'),1,14);
        $oCloseOne->setTime(17,20,0);
        
        
        $oOpenTwo  =  clone $oNow;
        $oOpenTwo->setDate($oNow->format('Y'),2,13);
        $oOpenTwo->setTime(13,0,0);
        
        $oCloseTwo = clone $oNow;
        $oCloseTwo->setDate($oNow->format('Y'),2,13);
        $oCloseTwo->setTime(13,20,0);
        
        
        
        $aBookings = [
          'BOOKING_ONE' => [
                'SCHEDULE_ID' => $iMemberOneSchedule,
                'SLOT_OPEN'   => $oOpenOne, 
                'SLOT_CLOSE'  => $oCloseOne,
            ], 
            'BOOKING_TWO' => [
                'SCHEDULE_ID' => $iMemberOneSchedule,
                'SLOT_OPEN'   => $oOpenTwo, 
                'SLOT_CLOSE'  => $oCloseTwo,
            ],
            
        ];
        
        
        $oBookingSeed = new NewBookingSeed($oDatabase, $aTableNames, $aBookings);
        $aNewBooking     = $oBookingSeed->executeSeed();
        
        
        $aAppointments = [
           'APPT_ONE' => [
                'APPOINTMENT_ID' => '',
                'BOOKING_ID'     => $aNewBooking['BOOKING_ONE'],
                'CUSTOMER_ID'   => '',
                'APPT_NO'       => '',
                'USER_ID'       => '',
                'EXTERNAL_GUID' => '',
            ],
            'APPT_TWO' => [
                'APPOINTMENT_ID' => '',
                'BOOKING_ID'     => $aNewBooking['BOOKING_TWO'],
                'CUSTOMER_ID'   => '',
                'APPT_NO'       => '',
                'USER_ID'       => '',
                'EXTERNAL_GUID' => '',
            ]  
        ];
            
        return $aAppointments; 
      
    }
    
    
    
    
    
}
/* End of Class */