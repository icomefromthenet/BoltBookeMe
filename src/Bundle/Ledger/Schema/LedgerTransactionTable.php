<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Ledger\Schema;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\VirtualColumnTable;


class LedgerTransactionTable extends VirtualColumnTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
        
        $this->table->addOption('comment','Holds a Header record for a Ledger Transaction');
            
        $this->table->addColumn('transaction_id',"integer",array("unsigned" => true, "autoincrement" => true));
        $this->table->addColumn('org_unit_id',"integer",array("notnull" => false,"unsigned" => true));
        $this->table->addColumn('process_dt',"datetime",array("notnull" => true));
        $this->table->addColumn('occured_dt',"date",array("notnull" => true));
        $this->table->addColumn('vouchernum',"string",array("length" => 100));
        $this->table->addColumn('journal_type_id',"integer",array("notnull"=> true,"unsigned" => true));
        $this->table->addColumn('adjustment_id',"integer",array("notnull"=> false,"unsigned" => true));
        $this->table->addColumn('user_id',"integer",array("notnull"=> false,"unsigned" => true));
            
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
        $this->table->setPrimaryKey(array("transaction_id"));
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {
        $sJournalTypeTable = $this->tablePrefix . 'bm_ledger_journal_type';
        $sSelfTable        = $this->tablePrefix . 'bm_ledger_transaction';
        $sOrgUnitTable     = $this->tablePrefix . 'bm_ledger_org_unit';
        $sUserTable        = $this->tablePrefix . 'bm_ledger_user';
        
        
        $this->table->addForeignKeyConstraint($sJournalTypeTable, array("journal_type_id"), array("journal_type_id"));
        $this->table->addForeignKeyConstraint($sSelfTable,array("adjustment_id"),array("transaction_id"));
        $this->table->addForeignKeyConstraint($sOrgUnitTable, array("org_unit_id"), array("org_unit_id"));
        $this->table->addForeignKeyConstraint($sUserTable, array("user_id"), array("user_id"));

      
    }
}
/* End of Table */