<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Voucher\Schema;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\VirtualColumnTable;


class VoucherInstanceTable extends VirtualColumnTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
        
        $this->table->addOption('comment','Voucher Instances');

        $this->table->addColumn('voucher_instance_id','integer',array("unsigned" => true,'autoincrement' => true));
        $this->table->addColumn('voucher_type_id','integer',array("unsigned" => true));
        $this->table->addColumn('voucher_code','string',array("length"=> 255));
        $this->table->addColumn('date_created','datetime',array());
     
    }

  /**
     * {@inheritdoc}
     */
    protected function addIndexes()
    {
         $this->table->addUniqueIndex(array('voucher_code'),'voucher_instance_uiq1');
       
    }

    /**
     * {@inheritdoc}
     */
    protected function setPrimaryKey()
    {
        $this->table->setPrimaryKey(array('voucher_instance_id'));
       
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {
        $sVoucherTypeName = $this->tablePrefix . 'voucher_type';
        
        $table->addForeignKeyConstraint($sVoucherTypeName,array('voucher_type_id'),array('voucher_type_id'),array(),'voucher_instance_fk1');

    }
    
}
/* End of Table */