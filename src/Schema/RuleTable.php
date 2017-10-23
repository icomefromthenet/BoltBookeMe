<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Schema;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\VirtualColumnTable;


class RuleTable extends VirtualColumnTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
        
        $this->table->addOption('comment','Rule instances');
        
        $this->table->addColumn('rule_id',        'integer',   ['notnull' => true, 'comment' =>'Table Primary key', 'autoincrement' => true, 'unsigned' => true ]);
        $this->table->addColumn('rule_type_id',   'integer',   ['notnull' => true, 'comment' =>'FK to rule type table', 'unsigned' => true ]);
        $this->table->addColumn('timeslot_id',    'integer',   ['notnull' => true, 'comment' =>'FK to timeslot table', 'unsigned' => true ]);
        
        
        $this->table->addColumn('repeat_minute',     'string',    ['notnull' => true, 'comment' =>'Cron minute segment' ,'length' => 45]);
        $this->table->addColumn('repeat_hour',       'string',    ['notnull' => true, 'comment' =>'Cron hour segment' ,'length' => 45]);
        $this->table->addColumn('repeat_dayofweek',  'string',    ['notnull' => true, 'comment' =>'Cron day of week segment' ,'length' => 45]);
        $this->table->addColumn('repeat_dayofmonth', 'string',    ['notnull' => true, 'comment' =>'Cron day of month segment' ,'length' => 45]);
        $this->table->addColumn('repeat_month',      'string',    ['notnull' => true, 'comment' =>'Cron month segment' ,'length' => 45]);
        $this->table->addColumn('repeat_weekofyear', 'string',    ['notnull' => true, 'comment' =>'Cron week of year segment (not standard)' ,'length' => 45]);
        
        
        $this->table->addColumn('start_from',      'datetime',    ['notnull' => true, 'comment' =>'start date for rule']);
        $this->table->addColumn('end_at',          'datetime',    ['notnull' => true, 'comment' =>'end date for rule']);
        
        $this->table->addColumn('open_slot',      'integer',   ['notnull' => true, 'comment' =>'Open slot from timeslot day',    'unsigned' => true ]);
        $this->table->addColumn('close_slot',     'integer',   ['notnull' => true, 'comment' =>'Closing slot from timeslot day', 'unsigned' => true ]);
        $this->table->addColumn('cal_year',       'integer',   ['notnull' => true, 'comment' =>'calendar year applied too',      'unsigned' => true ]);
        $this->table->addColumn('carry_from_id',  'integer',   ['default' => 0,    'comment' =>'FK to timeslot table',           'unsigned' => true ]);

        $this->table->addColumn('is_single_day','boolean',    ['default' => false ]);
        
        $this->table->addColumn('rule_name','string', ['length'=> 50]);
        $this->table->addColumn('rule_desc','string', ['length' =>500 ,'notnull' => false]);
        
        
        
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
        $this->table->setPrimaryKey(['rule_id']);
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {
       $sRuleTypeTableName = $this->tablePrefix .'bm_rule_type';
       
       $this->table->addForeignKeyConstraint($sRuleTypeTableName, ['rule_type_id'], ['rule_type_id'], [], null);
       
       $sTimeslotTableName = $this->tablePrefix .'bm_timeslot';
       
       $this->table->addForeignKeyConstraint($sTimeslotTableName, ['timeslot_id'], ['timeslot_id'], [], null);
       
    }
}
/* End of Table */