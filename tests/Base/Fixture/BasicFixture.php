<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Fixture;


class BasicFixture extends BaseFixture
{
 
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
        
        
       
        $this->getDatabaseAdapter()->executeUpdate("INSERT INTO ".$aConfig['tablenames']['bm_rule_type'] ." (`rule_type_id`,`rule_code`,`is_work_day`,`is_exclusion`,`is_inc_override`) values (1,'workday',true,false,false)");
        $this->getDatabaseAdapter()->executeUpdate("INSERT INTO ".$aConfig['tablenames']['bm_rule_type'] ." (`rule_type_id`,`rule_code`,`is_work_day`,`is_exclusion`,`is_inc_override`) values (2,'break',false,true,false)");
        $this->getDatabaseAdapter()->executeUpdate("INSERT INTO ".$aConfig['tablenames']['bm_rule_type'] ." (`rule_type_id`,`rule_code`,`is_work_day`,`is_exclusion`,`is_inc_override`) values (3,'holiday',false,true,false)");
        $this->getDatabaseAdapter()->executeUpdate("INSERT INTO ".$aConfig['tablenames']['bm_rule_type'] ." (`rule_type_id`,`rule_code`,`is_work_day`,`is_exclusion`,`is_inc_override`) values (4,'overtime',false,false,true)");

        $this->getDatabaseAdapter()->executeUpdate("INSERT INTO ".$aConfig['tablenames']['bm_appointment_status'] ." (`status_code`,`status_description`) values ('W','Waiting')");
        $this->getDatabaseAdapter()->executeUpdate("INSERT INTO ".$aConfig['tablenames']['bm_appointment_status'] ." (`status_code`,`status_description`) values ('A','Assigned')");
        $this->getDatabaseAdapter()->executeUpdate("INSERT INTO ".$aConfig['tablenames']['bm_appointment_status'] ." (`status_code`,`status_description`) values ('D','Completed')");
        $this->getDatabaseAdapter()->executeUpdate("INSERT INTO ".$aConfig['tablenames']['bm_appointment_status'] ." (`status_code`,`status_description`) values ('C','Canceled')");

        
        $this->getDatabaseAdapter()->executeUpdate(
            "INSERT INTO ".$aConfig['tablenames']['bm_voucher_group'] ." (`voucher_group_id`,`voucher_group_name`,`voucher_group_slug`,`is_disabled`,`sort_order`,`date_created`) ".
            " VALUES (1,'Appointment','appointment',0,1,now())");
        
        $this->getDatabaseAdapter()->executeUpdate(
            "INSERT INTO ".$aConfig['tablenames']['bm_voucher_group']." (`voucher_group_id`,`voucher_group_name`,`voucher_group_slug`,`is_disabled`,`sort_order`,`date_created`) ".
            " VALUES (2,'Journal','journal',0,1,now())");
            
            
          $this->getDatabaseAdapter()->executeUpdate(
            "INSERT INTO ".$aConfig['tablenames']['bm_voucher_gen_rule'] .
            "(`voucher_gen_rule_id`,`voucher_rule_name`,`voucher_rule_slug`,`voucher_padding_char`,`voucher_prefix`,`voucher_suffix`,
              `voucher_length`,`date_created`,`voucher_sequence_no`,`voucher_sequence_strategy`,`voucher_validate_rules`) ".
            " VALUES ('1','Appointment Number','appointment_number','','A', '', 5, now(), 1000, 'sequence', '".serialize(['always-valid'])."')");
                      
            $this->getDatabaseAdapter()->executeUpdate(
            "INSERT INTO ".$aConfig['tablenames']['bm_voucher_gen_rule'] .
            "(`voucher_gen_rule_id`,`voucher_rule_name`,`voucher_rule_slug`,`voucher_padding_char`,`voucher_prefix`,`voucher_suffix`,
              `voucher_length`,`date_created`,`voucher_sequence_no`,`voucher_sequence_strategy`,`voucher_validate_rules`) ".
            " VALUES ('2','Sales Journal','sales_journal','','S', '', 8, now(), 100, 'sequence', '".serialize(['always-valid'])."')");           
            
            $this->getDatabaseAdapter()->executeUpdate(
            "INSERT INTO ".$aConfig['tablenames']['bm_voucher_gen_rule'] .
            "(`voucher_gen_rule_id`,`voucher_rule_name`,`voucher_rule_slug`,`voucher_padding_char`,`voucher_prefix`,`voucher_suffix`,
              `voucher_length`,`date_created`,`voucher_sequence_no`,`voucher_sequence_strategy`,`voucher_validate_rules`) ".
            " VALUES ('3','Discounts Journal','discounts_journal','','D', '', 8, now(), 100, 'sequence', '".serialize(['always-valid'])."')");           
            
            $this->getDatabaseAdapter()->executeUpdate(
            "INSERT INTO ".$aConfig['tablenames']['bm_voucher_gen_rule'] .
            "(`voucher_gen_rule_id`,`voucher_rule_name`,`voucher_rule_slug`,`voucher_padding_char`,`voucher_prefix`,`voucher_suffix`,
              `voucher_length`,`date_created`,`voucher_sequence_no`,`voucher_sequence_strategy`,`voucher_validate_rules`) ".
            " VALUES ('4','General Journal','general_journal','','G', '', 8, now(), 100, 'sequence', '".serialize(['always-valid'])."')");
            
             $this->getDatabaseAdapter()->executeUpdate(
            "INSERT INTO ".$aConfig['tablenames']['bm_voucher_type'] .
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
            "INSERT INTO ".$aConfig['tablenames']['bm_voucher_type'] .
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
            "INSERT INTO ".$aConfig['tablenames']['bm_voucher_type'] .
            "(`voucher_type_id`, `voucher_enabled_from`, `voucher_enabled_to`, `voucher_name`, `voucher_name_slug`, `voucher_description`, `voucher_group_id`, `voucher_gen_rule_id`) ".
            " VALUES (3,
                     DATE_FORMAT(NOW() ,'%Y-01-01'),
                     '3000-01-01',
                     'Discounts Journals V1',
                     'discounts_journals',
                     'Discounts Journals Version 1',
                     2,
                     3
            )");
            
            $this->getDatabaseAdapter()->executeUpdate(
            "INSERT INTO ".$aConfig['tablenames']['bm_voucher_type'] .
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
    
    
    
    
}
/* End of Class */
