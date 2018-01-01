<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Fixture;

use DateTime;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Seed\TransactionSeed;


class NewTransactionFixture extends BaseFixture
{
   
   
   
    
    public function runFixture(array $aAppConfig, DateTime $oNow)
    {
      
        $oDatabase   = $this->getDatabaseAdapter();
        $aTableNames = $this->getTableNames();
      
        $aTransactions = [
           
            // -------- Sales Journal Transactions ---------------------------
            [
                // Appointment One
                
                'TRANSACTION_ID' => 1,
                'JOURNAL_TYPE_ID' => 1,
                'USER_ID' => 1, // Appt One
                'VOUCHER_CODE' => 'S101',
                'MOVEMENTS' => [ 
                    [
                        'ENTRY_ID' => 1,
                        'ACCOUNT_ID' => 3, // Sales Account 
                        'MOVEMENT' => 100.00 
                    ],
                    [
                        'ENTRY_ID' => 2,
                        'ACCOUNT_ID' => 4, // Tax  Account 
                        'MOVEMENT' => 10.00 
                    ],
                    
                ]
                    
                
            ],
            
            [
                // Appointment Two
                
                'TRANSACTION_ID' => 2,
                'JOURNAL_TYPE_ID' => 1,
                'USER_ID' => 2, // Appt Two
                'VOUCHER_CODE' => 'S102',
                'MOVEMENTS' => [ 
                    [
                        'ENTRY_ID' => 3,
                        'ACCOUNT_ID' => 3, // Sales Account 
                        'MOVEMENT' => 150.00 
                    ],
                    [
                        'ENTRY_ID' => 4,
                        'ACCOUNT_ID' => 4, // Tax  Account 
                        'MOVEMENT' => 15.00 
                    ],
                    
                ]
                    
                
            ],
            
            [
                // Appointment Three
                
                'TRANSACTION_ID' => 3,
                'JOURNAL_TYPE_ID' => 1,
                'USER_ID' => 3, // Appt Three
                'VOUCHER_CODE' => 'S102',
                'MOVEMENTS' => [ 
                    [
                        'ENTRY_ID' => 5,
                        'ACCOUNT_ID' => 3, // Sales Account 
                        'MOVEMENT' => 110.00 
                    ],
                    [
                        'ENTRY_ID' => 6,
                        'ACCOUNT_ID' => 4, // Tax  Account 
                        'MOVEMENT' => 11.00 
                    ],
                    
                ]
                    
                
            ],
            
            
            [
                // Appointment Four
                
                'TRANSACTION_ID' => 4,
                'JOURNAL_TYPE_ID' => 1,
                'USER_ID' => 4, // Appt Four
                'VOUCHER_CODE' => 'S104',
                'MOVEMENTS' => [ 
                    [
                        'ENTRY_ID' => 7,
                        'ACCOUNT_ID' => 3, // Sales Account 
                        'MOVEMENT' => 130.00 
                    ],
                    [
                        'ENTRY_ID' => 8,
                        'ACCOUNT_ID' => 4, // Tax  Account 
                        'MOVEMENT' => 13.00 
                    ],
                    
                ]
                    
                
            ],
           
           
            // -------- Payment Journal Transactions --------------------------- 
            
            [
                // Appointment One
                // a Payment on the sale
                
                'TRANSACTION_ID' => 5,
                'JOURNAL_TYPE_ID' => 2,
                'USER_ID' => 1, // Appt One
                'VOUCHER_CODE' => 'D101',
                'MOVEMENTS' => [ 
                    [
                        'ENTRY_ID' => 9,
                        'ACCOUNT_ID' => 105, // Card Payments 
                        'MOVEMENT' => -110.00 
                    ],
                
                    
                ]
                    
                
            ],
            
            [
                // Appointment Three
                // a payment on sale
                
                'TRANSACTION_ID' => 6,
                'JOURNAL_TYPE_ID' => 2,
                'USER_ID' => 3, // Appt Three
                'VOUCHER_CODE' => 'D102',
                'MOVEMENTS' => [ 
                    [
                        'ENTRY_ID' => 10,
                        'ACCOUNT_ID' => 105, // Card Payments 
                        'MOVEMENT' => -121.00 
                    ],
                
                    
                ]
                    
                
            ],
            
            // ------------- Adjustment Transactions --------------------
            [
                // Appointment Two
                // Using Adjustments to reverse the sale.
                
                'TRANSACTION_ID' => 7,
                'JOURNAL_TYPE_ID' => 3,
                'USER_ID' => 2, // Appt Two
                'VOUCHER_CODE' => 'G101',
                'MOVEMENTS' => [ 
                    [
                        'ENTRY_ID' => 11,
                        'ACCOUNT_ID' => 3, // Sales Account 
                        'MOVEMENT' => -150.00 
                    ],
                    [
                        'ENTRY_ID' => 12,
                        'ACCOUNT_ID' => 4, // Tax  Account 
                        'MOVEMENT' => -15.00 
                    ],
                    
                ]
            ],
            
        ];
        
        $oTransactionSeed = new TransactionSeed($oDatabase, $aTableNames, $aTransactions);
        $oTransactionSeed->executeSeed();
        
    }
    
   
    
}
/* End of Class */
