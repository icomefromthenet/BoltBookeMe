<?php
namespace  Bolt\Extension\IComeFromTheNet\BookMe\Tests\Unit\Bundle\Ledger;

use Doctrine\DBAL\Types\Type;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\ExtensionTest;
use Bolt\Extension\IComeFromTheNet\BookMe\Bus\Middleware\ValidationException;



class LedgerContainerTest extends ExtensionTest
{
    
    
   protected $aDatabaseId;    
    
    
   protected function handleEventPostFixtureRun()
   {
       
      $oNow      = $this->getNow();
      $oService  = $this->getTestAPI();
      $oDatabase = $this->getDatabaseAdapter();
      $aConfig   = $this->getAppConfig();
      
      return;
   }
   
   
   public function testLibraryContainerLoads()
   {
       
       $oContainer = $this->getContainer();
       
       $oLedgerContainer = $oContainer['bm.ledger.container'];
       
       $this->assertInstanceOf("Bolt\\Extension\\IComeFromTheNet\\BookMe\\Bundle\\Ledger\\CustomLedgerContainer",$oLedgerContainer);
       
       
   }
   
   public function testLibraryContainerLoadsVoucherGenerator()
   {
       
       $oContainer = $this->getContainer();
       
       $oLedgerContainer = $oContainer['bm.ledger.container'];
       
       $oVoucherGenerator = $oLedgerContainer->getVoucherGenerator();
       
       $this->assertInstanceOf("Bolt\\Extension\\IComeFromTheNet\\BookMe\\Bundle\\Voucher\\CustomVoucherGenerator",$oVoucherGenerator);
       
       
   }
}
/* End of File */