<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Fixture;


class BasicFixture extends BaseFixture
{
    
    
    protected function doBookingFixtures($aTableNames)
    {
        $this->getDatabaseAdapter()->executeUpdate("INSERT INTO ".$aTableNames['bm_rule_type'] ." (`rule_type_id`,`rule_code`,`is_work_day`,`is_exclusion`,`is_inc_override`) values (1,'workday',true,false,false)");
        $this->getDatabaseAdapter()->executeUpdate("INSERT INTO ".$aTableNames['bm_rule_type'] ." (`rule_type_id`,`rule_code`,`is_work_day`,`is_exclusion`,`is_inc_override`) values (2,'break',false,true,false)");
        $this->getDatabaseAdapter()->executeUpdate("INSERT INTO ".$aTableNames['bm_rule_type'] ." (`rule_type_id`,`rule_code`,`is_work_day`,`is_exclusion`,`is_inc_override`) values (3,'holiday',false,true,false)");
        $this->getDatabaseAdapter()->executeUpdate("INSERT INTO ".$aTableNames['bm_rule_type'] ." (`rule_type_id`,`rule_code`,`is_work_day`,`is_exclusion`,`is_inc_override`) values (4,'overtime',false,false,true)");

        $this->getDatabaseAdapter()->executeUpdate("INSERT INTO ".$aTableNames['bm_appointment_status'] ." (`status_code`,`status_description`) values ('W','Waiting')");
        $this->getDatabaseAdapter()->executeUpdate("INSERT INTO ".$aTableNames['bm_appointment_status'] ." (`status_code`,`status_description`) values ('A','Assigned')");
        $this->getDatabaseAdapter()->executeUpdate("INSERT INTO ".$aTableNames['bm_appointment_status'] ." (`status_code`,`status_description`) values ('D','Completed')");
        $this->getDatabaseAdapter()->executeUpdate("INSERT INTO ".$aTableNames['bm_appointment_status'] ." (`status_code`,`status_description`) values ('C','Canceled')");

        
        
    }
    
    
    protected function doVoucherFixture($aTableNames)
    {
            $this->getDatabaseAdapter()->executeUpdate(
            "INSERT INTO ".$aTableNames['bm_voucher_group'] ." (`voucher_group_id`,`voucher_group_name`,`voucher_group_slug`,`is_disabled`,`sort_order`,`date_created`) ".
            " VALUES (1,'Appointment','appointment',0,1,now())");
        
        $this->getDatabaseAdapter()->executeUpdate(
            "INSERT INTO ".$aTableNames['bm_voucher_group']." (`voucher_group_id`,`voucher_group_name`,`voucher_group_slug`,`is_disabled`,`sort_order`,`date_created`) ".
            " VALUES (2,'Journal','journal',0,1,now())");
            
            
          $this->getDatabaseAdapter()->executeUpdate(
            "INSERT INTO ".$aTableNames['bm_voucher_gen_rule'] .
            "(`voucher_gen_rule_id`,`voucher_rule_name`,`voucher_rule_slug`,`voucher_padding_char`,`voucher_prefix`,`voucher_suffix`,
              `voucher_length`,`date_created`,`voucher_sequence_no`,`voucher_sequence_strategy`,`voucher_validate_rules`) ".
            " VALUES ('1','Appointment Number','appointment_number','','A', '', 5, now(), 1000, 'sequence', '".serialize(['always-valid'])."')");
                      
