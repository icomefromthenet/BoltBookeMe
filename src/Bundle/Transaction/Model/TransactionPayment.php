<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model;

use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model\AccountBook;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\TransactionBundleException;
use Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Transaction\Model\TransactionNormal;

/**
 * A class To create transactions for Payment Journals
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class TransactionPayment extends TransactionNormal
{
    
    
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