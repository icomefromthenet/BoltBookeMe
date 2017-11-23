<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Ledger\Schema;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\VirtualColumnTable;


class LedgerAccountGroupTable extends VirtualColumnTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
        
        $this->table->addOption('comment','Holds the Ledger Account Group Relations');
            
        $this->table->addColumn("child_account_id", "integer", array("unsigned" => true));
        $this->table->addColumn("parent_account_id", "integer", array("unsigned" => true,'notnull'=> false));
            
        
        
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
          $this->table->setPrimaryKey(array("child_account_id","parent_account_id"));
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {
        $sAccountTable = $this->tablePrefix . 'bm_ledger_account';
        
        $this->table->addForeignKeyConstraint($sAccountTable,array("parent_account_id"), array("account_id"), array("onUpdate" => "CASCADE"));
        $this->table->addForeignKeyConstraint($sAccountTable,array("child_account_id"), array("account_id"), array("onUpdate" => "CASCADE"));

    }
}
/* End of Table */