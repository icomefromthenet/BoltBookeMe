<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Schema;

use Bolt\Storage\Database\Schema\Table\BaseTable;


class RuleSeriesTable extends BaseTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
        
        $this->table->addOption('comment','Defines schedule slots affected by rule');
        
        $this->table->addColumn('rule_id',        'integer',   ['notnull' => true, 'comment' =>'FK to rule table ', 'unsigned' => true ]);
        $this->table->addColumn('rule_type_id',   'integer',   ['notnull' => true, 'comment' =>'FK to rule type table', 'unsigned' => true ]);
        
        $this->table->addColumn('slot_open',      'datetime',  ['notnull' => true, 'comment' =>'start date for rule']);
        $this->table->addColumn('slot_close',     'datetime',  ['notnull' => true, 'comment' =>'end date for rule']);
        
        $this->table->addColumn('cal_year',       'integer',   ['notnull' => true, 'comment' =>'calendar year applied too',      'unsigned' => true ]);
        
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
        $this->table->setPrimaryKey(['rule_id','cal_year','slot_close']);
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {
       $sRuleTypeTableName = $this->tablePrefix .'bm_rule_type';
       
       $this->table->addForeignKeyConstraint($sRuleTypeTableName, ['rule_type_id'], ['rule_type_id'], [], null);
       
       $sRuleTableName = $this->tablePrefix .'bm_rule';
       
       $this->table->addForeignKeyConstraint($sRuleTableName, ['rule_id'], ['rule_id'], [], null);
       
    }
}
/* End of Table */