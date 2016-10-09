<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Schema;

use Bolt\Storage\Database\Schema\Table\BaseTable;


class QueueTable extends BaseTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
        # setup pk
        $this->table->addColumn('job_id','string',array('length'=> 36,'notnull' => false));
       
        # current state
        $this->table->addColumn('state_id','integer',array('unsigned'=> true , 'notnull' => true));
        
        # date added to queue
        $this->table->addColumn('dte_add','datetime',array('notnull' => true));
        
        # retry count
        $this->table->addColumn('retry_count','integer',array('default' => 0,'unsigned' => true));
        
        # retry timer
        $this->table->addColumn('retry_last','datetime',array('notnull' => false));
        
        # data blob for this job.
        $this->table->addColumn('job_data','object',array());
        
        # lock information
        $this->table->addColumn('handle','string',array('length' => 36, 'notnull' => false));
      
        $this->table->addColumn('lock_timeout','datetime',array('notnull' => false));
                
    }

    /**
     * {@inheritdoc}
     */
    protected function addIndexes()
    {
        $this->table->addIndex(array('handle'));
       
    }

    /**
     * {@inheritdoc}
     */
    protected function setPrimaryKey()
    {
        $this->table->setPrimaryKey(array("job_id"));
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {
      
       
    }
    
    
}
/* End of Table */