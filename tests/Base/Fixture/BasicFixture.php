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

        
      
    }
    
    
    
    
}
/* End of Class */
