<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Schema;

use Bolt\Storage\Database\Schema\Table\BaseTable;


class TimeslotTable extends BaseTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
        
            
        $this->table->addOption('comment','Timeslot definitions');
        
        $this->table->addColumn('timeslot_id',          'integer',    ['notnull' => true, 'comment' =>'Table Primary key', 'autoincrement' => true, 'unsigned' => true ]);
        $this->table->addColumn('timeslot_length',      'smallint',   ['notnull' => true, 'comment' =>'Number of minutes in the slot', 'unsigned' => true ]);
        $this->table->addColumn('is_active_slot',       'boolean',    ['default' => true, 'comment' =>'Be used in new schedules' ]);
        
        
    }

    /**
     * {@inheritdoc}
     */
    protected function addIndexes()
    {
         $this->table->addUniqueIndex(['timeslot_length']);
    }

    /**
     * {@inheritdoc}
     */
    protected function setPrimaryKey()
    {
        $this->table->setPrimaryKey(['timeslot_id']);
    }
}
/* End of Table */