<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Ledger\Schema;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\VirtualColumnTable;


class LedgerDailyTable extends VirtualColumnTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
        
        $this->table->addOption('comment','Holds the agg finance values for account for a calendar day');
            
        $this->table->addColumn('process_dt',"date",array("notnull" => true));
        $this->table->addColumn('account_id',"integer",array("notnull" => true,"unsigned" => true));
        $this->table->addColumn('balance',"float",array("notnull" => true));
            

       
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
        $this->table->setPrimaryKey(array("process_dt","account_id"));
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {
        $sAccountTable     = $this->tabkePrefix . 'bm_ledger_account';
        
        $this->table->addForeignKeyConstraint($sAccountTable, array("account_id"), array("account_id"));
    }
    
}
/* End of Table */