            $this->getDatabaseAdapter()->executeUpdate(
            "INSERT INTO ".$aTableNames['bm_voucher_gen_rule'] .
            "(`voucher_gen_rule_id`,`voucher_rule_name`,`voucher_rule_slug`,`voucher_padding_char`,`voucher_prefix`,`voucher_suffix`,
              `voucher_length`,`date_created`,`voucher_sequence_no`,`voucher_sequence_strategy`,`voucher_validate_rules`) ".
            " VALUES ('2','Sales Journal','sales_journal','','S', '', 8, now(), 100, 'sequence', '".serialize(['always-valid'])."')");           
            
            $this->getDatabaseAdapter()->executeUpdate(
            "INSERT INTO ".$aTableNames['bm_voucher_gen_rule'] .
            "(`voucher_gen_rule_id`,`voucher_rule_name`,`voucher_rule_slug`,`voucher_padding_char`,`voucher_prefix`,`voucher_suffix`,
              `voucher_length`,`date_created`,`voucher_sequence_no`,`voucher_sequence_strategy`,`voucher_validate_rules`) ".
            " VALUES ('3','Discounts Journal','discounts_journal','','D', '', 8, now(), 100, 'sequence', '".serialize(['always-valid'])."')");           
            
            $this->getDatabaseAdapter()->executeUpdate(
            "INSERT INTO ".$aTableNames['bm_voucher_gen_rule'] .
            "(`voucher_gen_rule_id`,`voucher_rule_name`,`voucher_rule_slug`,`voucher_padding_char`,`voucher_prefix`,`voucher_suffix`,
              `voucher_length`,`date_created`,`voucher_sequence_no`,`voucher_sequence_strategy`,`voucher_validate_rules`) ".
            " VALUES ('4','General Journal','general_journal','','G', '', 8, now(), 100, 'sequence', '".serialize(['always-valid'])."')");
            
             $this->getDatabaseAdapter()->executeUpdate(
            "INSERT INTO ".$aTableNames['bm_voucher_type'] .
            "(`voucher_type_id`, `voucher_enabled_from`, `voucher_enabled_to`, `voucher_name`, `voucher_name_slug`, `voucher_description`, `voucher_group_id`, `voucher_gen_rule_id`) ".
            " VALUES (1,
                     DATE_FORMAT(NOW() ,'%Y-01-01'),
                     '3000-01-01',
                     'Appointment Number V1',
                     'appointment_number',
                     'Appointment Number Version 1',
                     1,
                     1
            )");
            
            $this->getDatabaseAdapter()->executeUpdate(
            "INSERT INTO ".$aTableNames['bm_voucher_type'] .
            "(`voucher_type_id`, `voucher_enabled_from`, `voucher_enabled_to`, `voucher_name`, `voucher_name_slug`, `voucher_description`, `voucher_group_id`, `voucher_gen_rule_id`) ".
            " VALUES (2,
                     DATE_FORMAT(NOW() ,'%Y-01-01'),
                     '3000-01-01',
                     'Sales Journals V1',
                     'sales_journals',
                     'Sales Journals Version 1',
                     2,
                     2
            )");
            
            $this->getDatabaseAdapter()->executeUpdate(
            "INSERT INTO ".$aTableNames['bm_voucher_type'] .
            "(`voucher_type_id`, `voucher_enabled_from`, `voucher_enabled_to`, `voucher_name`, `voucher_name_slug`, `voucher_description`, `voucher_group_id`, `voucher_gen_rule_id`) ".
            " VALUES (3,
                     DATE_FORMAT(NOW() ,'%Y-01-01'),
                     '3000-01-01',
                     'Payments Journals V1',
                     'payments_journals',
                     'Payments Journals Version 1',
                     2,
                     3
            )");
            
            $this->getDatabaseAdapter()->executeUpdate(
            "INSERT INTO ".$aTableNames['bm_voucher_type'] .
            "(`voucher_type_id`, `voucher_enabled_from`, `voucher_enabled_to`, `voucher_name`, `voucher_name_slug`, `voucher_description`, `voucher_group_id`, `voucher_gen_rule_id`) ".
            " VALUES (4,
                     DATE_FORMAT(NOW() ,'%Y-01-01'),
                     '3000-01-01',
                     'General Journals V1',
                     'general_journals',
                     'General Journals Version 1',
                     2,
                     4
            )");
            
    
        
    }
    
    
    protected function doLedgerFixture($aTableNames)
    {
        $sAccounts      = $aTableNames['ledger_account'];
        $sAccountGroup  = $aTableNames['ledger_account_group'];
        $sJournalTable  = $aTableNames['ledger_journal_type'];
        
        $aAccounts = [
        
        [
            'account_id'   => 1
            ,'account_number' => '000000'
            ,'account_name' => 'Root Account'
            ,'account_name_slug' => 'root_account'
            ,'hide_ui'  => 0
            ,'is_left'  => 0
            ,'is_right' => 0
        ]
            
        # Asset Accounts
                    
       ,[
            'account_id' => 2
            ,'account_number' => '1-0000'
            ,'account_name' => 'Assets'
            ,'account_name_slug' => 'assets'
            ,'hide_ui'  => 0
            ,'is_left'  => 1
            ,'is_right' => 0  
        ]
        
        ,[
            'account_id' => 3
            ,'account_number' => '1-0001'
            ,'account_name' => 'Payments'
            ,'account_name_slug' => 'sales'
            ,'hide_ui'  => 0
            ,'is_left'  => 1
            ,'is_right' => 0  
        ]
        ,[
            'account_id' => 4
            ,'account_number' => '1-0002'
            ,'account_name' => 'Cash Payments'
            ,'account_name_slug' => 'cash_payments'
            ,'hide_ui'  => 0
            ,'is_left'  => 1
            ,'is_right' => 0  
        ]
        ,[
            'account_id' => 5
            ,'account_number' => '1-0003'
            ,'account_name' => 'Credit Card Payments'
            ,'account_name_slug' => 'credit_card_payments'
            ,'hide_ui'  => 0
            ,'is_left'  => 1
            ,'is_right' => 0  
        ]
        ,[
            'account_id' => 6
            ,'account_number' => '1-0004'
            ,'account_name' => 'Direct Deposits'
            ,'account_name_slug' => 'direct_deposits'
            ,'hide_ui'  => 0
            ,'is_left'  => 1
            ,'is_right' => 0  
        ]
        ,[
            'account_id' => 7
            ,'account_number' => '1-0005'
            ,'account_name' => 'Sales'
            ,'account_name_slug' => 'sales'
            ,'hide_ui'  => 0
            ,'is_left'  => 1
            ,'is_right' => 0  
        ]
        
        # Liabilties Accounts
        
        ];
        
        foreach($aAccounts as $aAccount) {
            
            $this->getDatabaseAdapter()->executeUpdate(
                "INSERT INTO ".$sAccounts .
                "(`account_id`, `account_number`, `account_name`, `account_name_slug`, `hide_ui`, `is_left`, `is_right`) ".
                " VALUES (:iAccountId, :sAccountNumber, :sAccountName, :sAccountNameSlug, ':iHideUI', :isLeft, :isRight)",
                [':iAccountId' => $aAccount['account_id'],
                 ':sAccountNumber' => $aAccount['account_number'],
                 ':sAccountName' => $aAccount['account_name'],
                 ':sAccountNameSlug' => $aAccount['account_name_slug'],
                 ':iHideUI' => $aAccount['hide_ui'],
                 ':isLeft' => $aAccount['is_left'], 
                 ':isRight' => $aAccount['is_right']
                ]
            );
            
        }
        
        # Account Groups
        
        $aAccountGroups = [
            ['child_account_id' =>2, 'parent_account_id' => 1],
            ['child_account_id' =>3, 'parent_account_id' => 1],
            ['child_account_id' =>4, 'parent_account_id' => 1],
            ['child_account_id' =>5, 'parent_account_id' => 1],
            ['child_account_id' =>6, 'parent_account_id' => 1],
            ['child_account_id' =>7, 'parent_account_id' => 1],
            
            ['child_account_id' =>3, 'parent_account_id' => 2],
            ['child_account_id' =>4, 'parent_account_id' => 2],
            ['child_account_id' =>5, 'parent_account_id' => 2],
            ['child_account_id' =>6, 'parent_account_id' => 2],
            ['child_account_id' =>7, 'parent_account_id' => 2],
            
        
        ];
        
        
        foreach($aAccountGroups as $aAccountGroup) {
            
            $this->getDatabaseAdapter()->executeUpdate(
                "INSERT INTO ".$sAccountGroup .
                "(`child_account_id`, `parent_account_id`) ".
                " VALUES (:iParent, :iChild)",
                [':iParent' => $aAccountGroup['parent_account_id'], ':iChild' => $aAccountGroup['child_account_id']]
            );
        }
        
        
        # Ledger Journals
        
        $aLedgerJournals = [
        [
            'journal_type_id' =>  1
            ,'journal_name'  => 'Sales journal'
            ,'journal_name_slug' => 'sales_journal'
            ,'hide_ui' => 0
        ]
        
        ,[
             'journal_type_id' =>  2
            ,'journal_name'  => 'Payments journal'
            ,'journal_name_slug' => 'payments_journal'
            ,'hide_ui' => 0 
        ]
        
        ,[
             'journal_type_id' =>  3
            ,'journal_name'  => 'Adjustments journal'
            ,'journal_name_slug' => 'adjustments_journal'
            ,'hide_ui' => 0 
        ]
        
        ];
        
        foreach($aLedgerJournals as $aJournal) {
            
            $this->getDatabaseAdapter()->executeUpdate(
                "INSERT INTO ".$sJournalTable .
                "(`journal_type_id`, `journal_name`,`journal_name_slug`, `hide_ui`) ".
                " VALUES (:iJournalType, :sJournalName, :sJournalNameSlug, :iHideUI)",
                [':iJournalType'     => $aJournal['journal_type_id'],
                 ':sJournalName'     =>  $aJournal['journal_name'],
                 ':sJournalNameSlug' =>  $aJournal['journal_name_slug'],
                 ':iHideUI'          =>  $aJournal['hide_ui'],
                ]
            );
            
        }
        
    }
    
 
    public function runFixture(array $aAppConfig)
    {
        $aConfig = $aAppConfig;
        
        
        $oDatabase = $this->getDatabaseAdapter();
        
        // Truncate the Tables
        $sm = $oDatabase->getSchemaManager();
        
        $aTables = $sm->listTables();
        
        $oDatabase->exec('SET foreign_key_checks = 0');
        
        foreach ($aTables as $table) {
            if(strpos($table->getName(),'_bm_') != false) {
                $oDatabase->exec('TRUNCATE TABLE '.$table->getName());    
            }
            
        }
    
        $oDatabase->exec('SET foreign_key_checks = 1');
        
        
        $this->doBookingFixtures($aConfig['tablenames']);
        
       
        $this->doVoucherFixture($aConfig['tablenames']);
        
        
        $this->doLedgerFixture($aConfig['tablenames']);
            
            
    }
    
    
    
    
}
/* End of Class */
