<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Schema;

use Bolt\Storage\Database\Schema\Table\BaseTable;


class ScheduleTeamTable extends BaseTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
       
        $this->table->addOption('Group schedules together with a common timeslot');
        
        $this->table->addColumn('team_id',     'integer',  ['notnull' => true,'comment' =>'Table Primary key', 'autoincrement' => true, 'unsigned' => true ]);
        $this->table->addColumn('registered_date',   'datetime',   ['notnull' => true, 'comment' =>'Date membership was created' ]);
        
        $this->table->addColumn('team_name',   'string',   ['notnull' => true, 'comment' =>'Member Name', 'length' => 100 ]);        
        
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
        $this->table->setPrimaryKey(['team_id']);
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {
      
     
    }
}
/* End of Table */