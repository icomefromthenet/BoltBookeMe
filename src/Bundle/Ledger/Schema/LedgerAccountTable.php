<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Ledger\Schema;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\VirtualColumnTable;


class LedgerAccountTable extends VirtualColumnTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
        
        $this->table->addOption('comment','Holds the Ledger Accounts Details');

        $this->table->addColumn("account_id", "integer", array("unsigned" => true,"autoincrement"=>true));
        $this->table->addColumn("account_number", "string", array("length" => 25));
        $this->table->addColumn("account_name","string",array('length' => 50));
        $this->table->addColumn("account_name_slug","string",array('length' => 50));
        $this->table->addColumn('hide_ui',"boolean",array("default" => false));
        $this->table->addColumn("is_left", "boolean", array('notnull'=> true));
        $this->table->addColumn("is_right", "boolean", array('notnull'=> true));

    }

    /**
     * {@inheritdoc}
     */
    protected function addIndexes()
    {
        $this->table->addUniqueIndex(array("account_number"));
    }

    /**
     * {@inheritdoc}
     */
    protected function setPrimaryKey()
    {
        $this->table->setPrimaryKey(array('account_id'));
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {

       
    }
}
/* End of Table */