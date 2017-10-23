<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Schema;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\VirtualColumnTable;


class AppointmentStatusTable extends VirtualColumnTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
        
        $this->table->addOption('comment','Status Codes for appointment');
        
        $this->table->addColumn('status_code',          'string',   ['notnull' => true,'comment' =>'Table Primary key', 'length'=> 2 ]);
        
        $this->table->addColumn('status_description',   'string',   ['notnull' => true,'comment' =>'Fk to the current booking', 'length' => 25]);
        
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
        $this->table->setPrimaryKey(['status_code']);
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {
      
       
    }
}
/* End of Table */