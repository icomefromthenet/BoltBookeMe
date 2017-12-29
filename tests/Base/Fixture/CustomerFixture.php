<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Fixture;

use DateTime;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Seed\CustomerSeed;


class CustomerFixture extends BaseFixture
{
 
    
    public function runFixture(array $aAppConfig, DateTime $oNow)
    {
      
        $oDatabase       = $this->getDatabaseAdapter();
        $aTableNames     = $this->getTableNames();
        
        
        $aNewCustomers = [
            'iCustomerOneId' => [
               'FIRST_NAME'     => 'Bob',
               'LAST_NAME'      => 'Builder',
               'EMAIL'          => 'bob@builder.com',
               'MOBILE'         => '0404555555', 
               'LANDLINE'       => '98172762',
               'ADDRESS_ONE'    => 'Bob Address Line One',
               'ADDRESS_TWO'    => 'Bob Address Line Two',
               'COMPANY_NAME'   => 'Company One', 
            ],
            'iCustomerTwoId' => [
               'FIRST_NAME'     => 'Steve',
               'LAST_NAME'      => 'Builder',
               'EMAIL'          => 'seteve@builder.com',
               'MOBILE'         => '0404555556', 
               'LANDLINE'       => '98172762',
               'ADDRESS_ONE'    => 'Steve Address Line One',
               'ADDRESS_TWO'    => 'Steve Address Line Two',
               'COMPANY_NAME'   => 'Company two', 
            ],
            'iCustomerThreeId' => [
               'FIRST_NAME'     => 'Karen',
               'LAST_NAME'      => 'Builder',
               'EMAIL'          => 'karen@builder.com',
               'MOBILE'         => '0404555557', 
               'LANDLINE'       => '98172762',
               'ADDRESS_ONE'    => 'Karen Address Line One',
               'ADDRESS_TWO'    => 'Karen Address Line Two',
               'COMPANY_NAME'   => 'Company three', 
            ]
            
            
        ];
        
        
        $oCustomerSeed = new CustomerSeed($oDatabase, $aTableNames, $aNewCustomers);
      
        return $oCustomerSeed->executeSeed();
        
    }
    
}
/* End of Class */
