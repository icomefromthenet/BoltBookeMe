<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Order\Schema;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\VirtualColumnTable;


class OrderApptPackageTable extends VirtualColumnTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
       
        
        $this->table->addOption('comment','Contain the appointment order package settings');
        
        $this->table->addColumn('package_id',   'integer',   ['notnull' => true,'comment' =>'Fk to the Order Package Table',  'unsigned' => true ]);
    
        $this->table->addColumn('package_cost',     'decimal',   ['notnull' => false, 'comment' =>'Pre Tax Cost of the package', 'precision' => 13, 'scale' => 4]);        
        $this->table->addColumn('package_tax_rate', 'float',   ['notnull' => false, 'comment' =>'Tax rate to apply to package'  ]);        
           
        $this->table->addColumn('appointment_id',   'integer',   ['notnull' => true,'comment' =>'Primary key of appointment', 'unsigned' => true ]);
    
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
        $this->table->setPrimaryKey(['appointment_id']);
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {
       $sApptTableName = $this->tablePrefix .'bm_appointment';
       
       $this->table->addForeignKeyConstraint($sApptTableName, ['appointment_id'], ['appointment_id'], ["onDelete" => "CASCADE"], null);
        
        $sPackageTableName = $this->tablePrefix .'bm_order_package';
       
       $this->table->addForeignKeyConstraint($sPackageTableName, ['package_id'], ['package_id'], ["onDelete" => "CASCADE"], null);

    }
}
/* End of Table */