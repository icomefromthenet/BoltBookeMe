<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Order\Schema;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\VirtualColumnTable;


class OrderCouponTable extends VirtualColumnTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
       
        
        $this->table->addOption('comment','Contain details on appointment coupons which used to provide discounts');
        
        $this->table->addColumn('coupon_id',   'integer',   ['notnull' => true,'comment' =>'Table Primary key', 'autoincrement' => true, 'unsigned' => true ]);

        $this->table->addColumn('created_on',    'datetime',   ['notnull' => true,'comment' =>'Date coupon was created' ]);
        $this->table->addColumn('updated_on',    'datetime',   ['notnull' => true,'comment' =>'Date last coupon was update' ]);
        
        $this->table->addColumn('coupon_name',    'string',   ['notnull' => true,  'comment' =>'Name for this coupon', 'length' => 100 ]);
        $this->table->addColumn('coupon_desc',   'string',   ['notnull' => false, 'comment' =>'A short Description', 'length' => 255 ]);        
        
        $this->table->addColumn('coupon_cost',  'decimal',   ['notnull' => false, 'comment' =>'Fixed discount to apply', 'precision' => 13, 'scale' => 4]);        
        $this->table->addColumn('coupon_rate', 'float',   ['notnull' => false, 'comment' =>'Relative Discount to apply'  ]);        
        
        $this->table->addColumn('coupon_disabled', 'boolean',   ['default' => true, 'comment' =>'Coupon allowed for new appointments'  ]);        
        
        $this->table->addColumn('coupon_apply_from',    'datetime',   ['notnull' => true,'comment' =>'First date that coupon allowed for' ]);
        $this->table->addColumn('coupon_apply_to',    'datetime',   ['notnull' => true,'comment' =>'Last date the coupon allowed for' ]);
       
        
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
        $this->table->setPrimaryKey(['coupon_id']);
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {
       
    }
}
/* End of Table */