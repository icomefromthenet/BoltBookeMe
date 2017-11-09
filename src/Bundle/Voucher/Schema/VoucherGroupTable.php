<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Voucher\Schema;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\VirtualColumnTable;


class VoucherGroupTable extends VirtualColumnTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
        
        $this->table->addOption('comment','Voucher Groups');

        
        # table pk
        $this->table->addColumn('voucher_group_id',   'integer',   ['notnull' => true, 'autoincrement' => true, 'comment' =>'Table Primary key','unsigned' => true ]);

        $this->table->addColumn('voucher_group_name','string',array("length" => 100));
        $this->table->addColumn('voucher_group_slug','string',array("length" => 100));
        $this->table->addColumn('is_disabled','boolean',array("default"=>false));
        $this->table->addColumn('sort_order','integer',array("unsigned" => true));
        $this->table->addColumn('date_created','datetime',array());
      

    }

    /**
     * {@inheritdoc}
     */
    protected function addIndexes()
    {
        $this->table->addUniqueIndex(array('voucher_group_slug'),'bm_voucher_group_uiq1');
    }

    /**
     * {@inheritdoc}
     */
    protected function setPrimaryKey()
    {
        $this->table->setPrimaryKey(['voucher_group_id']);
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {

       
    }
}
/* End of Table */