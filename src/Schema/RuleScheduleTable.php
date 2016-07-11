<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Schema;

use Bolt\Storage\Database\Schema\Table\BaseTable;


class RuleScheduleTable extends BaseTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
        $this->table->addOption('comment','Links a rule to a schedule');
        
        $this->table->addColumn('rule_id',       'integer',   ['notnull' => true, 'comment' =>'FK to rule table ', 'unsigned' => true ]);
        $this->table->addColumn('schedule_id',   'integer',   ['notnull' => true, 'comment' =>'FK to schedule table', 'unsigned' => true ]);
        
        $this->table->addColumn('is_rollover',   'boolean',   ['default' => true, 'comment' =>'rule carried into new year' ]);
        
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
        $this->table->setPrimaryKey(['rule_id','schedule_id']);
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {
       $sScheduleTableName = $this->tablePrefix .'bm_schedule';
       
       $this->table->addForeignKeyConstraint($sScheduleTableName, ['schedule_id'], ['schedule_id'], [], null);
       
       $sRuleTableName = $this->tablePrefix .'bm_rule';
       
       $this->table->addForeignKeyConstraint($sRuleTableName, ['rule_id'], ['rule_id'], [], null);
       
    }
}
/* End of Table */