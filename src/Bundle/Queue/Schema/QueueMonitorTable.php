<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Schema;

use Bolt\Storage\Database\Schema\Table\BaseTable;


class QueueMonitorTable extends BaseTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
        $this->table->addColumn('monitor_id','integer',array("unsigned" => true,'autoincrement' => true));
      
        # date and hour
        $this->table->addColumn('monitor_dte','datetime',array());
        
        # worker stats
        $this->table->addColumn('worker_max_time','integer',array("unsigned" => true, 'notnull' => false));
        $this->table->addColumn('worker_min_time','integer',array("unsigned" => true, 'notnull' => false));
        $this->table->addColumn('worker_mean_time','integer',array("unsigned" => true, 'notnull' => false));
        $this->table->addColumn('worker_mean_throughput','integer',array("unsigned" => true, 'notnull' => false));
        $this->table->addColumn('worker_max_throughput','integer',array("unsigned" => true, 'notnull' => false));
        $this->table->addColumn('worker_mean_utilization','decimal',array('notnull' => false));
        
        # queue stats
        $this->table->addColumn('queue_no_waiting_jobs','integer',array("unsigned" => true, 'notnull' => false));
        $this->table->addColumn('queue_no_failed_jobs','integer',array("unsigned" => true, 'notnull' => false));
        $this->table->addColumn('queue_no_error_jobs','integer',array("unsigned" => true, 'notnull' => false));
        $this->table->addColumn('queue_no_completed_jobs','integer',array("unsigned" => true, 'notnull' => false));
        $this->table->addColumn('queue_no_processing_jobs','integer',array("unsigned" => true, 'notnull' => false));
        $this->table->addColumn('queue_mean_service_time','integer',array("unsigned" => true, 'notnull' => false));
        $this->table->addColumn('queue_min_service_time','integer',array("unsigned" => true, 'notnull' => false));
        $this->table->addColumn('queue_max_service_time','integer',array("unsigned" => true, 'notnull' => false));
            
        $this->table->addColumn('monitor_complete','boolean',array('default' => false));     
                
    }

    /**
     * {@inheritdoc}
     */
    protected function addIndexes()
    {
        $this->table->addUniqueIndex(array('monitor_dte'));  
    }

    /**
     * {@inheritdoc}
     */
    protected function setPrimaryKey()
    {
        $this->table->setPrimaryKey(array("monitor_id"));
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {
      
       
    }
    
    
}
/* End of Table */