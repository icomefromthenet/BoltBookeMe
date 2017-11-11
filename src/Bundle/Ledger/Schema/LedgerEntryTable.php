<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Ledger\Schema;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\VirtualColumnTable;


class LedgerEntryTable extends VirtualColumnTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
        
        $this->table->addOption('comment','Holds the finance movements for each transaction');
            
        $this->table->addColumn('entry_id',"integer",array("unsigned" => true, "autoincrement" => true)); 
        $this->table->addColumn('transaction_id',"integer",array("notnull" => true,"unsigned" => true));
        $this->table->addColumn('account_id',"integer",array("notnull" => true,"unsigned" => true));
        $this->table->addColumn('movement',"float",array("notnull" => true));
       
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
        $this->table->setPrimaryKey(array("entry_id"));
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {
        $sTransactionTable = $this->tablePrefix . 'bm_ledger_transaction';
        $sAccountTable     = $this->tabkePrefix . 'bm_ledger_account';
        
        $this->table->addForeignKeyConstraint($sTransactionTable, array("transaction_id"), array("transaction_id"));
        $this->table->addForeignKeyConstraint($sAccountTable, array("account_id"), array("account_id"));
  
    }
}
/* End of Table */