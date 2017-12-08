<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model;

use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model\AccountBook;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\TransactionBundleException;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model\TransactionNormal;

/**
 * A class To create transactions for Sales Journals

 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class TransactionSale extends TransactionNormal
{
    
    
    public function setCustomerCost($fAmt)
    {
       $this->addAccountMovement(AccountBook::DEBIT_SALES, $fAmt);
    }
    
    
    public function setTaxOwed($fAmt)
    {
        $this->addAccountMovement(AccountBook::DEBIT_TAX,$fAmt);
    }
    
    public function setDiscount($fAmt)
    {
        $this->addAccountMovement(AccountBook::CREDIT_DISCOUNTS, $fAmt);
    }
    
    
}
/* End of File */