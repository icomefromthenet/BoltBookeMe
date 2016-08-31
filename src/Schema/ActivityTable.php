<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Schema;

use Bolt\Storage\Database\Schema\Table\BaseTable;


class ActivityTable extends BaseTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
        
        $this->table->addOption('comment','Log of operations that change entities in this extension');

        
        # table pk
        $this->table->addColumn('activity_id',   'integer',   ['notnull' => true,'comment' =>'Table Primary key', 'autoincrement' => true, 'unsigned' => true ]);

        # optional fk 
        $this->table->addColumn('bolt_user_id',   'integer',   ['notnull' => false, 'comment' =>'Fk to Bolt User table',        'unsigned' => true ]);
        $this->table->addColumn('appointment_id', 'integer',   ['notnull' => false, 'comment' =>'Fk to Appointment table',      'unsigned' => true ]);
        $this->table->addColumn('customer_id',    'integer',   ['notnull' => false, 'comment' =>'Fk to Customer table',         'unsigned' => true ]);
        $this->table->addColumn('member_id',      'integer',   ['notnull' => false, 'comment' =>'Fk to Schedule Member table',  'unsigned' => true ]);
        $this->table->addColumn('schedule_id',    'integer',   ['notnull' => false, 'comment' =>'Fk to Schedule table',         'unsigned' => true ]);
        $this->table->addColumn('rule_id',        'integer',   ['notnull' => false, 'comment' =>'Fk to Rule table',             'unsigned' => true ]);
        
        # event details
        $this->table->addColumn('occured_dte',      'datetime',   ['notnull' => true,'comment' =>'When the event occured' ]);
        $this->table->addColumn('activity_type',    'string',     ['notnull' => true,'comment' =>'activity type', 'length' => 50 ]);
        $this->table->addColumn('activity_reason',  'string',     ['notnull' => true,'comment' =>'extra context reason', 'length' => 100 ]);
        
    }

    /**
     * {@inheritdoc}
     */
    protected function addIndexes()
    {
        
    }

    /**
     * {@inheritdoc}
     */
    protected function setPrimaryKey()
    {
        $this->table->setPrimaryKey(['activity_id']);
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {
       //$sBoltUserTableName = $this->tablePrefix .'user';
       
       //$this->table->addForeignKeyConstraint($sBoltUserTableName, ['bolt_user_id'], ['user_id'], [], null);
       
       
       $sApptTableName = $this->tablePrefix .'bm_appointment';
       
       $this->table->addForeignKeyConstraint($sApptTableName, ['appointment_id'], ['appointment_id'], [], null);
       
       
       $sCustomerTableName = $this->tablePrefix .'bm_customer';
       
       $this->table->addForeignKeyConstraint($sCustomerTableName, ['customer_id'], ['customer_id'], [], null);
       
       
       $sMemberTableName = $this->tablePrefix .'bm_schedule_membership';
       
       $this->table->addForeignKeyConstraint($sMemberTableName, ['member_id'], ['membership_id'], [], null);
       
       
       $sRuleTableName = $this->tablePrefix .'bm_rule';
       
       $this->table->addForeignKeyConstraint($sRuleTableName, ['rule_id'], ['rule_id'], [], null);
       
       
       $sScheduleTableName = $this->tablePrefix .'bm_schedule';
       
       $this->table->addForeignKeyConstraint($sScheduleTableName, ['schedule_id'], ['schedule_id'], [], null);
       
       
    }
}
/* End of Table */