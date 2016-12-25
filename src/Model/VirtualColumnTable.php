<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model;

use Bolt\Storage\Database\Schema\Table\BaseTable;
use DBALGateway\Metadata\Schema as SchemaExtension;
use Doctrine\DBAL\Schema\Schema;


abstract class VirtualColumnTable extends BaseTable
{
    
    
    public function buildTable(Schema $schema, $aliasName, $charset, $collate)
    {
           
         $oTable = parent::buildTable($schema, $aliasName, $charset, $collate);
           
         $this->addVirtualColumns();  
        
         return $oTable;
    }
    
    
     /**
     * Set the table's virtual columns.
     */
    protected function addVirtualColumns()
    {
        
    }
       
    
}
/* End of Class */