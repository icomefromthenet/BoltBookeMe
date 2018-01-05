<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Order\Schema;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\VirtualColumnTable;


class OrderApptSurchargeTable extends VirtualColumnTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
       
        
        $this->table->addOption('comment','Contain the appointment order surcharge settings');
        $this->table->addColumn('surcharge_id',   'integer',   ['notnull' => true,'comment' =>'Fk to the Order Surcharge Table', 'unsigned' => true ]);
          
        $this->table->addColumn('surcharge_cost',     'decimal',   ['notnull' => false, 'comment' =>'Pre Tax Cost of the surcharge', 'precision' => 13, 'scale' => 4]);        
        $this->table->addColumn('surcharge_rate', 'float',   ['notnull' => false, 'comment' =>'rate to apply for this surcharge'  ]);        
  
           
        $this->table->addColumn('appointment_id',   'integer',   ['notnull' => true,'comment' =>'Primary Key to the Appt Table', 'unsigned' => true ]);
    
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
        $this->table->setPrimaryKey(['appointment_id']);
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {
       $sApptTableName = $this->tablePrefix .'bm_appointment';
       
       $this->table->addForeignKeyConstraint($sApptTableName, ['appointment_id'], ['appointment_id'], ["onDelete" => "CASCADE"], null);
        
        $sSurchargeTableName = $this->tablePrefix .'bm_order_surcharge';
       
       $this->table->addForeignKeyConstraint($sSurchargeTableName, ['surcharge_id'], ['surcharge_id'], ["onDelete" => "CASCADE"], null);

    }
}
/* End of Table */