<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model;

use Bolt\Storage\Mapping\MetadataDriver;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriver;

use Bolt\Exception\StorageException;
use Bolt\Storage\CaseTransformTrait;
use Bolt\Storage\Database\Schema\Manager;
use Bolt\Storage\Mapping\ClassMetadata as BoltClassMetadata;
use Bolt\Storage\NamingStrategy;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\Table;


/**
 * Decorates the built in MetadataDriver to add virtual fields mappings.
 * 
 * The mapping found under SchemaTable::getVirtualColumns()
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class VirtualFieldsDecorator extends MetadataDriver implements MappingDriver
{
    
    
    
    protected function loadMetadataForTable(Table $table)
    {
        parent::loadMetadataForTable($table);
        
        $tblName = $table->getName();

        if (isset($this->defaultAliases[$tblName])) {
            $className = $this->defaultAliases[$tblName];
        } else {
            $className = $tblName;
        }

        
        if(method_exists($table,'getVirtualColumns')) {
        
             foreach ($table->getVirtualColumns() as $colName => $column) {
                $mapping = [
                    'fieldname'        => $column->getName(),
                    'attribute'        => $this->camelize($column->getName()),
                    'type'             => $column->getType()->getName(),
                    'fieldtype'        => $this->getFieldTypeFor($table->getOption('alias'), $column),
                    'length'           => $column->getLength(),
                    'nullable'         => $column->getNotnull(),
                    'platformOptions'  => $column->getPlatformOptions(),
                    'precision'        => $column->getPrecision(),
                    'scale'            => $column->getScale(),
                    'default'          => $column->getDefault(),
                    'columnDefinition' => $column->getColumnDefinition(),
                    'autoincrement'    => $column->getAutoincrement(),
                ];
    
                $this->metadata[$className]['fields'][$colName] = $mapping;
    
                if (isset($this->contenttypes[$contentKey]['fields'][$colName])) {
                    $this->metadata[$className]['fields'][$colName]['data'] = $this->contenttypes[$contentKey]['fields'][$colName];
                }
            }

        }
    
    }
    
}
/* End of Column */