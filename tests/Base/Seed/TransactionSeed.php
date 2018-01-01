<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Seed;

use DateTime;
use RuntimeException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;

class TransactionSeed extends BaseSeed
{
    
   
   protected $aTransactions;
    
    
    
    
    protected function createTransaction($iTransactionId, $iJournalTypeId, $iUserId, $sVoucherCode)
    {
        $oDatabase          = $this->getDatabase();
        $aTableNames        = $this->getTableNames();
        $iRowsAffected      = 0;
        $sTransactionTable  =  $aTableNames['ledger_transaction'];
        
        
        $aSql            = [];
        $sSql            = '';
        $aBinds          = [
            ':iTransactionId'     => $iTransactionId,
            ':iJournalTypeId'     => $iJournalTypeId,
            ':iUserId'            => $iUserId,
            ':sVoucherCode'       => $sVoucherCode, 
      
        ];
        
        
        $aTypes = [
            ':iTransactionId' => TYPE::getType(TYPE::INTEGER),
            ':iJournalTypeId' => TYPE::getType(TYPE::INTEGER),
            ':iUserId'        => TYPE::getType(TYPE::INTEGER),
            ':sVoucherCode'   => TYPE::getType(TYPE::STRING), 
        ];

 

        $aSql[] = "INSERT INTO $sTransactionTable (`transaction_id`, `journal_type_id`, `adjustment_id`, `org_unit_id`, `user_id`, `process_dt`, `occured_dt`, `vouchernum`)";
        $aSql[] =" VALUES ( :iTransactionId, :iJournalTypeId, null, null,  :iUserId, NOW(), NOW(), :sVoucherCode ) ";
     
           
        $sSql1 =  implode(PHP_EOL,$aSql);
        $iRowsAffected = $oDatabase->executeUpdate($sSql1,$aBinds,$aTypes);
        
        if($iRowsAffected == 0) {
            throw new RuntimeException('Unable to create new Transaction');
        }
         
             
    }
   
   
    protected function createMovement($iEntryId, $iTransactionId, $iAccountId, $fMovement)
    {
        $oDatabase          = $this->getDatabase();
        $aTableNames        = $this->getTableNames();
        $iRowsAffected      = 0;
        $sTransactionEntryTable  =  $aTableNames['ledger_entry'];
        
        
        $aSql            = [];
        $sSql            = '';
        
        $aBinds          = [
            ':iEntryId'        => $iEntryId,
            ':iTransactionId'  => $iTransactionId,
            ':iAccountId'      => $iAccountId,
            ':fMovement'       => $fMovement, 
      
        ];
        
        
        $aTypes = [
            ':iEntryId'       => TYPE::getType(TYPE::INTEGER),
            ':iTransactionId' => TYPE::getType(TYPE::INTEGER),
            ':iAccountId'     => TYPE::getType(TYPE::INTEGER),
            ':fMovement'      => TYPE::getType(TYPE::DECIMAL), 
        ];

 

        $aSql[] = "INSERT INTO $sTransactionEntryTable (`entry_id`, `transaction_id`, `account_id`, `movement`)";
        $aSql[] =" VALUES (:iEntryId, :iTransactionId, :iAccountId, :fMovement ) ";
     
           
        $sSql1 =  implode(PHP_EOL,$aSql);
        $iRowsAffected = $oDatabase->executeUpdate($sSql1,$aBinds,$aTypes);
        
        if($iRowsAffected == 0) {
            throw new RuntimeException('Unable to create new Transaction Movement');
        }
        
        
    }
    
    
    protected function updateAggTables()
    {
        $oDatabase          = $this->getDatabase();
        $aTableNames        = $this->getTableNames();
        $sDailyTableName    = $aTableNames['ledger_daily'];
        $sTransactionEntryTable  =  $aTableNames['ledger_entry'];
        $sTransactionTable  =  $aTableNames['ledger_transaction'];
        $sApptDailyTable    = $aTableNames['ledger_daily_user'];
        
        
        $sSql   =" INSERT INTO $sDailyTableName (process_dt, account_id, balance) ";
        $sSql  .=" SELECT t.process_dt, te.account_id, sum(te.movement) ";
        $sSql  .=" FROM $sTransactionTable t, $sTransactionEntryTable te ";
        $sSql  .=" WHERE t.transaction_id = te.transaction_id ";
        $sSql  .=" GROUP BY te.account_id, t.process_dt ";
    
    
    
        $oDatabase->executeUpdate($sSql,[],[]);
        
        $sSql   =" INSERT INTO $sApptDailyTable (process_dt, account_id, balance, user_id) "; 
        $sSql  .=" SELECT t.process_dt, te.account_id, sum(te.movement), t.user_id ";
        $sSql  .=" FROM $sTransactionTable t, $sTransactionEntryTable te ";
        $sSql  .=" WHERE t.transaction_id = te.transaction_id ";
        $sSql  .=" GROUP BY te.account_id, t.process_dt, t.user_id ";
    
        $oDatabase->executeUpdate($sSql,[],[]);
        
    }
    
    protected function doExecuteSeed()
    {
        
        foreach($this->aTransactions as $sKey => $aTransaction) {
            $this->createTransaction(
                $aTransaction['TRANSACTION_ID'],
                $aTransaction['JOURNAL_TYPE_ID'],
                $aTransaction['USER_ID'],
                $aTransaction['VOUCHER_CODE']
            );
            
            
            // Process the movements
            foreach($aTransaction['MOVEMENTS'] as $aMovement) {
                $this->createMovement(
                   $aMovement['ENTRY_ID'],
                   $aTransaction['TRANSACTION_ID'],
                   $aMovement['ACCOUNT_ID'],
                   $aMovement['MOVEMENT']
                );
                
            }
            
        }
        
        $this->updateAggTables();
        
    }
    
    
    public function __construct(Connection $oDatabase, array $aTableNames, array $aTransactions)
    {
       
        parent::__construct($oDatabase, $aTableNames);
        
       
        $this->aTransactions = $aTransactions;
   
    }
    
    
    
}
/* End of Class */
