<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Ledger\Schema;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\VirtualColumnTable;


class LedgerDailyOrgTable extends VirtualColumnTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
        
        $this->table->addOption('comment','Holds the agg finance values for account for a calendar day for given org');
            
        $this->table->addColumn('org_unit_id',"integer",array("notnull" => true,"unsigned" => true));
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
        $this->table->setPrimaryKey(array("process_dt","account_id",'org_unit_id'));
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {
        $sAccountTable  = $this->tablePrefix . 'bm_ledger_account';
        $sOrgUnitTable  = $this->tablePrefix . 'bm_ledger_org_unit';
        
        $this->table->addForeignKeyConstraint($sAccountTable, array("account_id"), array("account_id"));
        $this->table->addForeignKeyConstraint($sOrgUnitTable, array("org_unit_id"), array("org_unit_id"));
  }
    
}
/* End of Table */