<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Schema;

use Bolt\Storage\Database\Schema\Table\BaseTable;


class QueueTransitionTable extends BaseTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
        $this->table->addColumn('transition_id','integer',array("unsigned" => true,'autoincrement' => true));
        
        # column for worker Global UUID
        $this->table->addColumn('worker_id','string',array('length'=> 36,'notnull' => false));
        
        # column for job Global UUID
        $this->table->addColumn('job_id','string',array('length'=> 36,'notnull' => false));
        
        # column for transition state
        $this->table->addColumn('state_id','integer',array('unsigned'=> true ,'notnull' => true));
        
        # Job Process Handler
        $this->table->addColumn('process_handle','string',array('length'=> 36,'notnull' => false));
        
        # column dte of transition
        $this->table->addColumn('dte_occured','datetime',array('notnull' => true));
        
        # Optional transitional message
        $this->table->addColumn('transition_msg','string',array('length' => 200,'notnull'=> false));
                
    }

    /**
     * {@inheritdoc}
     */
    protected function addIndexes()
    {
        $this->table->addIndex(array('job_id'));
        $this->table->addIndex(array('worker_id'));
    }

    /**
     * {@inheritdoc}
     */
    protected function setPrimaryKey()
    {
        $this->table->setPrimaryKey(array("transition_id"));
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {
      
       
    }
}
/* End of Table */