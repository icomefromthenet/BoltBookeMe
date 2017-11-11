<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Ledger\Schema;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\VirtualColumnTable;


class LedgerUserTable extends VirtualColumnTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
        
        $this->table->addOption('comment','Holds the Details of Users accounts used in Ledger Transaction Grouping');
            
        $this->table->addColumn('user_id',"integer",array("unsigned" => true, "autoincrement" => true));
        $this->table->addColumn('external_guid',"guid",array());
        $this->table->addColumn('rego_date','datetime',array('notnull' => true));
        
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
        $this->table->setPrimaryKey(array("user_id"));
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {
      
    }
}
/* End of Table */