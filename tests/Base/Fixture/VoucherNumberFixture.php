<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Fixture;

use DateTime;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Seed\VoucherNumberSeed;


class VoucherNumberFixture extends BaseFixture
{
   
   
   
    
    public function runFixture(array $aAppConfig, DateTime $oNow)
    {
      
        $oDatabase   = $this->getDatabaseAdapter();
        $aTableNames = $this->getTableNames();
      
      
        $aVoucherNumbers =[
           
            // appointment_number
           
            [
                'VOUCHER_INSTANCE_ID' => 1 ,    
                'VOUCHER_TYPE_ID' => 1 ,
                'VOUCHER_CODE' => 'A1001' ,
            ],
            
            [
                'VOUCHER_INSTANCE_ID' => 2 ,    
                'VOUCHER_TYPE_ID' => 1 ,
                'VOUCHER_CODE' => 'A1002' ,
            ],
            
            [
                'VOUCHER_INSTANCE_ID' => 3 ,    
                'VOUCHER_TYPE_ID' => 1 ,
                'VOUCHER_CODE' => 'A1003' ,
            ],
            
            [
                'VOUCHER_INSTANCE_ID' => 4 ,    
                'VOUCHER_TYPE_ID' => 1 ,
                'VOUCHER_CODE' => 'A1004' ,
            ],
            
            // sales_journals
            
            [
                'VOUCHER_INSTANCE_ID' => 5 ,    
                'VOUCHER_TYPE_ID' => 2 ,
                'VOUCHER_CODE' => 'S101' ,
            ],
            
            [
                'VOUCHER_INSTANCE_ID' => 6 ,    
                'VOUCHER_TYPE_ID' => 2 ,
                'VOUCHER_CODE' => 'S102' ,
            ],
            
            [
                'VOUCHER_INSTANCE_ID' => 7 ,    
                'VOUCHER_TYPE_ID' => 2 ,
                'VOUCHER_CODE' => 'S103' ,
            ],
            
            [
                'VOUCHER_INSTANCE_ID' => 8 ,    
                'VOUCHER_TYPE_ID' => 2 ,
                'VOUCHER_CODE' => 'S104' ,
            ],
            
            // payments_journals
            [
                'VOUCHER_INSTANCE_ID' => 9 ,    
                'VOUCHER_TYPE_ID' => 3 ,
                'VOUCHER_CODE' => 'D101' ,
            ],
            
            [
                'VOUCHER_INSTANCE_ID' => 10 ,    
                'VOUCHER_TYPE_ID' => 3 ,
                'VOUCHER_CODE' => 'D102' ,
            ],
            
            [
                'VOUCHER_INSTANCE_ID' => 11 ,    
                'VOUCHER_TYPE_ID' => 3 ,
                'VOUCHER_CODE' => 'D103' ,
            ],
            
            
            [
                'VOUCHER_INSTANCE_ID' => 12 ,    
                'VOUCHER_TYPE_ID' => 3 ,
                'VOUCHER_CODE' => 'D104' ,
            ],
            
            // general_journals
           [
                'VOUCHER_INSTANCE_ID' => 13 ,    
                'VOUCHER_TYPE_ID' => 4 ,
                'VOUCHER_CODE' => 'G101' ,
           ],
           [
                'VOUCHER_INSTANCE_ID' => 14 ,    
                'VOUCHER_TYPE_ID' => 4 ,
                'VOUCHER_CODE' => 'G102' ,
           ],
           [
                'VOUCHER_INSTANCE_ID' => 15 ,    
                'VOUCHER_TYPE_ID' => 4 ,
                'VOUCHER_CODE' => 'G103' ,
           ],
           [
                'VOUCHER_INSTANCE_ID' => 16 ,    
                'VOUCHER_TYPE_ID' => 4 ,
                'VOUCHER_CODE' => 'G104' ,
           ],
           
        ];
        
        $oVoucherSeed = new VoucherNumberSeed($oDatabase, $aTableNames, $aVoucherNumbers);
        $oVoucherSeed->executeSeed();
        
    }
    
   
    
}
/* End of Class */
