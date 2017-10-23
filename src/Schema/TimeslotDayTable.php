<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Schema;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\VirtualColumnTable;


class TimeslotDayTable extends VirtualColumnTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
       
        $this->table->addOption('comment','the timeslots for a given day');
        
        $this->table->addColumn('timeslot_day_id',   'integer',   ['notnull' => true,'comment' =>'Table Primary key', 'autoincrement' => true, 'unsigned' => true ]);
        $this->table->addColumn('timeslot_id',       'integer',   ['notnull' => true,'comment' =>'FK to slot table', 'unsigned' => true ]);
        $this->table->addColumn('open_minute',       'integer',   ['notnull' => true,'comment' =>'Closing Minute component', 'unsigned' => true ]);
        $this->table->addColumn('close_minute',      'integer',   ['notnull' => true,'comment' =>'Closing Minute component', 'unsigned' => true ]);
        
        
    }

    /**
     * {@inheritdoc}
     */
    protected function addIndexes()
    {
        $this->table->addUniqueIndex(['timeslot_id','close_minute']);
    }

    /**
     * {@inheritdoc}
     */
    protected function setPrimaryKey()
    {
        $this->table->setPrimaryKey(['timeslot_day_id']);
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {
       $sTimeslotTableName = $this->tablePrefix .'bm_timeslot';
       
       $this->table->addForeignKeyConstraint($sTimeslotTableName, ['timeslot_id'], ['timeslot_id'], [], null);
       
    }
}
/* End of Table */