<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Voucher\Schema;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\VirtualColumnTable;


class VoucherTypeTable extends VirtualColumnTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
        
        $this->table->addOption('comment','Voucher Types');

  
        $this->table->addColumn('voucher_type_id','integer',array("unsigned" => true,'autoincrement' => true));
        $this->table->addColumn("voucher_enabled_from", "datetime",array());
        $this->table->addColumn("voucher_enabled_to", "datetime",array());
        $this->table->addColumn('voucher_name','string',array('length'=>100));
        $this->table->addColumn('voucher_name_slug','string',array('length'=>100));
        $this->table->addColumn('voucher_description','string',array('length'=>500));
        $this->table->addColumn('voucher_group_id','integer',array('unsigned'=> true));
        $this->table->addColumn('voucher_gen_rule_id','integer',array('unsigned'=> true));
        
    }

    /**
     * {@inheritdoc}
     */
    protected function addIndexes()
    {
        $this->table->addUniqueIndex(array('voucher_name','voucher_enabled_from'),'bm_voucher_type_uiq1');
     
    }

    /**
     * {@inheritdoc}
     */
    protected function setPrimaryKey()
    {
        $this->table->setPrimaryKey(array('voucher_type_id'));
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {
        
        $sVoucherGenRuleName = $this->tablePrefix . 'bm_voucher_gen_rule';
        $sVoucherGroupName   = $this->tablePrefix . 'bm_voucher_group';
      
        
        $this->table->addForeignKeyConstraint($sVoucherGroupName,array('voucher_group_id'),array('voucher_group_id'),array(),'bm_voucher_type_fk1');
        $this->table->addForeignKeyConstraint($sVoucherGenRuleName,array('voucher_gen_rule_id'),array('voucher_gen_rule_id'),array(),'bm_voucher_type_fk2');
       
    }
    
}
/* End of Table */