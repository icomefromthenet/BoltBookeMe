<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Order\Schema;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\VirtualColumnTable;


class OrderApptCouponTable extends VirtualColumnTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
       
        
        $this->table->addOption('comment','Contain the appointment order coupon settings');
        
        $this->table->addColumn('coupon_id',   'integer',   ['notnull' => true,'comment' =>'Fk to the Order Coupon Table', 'unsigned' => true ]);
        
        $this->table->addColumn('coupon_cost',  'decimal',   ['notnull' => false, 'comment' =>'Fixed discount to apply', 'precision' => 13, 'scale' => 4]);        
        $this->table->addColumn('coupon_rate', 'float',   ['notnull' => false, 'comment' =>'Relative Discount to apply'  ]);        
  
           
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
        
        $sCouponTableName = $this->tablePrefix .'bm_order_coupon';
       
       $this->table->addForeignKeyConstraint($sCouponTableName, ['coupon_id'], ['coupon_id'], ["onDelete" => "CASCADE"], null);

    }
}
/* End of Table */