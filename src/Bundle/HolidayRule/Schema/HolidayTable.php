<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\HolidayRule\Schema;

use Bolt\Storage\Database\Schema\Table\BaseTable;


class HolidayTable extends BaseTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
        
        $this->table->addOption('comment','Rules Applied From Holiday Databae');

        
        # table pk
        $this->table->addColumn('holiday_hash',   'string',   ['notnull' => true,'comment' =>'Table Primary key','unsigned' => true ]);

        # optional fk 
        $this->table->addColumn('schedule_id',    'integer',   ['notnull' => false, 'comment' =>'Fk to Schedule table',         'unsigned' => true ]);
        $this->table->addColumn('rule_id',        'integer',   ['notnull' => false, 'comment' =>'Fk to Rule table',             'unsigned' => true ]);
        

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
        $this->table->setPrimaryKey(['holiday_hash']);
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {

       $sRuleTableName = $this->tablePrefix .'bm_rule';
       
       $this->table->addForeignKeyConstraint($sRuleTableName, ['rule_id'], ['rule_id'], [], null);
       
       
       $sScheduleTableName = $this->tablePrefix .'bm_schedule';
       
       $this->table->addForeignKeyConstraint($sScheduleTableName, ['schedule_id'], ['schedule_id'], [], null);
       
       
    }
}
/* End of Table */