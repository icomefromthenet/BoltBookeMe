<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Ledger\Schema;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\VirtualColumnTable;


class LedgerOrgGroupTable extends VirtualColumnTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
        
        $this->table->addOption('comment','Holds the Details of Org Groups used in Ledger Transaction Grouping');
            
        $this->table->addColumn('org_unit_id',"integer",array("unsigned" => true, "autoincrement" => true));
        $this->table->addColumn('org_unit_name',"string",array("length" => 50));
        $this->table->addColumn('org_unit_name_slug',"string",array("length" => 50));
        $this->table->addColumn('hide_ui',"boolean",array("default" => false));
        
       
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
        $this->table->setPrimaryKey(array("org_unit_id"));
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {
      
    }
}
/* End of Table */