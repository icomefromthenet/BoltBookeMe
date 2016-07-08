<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Schema;

use Bolt\Storage\Database\Schema\Table\BaseTable;


class AppointmentTable extends BaseTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
        
        $this->table->addOption('comment','Contain details of bookings that are in conflict due to rule changes');
        
        $this->table->addColumn('booking_id',   'integer',   ['notnull' => true,'comment' =>'Table Primary key', 'autoincrement' => true, 'unsigned' => true ]);
        $this->table->addColumn('known_date',  'datetime',   ['notnull' => true,'comment' =>'Date Booking Taken' ]);
        
        
        
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
       $sBookingTableName = $this->tablePrefix .'bm_booking';
       
       $this->table->addForeignKeyConstraint($sBookingTableName, ['booking_id'], ['booking_id'], [], null);
       
    }
}
/* End of Table */