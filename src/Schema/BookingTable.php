<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Schema;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\VirtualColumnTable;


class BookingTable extends VirtualColumnTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
       
        
        $this->table->addOption('comment','Contain details on bookings');
        
        $this->table->addColumn('booking_id',   'integer',   ['notnull' => true,'comment' =>'Table Primary key', 'autoincrement' => true, 'unsigned' => true ]);
        $this->table->addColumn('schedule_id',  'integer',   ['notnull' => true,'comment' =>'FK to schedule table', 'unsigned' => true ]);
        
        $this->table->addColumn('slot_open',     'datetime',   ['notnull' => true,'comment' =>'Opening slot time' ]);
        $this->table->addColumn('slot_close',    'datetime',   ['notnull' => true,'comment' =>'Closing slot time' ]);
        
        $this->table->addColumn('registered_date',  'datetime',   ['notnull' => true,'comment' =>'Date Booking Taken' ]);
        
        
        
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
        $this->table->setPrimaryKey(['booking_id']);
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {
       $sScheduleSlotTableName = $this->tablePrefix .'bm_schedule_slot';
       
       $this->table->addForeignKeyConstraint($sScheduleSlotTableName, ['schedule_id','slot_close'], ['schedule_id','slot_close'], [], null);
       
    }
}
/* End of Table */