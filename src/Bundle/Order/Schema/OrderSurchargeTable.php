<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Order\Schema;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\VirtualColumnTable;


class OrderSurchargeTable extends VirtualColumnTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
       
        
        $this->table->addOption('comment','Contain details on appointment surcharges which used to increase the customer cost');
        
        $this->table->addColumn('surcharge_id',   'integer',   ['notnull' => true,'comment' =>'Table Primary key', 'autoincrement' => true, 'unsigned' => true ]);

        $this->table->addColumn('created_on',    'datetime',   ['notnull' => true,'comment' =>'Date Surcharge was created' ]);
        $this->table->addColumn('updated_on',    'datetime',   ['notnull' => true,'comment' =>'Date last surcharge update' ]);
        
        $this->table->addColumn('surcharge_name',    'string',   ['notnull' => true,  'comment' =>'Name for this surcharge', 'length' => 100 ]);
        $this->table->addColumn('surcharge_desc',   'string',   ['notnull' => false, 'comment' =>'A short Description', 'length' => 255 ]);        
        
        $this->table->addColumn('surcharge_cost',     'decimal',   ['notnull' => false, 'comment' =>'Pre Tax Cost of the surcharge', 'precision' => 13, 'scale' => 4]);        
        $this->table->addColumn('surcharge_rate', 'float',   ['notnull' => false, 'comment' =>'rate to apply for this surcharge'  ]);        
        
        $this->table->addColumn('surcharge_disabled', 'boolean',   ['default' => true, 'comment' =>'Surcharge allowed for new appointments'  ]);        
        
        $this->table->addColumn('rule_id', 'integer',   [ 'comment' =>'Surcharge allowed for new appointments' , 'unsigned' => true  ]);        
     
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
        $this->table->setPrimaryKey(['surcharge_id']);
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {
       $sRuleTableName = $this->tablePrefix .'bm_rule';
       
       $this->table->addForeignKeyConstraint($sRuleTableName, ['rule_id'], ['rule_id'], ["onDelete" => "RESTRICT"], null);

    }
}
/* End of Table */