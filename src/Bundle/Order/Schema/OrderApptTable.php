<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Order\Schema;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\VirtualColumnTable;


class OrderApptTable extends VirtualColumnTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
       
        
        $this->table->addOption('comment','Contain header record for the appointments order');
        
        // Schema diff does not support a FK and PK on same column, so use this surrogate as pk
        $this->table->addColumn('order_id',   'integer',   ['notnull' => true,'comment' =>'Table Primary key', 'autoincrement' => true, 'unsigned' => true ]);

        $this->table->addColumn('created_on',    'datetime',   ['notnull' => true,'comment' =>'Date coupon was created' ]);
        $this->table->addColumn('updated_on',    'datetime',   ['notnull' => true,'comment' =>'Date last coupon was update' ]);
        
        $this->table->addColumn('is_deposit_only',  'boolean',   ['notnull' => true,  'comment' =>'If this order transaction should be only the deposite']);
        $this->table->addColumn('deposit_amt',      'decimal',   ['notnull' => false, 'comment' =>'Amount of the deposite', 'precision' => 13, 'scale' => 4 ]);        
        $this->table->addColumn('deposit_rate',     'float',     ['notnull' => false, 'comment' =>'Rate as percentage for the deposite']);        
       
        $this->table->addColumn('appointment_id',   'integer',   ['notnull' => true,'comment' =>'Fk to the Appt Table', 'unsigned' => true ]);
    
        $this->table->addColumn('package_cost',   'decimal', ['notnull'=> false, 'precision' => 13, 'scale' => 4 ]);
        $this->table->addColumn('discount_cost',  'decimal', ['notnull'=> false, 'precision' => 13, 'scale' => 4 ]);
        $this->table->addColumn('surcharge_cost', 'decimal', ['notnull'=> false, 'precision' => 13, 'scale' => 4 ]);
        $this->table->addColumn('tax_cost',       'decimal', ['notnull'=> false, 'precision' => 13, 'scale' => 4 ]);
        
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
        $this->table->setPrimaryKey(['order_id']);
        
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {
       $sApptTableName = $this->tablePrefix .'bm_appointment';
       
       $this->table->addForeignKeyConstraint($sApptTableName, ['appointment_id'], ['appointment_id'], ["onDelete" => "CASCADE"], null);

    }
}
/* End of Table */