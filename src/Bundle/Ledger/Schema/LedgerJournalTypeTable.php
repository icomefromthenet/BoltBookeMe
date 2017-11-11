<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Ledger\Schema;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\VirtualColumnTable;


class LedgerJournalTypeTable extends VirtualColumnTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
        
        $this->table->addOption('comment','Defines the Journal Types used in Transaction Grouping');
            
        $this->table->addColumn('journal_type_id',"integer",array("unsigned" => true, "autoincrement" => true));
        $this->table->addColumn('journal_name',"string",array("length" => 50));
        $this->table->addColumn('journal_name_slug',"string",array("length" => 50));
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
        $this->table->setPrimaryKey(array("journal_type_id"));
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {
      
    }
}
/* End of Table */