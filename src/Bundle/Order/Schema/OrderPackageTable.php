<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Order\Schema;

use Bolt\Extension\IComeFromTheNet\BookMe\Model\VirtualColumnTable;


class OrderPackageTable extends VirtualColumnTable
{
    
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
       
        
        $this->table->addOption('comment','Contain details on appointment packages which used to control the custom financial cost');
        
        $this->table->addColumn('package_id',   'integer',   ['notnull' => true,'comment' =>'Table Primary key', 'autoincrement' => true, 'unsigned' => true ]);

        $this->table->addColumn('created_on',    'datetime',   ['notnull' => true,'comment' =>'Date package was created' ]);
        $this->table->addColumn('updated_on',    'datetime',   ['notnull' => true,'comment' =>'Date last package update' ]);
        
        $this->table->addColumn('package_name',    'string',   ['notnull' => true,  'comment' =>'Name for this package', 'length' => 100 ]);
        $this->table->addColumn('package_desc',   'string',   ['notnull' => false, 'comment' =>'A short Description', 'length' => 255 ]);        
        
        $this->table->addColumn('package_cost',     'decimal',   ['notnull' => false, 'comment' =>'Pre Tax Cost of the package', 'precision' => 13, 'scale' => 4]);        
        $this->table->addColumn('package_tax_rate', 'float',   ['notnull' => false, 'comment' =>'Tax rate to apply to package'  ]);        
        
        $this->table->addColumn('package_disabled', 'boolean',   ['default' => true, 'comment' =>'Pakage allowed for new appointments'  ]);        
        
        
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
        $this->table->setPrimaryKey(['package_id']);
    }
    
    /**
     * Set the table's foreign key constraints.
     */
    protected function addForeignKeyConstraints()
    {
       
    }
}
/* End of Table */

