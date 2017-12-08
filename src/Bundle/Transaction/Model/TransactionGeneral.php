<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model;

use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model\AccountBook;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\TransactionBundleException;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model\TransactionNormal;

/**
 * A class To create transactions for General Journals

 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class TransactionGeneral extends TransactionNormal
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
    
    
    
    public function setPaymentCreditCard($fAmt)
    {
       $this->addAccountMovement(AccountBook::CREDIT_PAYMENTS_CREDIT_CARDS, $fAmt);
    }
    
    public function setPaymentDirectDeposit($fAmt)
    {
       $this->addAccountMovement(AccountBook::CREDIT_PAYMENTS_DIRECT_DEPOSITS, $fAmt);
    }
    
    public function setPaymentCash($fAmt)
    {
       $this->addAccountMovement(AccountBook::CREDIT_PAYMENTS_CASH, $fAmt);
    }
    
}
/* End of File */