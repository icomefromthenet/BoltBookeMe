<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Fixture;

use DateTime;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Seed\NewBookingSeed;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Seed\NewApptSeed;

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
                'APPOINTMENT_ID' => 1,
                'BOOKING_ID'     => $aNewBooking['BOOKING_ONE'],
                'CUSTOMER_ID'   =>  $aAppConfig['customer_1'],
                'APPT_NO'       => 'A001',
                'USER_ID'       => '1',
                'EXTERNAL_GUID' => '59d0b996-80e1-48c9-8b32-aff8c5a1ca75',
                'INSTRUCTIONS'  => 'First Job Instruction',
                'STATUS_CODE'   => 'A',
            ],
            'APPT_TWO' => [
                'APPOINTMENT_ID' => 2,
                'BOOKING_ID'     => $aNewBooking['BOOKING_TWO'],
                'CUSTOMER_ID'   =>  $aAppConfig['customer_1'],
                'APPT_NO'       => 'A002',
                'USER_ID'       => '2',
                'EXTERNAL_GUID' => '49e2b8f7-a3c5-4488-a4ee-f91343436fa2',
                'INSTRUCTIONS'  => 'Second Job Instruction',
                'STATUS_CODE'   => 'A',
            ],
            'APPT_THREE' => [
                'APPOINTMENT_ID' => 3,
                'BOOKING_ID'     => null, // no booking
                'CUSTOMER_ID'   => $aAppConfig['customer_2'],
                'APPT_NO'       => 'A003',
                'USER_ID'       => '3',
                'EXTERNAL_GUID' => 'a38f6823-c61f-4ab4-8aab-929af8554cfe',
                'INSTRUCTIONS'  => 'Third Job Instruction',
                'STATUS_CODE'   => 'W',
            ]  
        ];
        
        
        $oNewApptSeed = new NewApptSeed($oDatabase, $aTableNames, $aAppointments);
        $oNewApptSeed->executeSeed();
        
            
        return $aAppointments; 
      
    }
    
    
    
    
    
}
/* End of Class */