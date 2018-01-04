<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Seed;

use DateTime;
use RuntimeException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;

class VoucherNumberSeed extends BaseSeed
{
    
   
   protected $aVoucherNumbers;
    
    
    
    
    protected function createVoucherNumber($iVoucherInstaneId, $iVoucherTypeId, $sVoucherCode)
    {
        $oDatabase          = $this->getDatabase();
        $aTableNames        = $this->getTableNames();
        $iRowsAffected      = 0;
        $sVoucherTable    =  $aTableNames['bm_voucher_instance'];
        
        
        $aSql            = [];
        $sSql            = '';
        $aBinds          = [
            ':iVoucherInstanceId' => $iVoucherInstaneId,
            ':iVoucherTypeId'     => $iVoucherTypeId,
            ':sVoucherCode'       => $sVoucherCode, 
      
        ];
        
        
        $aTypes = [
            ':iVoucherInstanceId'  => TYPE::getType(TYPE::INTEGER),
            ':iVoucherTypeId'      => TYPE::getType(TYPE::INTEGER),
            ':sVoucherCode'        => TYPE::getType(TYPE::STRING), 
        ];

        $aSql[] = "INSERT INTO $sVoucherTable (`voucher_instance_id`, `voucher_type_id`, `voucher_code`, `date_created`) ";
        $aSql[] =" VALUES ( :iVoucherInstanceId, :iVoucherTypeId, :sVoucherCode, NOW() ) ";
     
           
        $sSql1 =  implode(PHP_EOL,$aSql);
        $iRowsAffected = $oDatabase->executeUpdate($sSql1,$aBinds,$aTypes);
        
        if($iRowsAffected == 0) {
            throw new RuntimeException('Unable to create voucher numbers ');
        }
         
             
    }
   
    
    
    protected function doExecuteSeed()
    {
        
        foreach($this->aVoucherNumbers as $sKey => $aVoucher) {
            $this->createVoucherNumber(
                $aVoucher['VOUCHER_INSTANCE_ID'],
                $aVoucher['VOUCHER_TYPE_ID'],
                $aVoucher['VOUCHER_CODE']
            );
            
        }
        
        $oDatabase          = $this->getDatabase();
        $aTableNames        = $this->getTableNames();
     
        $sVoucherRuleTable  = $aTableNames['bm_voucher_gen_rule'];
        
        $oDatabase->executeUpdate("UPDATE $sVoucherRuleTable set `voucher_sequence_no` = voucher_sequence_no +10 WHERE 1=1 ");
        
    }
    
    
    public function __construct(Connection $oDatabase, array $aTableNames, array $aVoucherNumbers)
    {
       
        parent::__construct($oDatabase, $aTableNames);
        
       
        $this->aVoucherNumbers = $aVoucherNumbers;
   
    }
    
    
    
}
/* End of Class */
