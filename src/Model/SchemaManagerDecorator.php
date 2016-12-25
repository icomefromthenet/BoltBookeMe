<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model;

use DBALGateway\Metadata\Schema as SchemaExtension;
use Bolt\Storage\Database\Schema\Manager;

class SchemaManagerDecorator extends Manager
{
 
    protected $oApp;
 
 
    public function setApp($oApp)
    {
        $this->oApp = $oApp;
    }
    
    
    /**
     * Get a merged array of tables.
     *
     * @return \Doctrine\DBAL\Schema\Table[]
     */
    public function getSchemaTables()
    {
        if ($this->schemaTables !== null) {
            return $this->schemaTables;
        }

        /** @deprecated Deprecated since 3.0, to be removed in 4.0. */
        $this->oApp['schema.builder']['extensions']->addPrefix($this->oApp['schema.prefix']);

        $schema = new SchemaExtension();
        $tables = array_merge(
            $this->oApp['schema.builder']['base']->getSchemaTables($schema),
            $this->oApp['schema.builder']['content']->getSchemaTables($schema, $this->config),
            $this->oApp['schema.builder']['extensions']->getSchemaTables($schema)
        );
        $this->schema = $schema;

        return $tables;
    }

}
/* End of File */ 