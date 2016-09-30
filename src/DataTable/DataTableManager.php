<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\DataTable;

use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\DataTableConfigInterface;

/**
 * Defines the expected interface of object that configure Datatable
 * 
 * @modified Lewis Dyer <getintouch@icomefromthenet.com>
 * @since  1.0.0
 */
class DataTableManager
{
    
    /**
     * @var Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output
     */ 
    protected $oOutput;
    
    /**
     * @var Bolt\Extension\IComeFromTheNet\BookMe\DataTable\DataTableConfigInterface
     */ 
    protected $oConfig;
    
    /**
     * Fetch the dataTable config writer
     * 
     * @return Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output
     */ 
    public function getOutputWriter()
    {
        return $this->oOutput;
    }
    
     /**
     * Fetch the dataTable config container
     * 
     * @return Bolt\Extension\IComeFromTheNet\BookMe\DataTable\DataTableConfigInterface
     */ 
    public function getTableConfig()
    {
        return $this->oConfig;
    }
    
    
    public function __construct(Output $oOutput, DataTableConfigInterface $oConfig)
    {
        $this->oOutput = $oOutput;
        $this->oConfig = $oConfig;
    }
    
    
    /**
     * 
     * 
     */ 
    public function write()
    {
        
        
    }
    
}
/* End of Interface */