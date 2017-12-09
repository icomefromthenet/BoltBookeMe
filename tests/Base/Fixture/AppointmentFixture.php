<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Fixture;


class AppointmentFixture extends BaseFixture
{
 
 
    
 
    
    public function runFixture(array $aAppConfig)
    {
        
        $oNow         = $this->getNow();
        $oService     = $this->getTestAPI();

        
        $iCustomerOneId   = $aAppConfig['CUSTOMER_1'];
        $iCustomerTwoId   = $aAppConfig['CUSTOMER_2'];
        $iCustomerThreeId = $aAppConfig['CUSTOMER_3'];
        
        $oDatabase = $this->getDatabaseAdapter();
        
        // Truncate the Tables
        $aTableNames = $aAppConfig['tablenames'];
        
        $aAppointments = [
          [
              
          ]
          
            
        ];
        
        foreach($aAppointments as $aAppt) {
            $oDatabase->
            
        } 
      
    }
    
    
    
    
    
}
/* End of Class */