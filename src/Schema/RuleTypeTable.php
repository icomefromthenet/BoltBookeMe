<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Schema;

use Bolt\Storage\Database\Schema\Table\BaseTable;


class RuleTypeTable extends BaseTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {

        $this->table->addOption('comment','Defines basic avability rules');
        
        $this->table->addColumn('rule_type_id',   'integer',   ['notnull' => true, 'comment' =>'Table Primary key', 'autoincrement' => true, 'unsigned' => true ]);
        $this->table->addColumn('rule_code',      'string',    ['notnull' => true, 'comment' =>'Shortcode to use for lookups' ,'length' => 10]);
        $this->table->addColumn('is_work_day',    'boolean',   ['default' => false ]);
        $this->table->addColumn('is_exclusion',   'boolean',   ['default' => false ]);
        $this->table->addColumn('is_inc_override','boolean',   ['default' => false ]);
        
        
        
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
        $this->table->setPrimaryKey(['rule_type_id']);
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {
      
       
    }
}
/* End of Table */