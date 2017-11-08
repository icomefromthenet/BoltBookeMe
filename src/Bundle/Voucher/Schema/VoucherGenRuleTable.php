<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Voucher\Schema;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\VirtualColumnTable;


class VoucherGenRuleTable extends VirtualColumnTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
        
        $this->table->addOption('comment','Voucher Generator Rules');

        $this->table->addColumn('voucher_rule_name','string',array('length'=> 25));
        $this->table->addColumn('voucher_rule_slug','string',array("length" => 25));
        $this->table->addColumn('voucher_gen_rule_id','integer',array('unsigned'=> true,'autoincrement' => true));
        $this->table->addColumn('voucher_padding_char','string',array('legnth'=>'1'));
        $this->table->addColumn('voucher_prefix','string',array('length'=> 50));
        $this->table->addColumn('voucher_suffix','string',array('length'=>50));
        $this->table->addColumn('voucher_length','smallint',array('unsigned'=> true,'length'=>3));
        $this->table->addColumn('date_created','datetime',array());
        $this->table->addColumn('voucher_sequence_no','integer',array('unsigned'=> true));
        $this->table->addColumn('voucher_sequence_strategy','string',array('length'=> 20));
        $this->table->addColumn('voucher_validate_rules','array',array());
       
      

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
        $this->table->setPrimaryKey(array('voucher_gen_rule_id'));
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {

       
    }
}
/* End of Table */