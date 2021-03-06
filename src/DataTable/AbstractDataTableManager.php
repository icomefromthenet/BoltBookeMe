<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\DataTable;

use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\Output;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Schema\ColumnSchema;
use Bolt\Extension\IComeFromTheNet\BookMe\Model\Member\ActionRoute;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


/**
 * Defines the expected interface of object that configure Datatable
 * 
 * @modified Lewis Dyer <getintouch@icomefromthenet.com>
 * @since  1.0.0
 */
abstract class AbstractDataTableManager implements DataTableConfigInterface
{
    
    /**
     * @var Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output
     */ 
    protected $oOutput;
    
    /**
     * @var Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Schema\ColumnSchema
     */ 
    protected $oSchema;
    
    /**
     * @var array[DataTableOptionInterface]
     */ 
    protected $aGeneral;
    
    /**
     * @var array[DataTableOptionInterface]
     */ 
    protected $aPlugins;
    
    /**
     * @var Bolt\Extension\IComeFromTheNet\BookMe\DataTable\DataTableEventRegistry
     */ 
    protected $oEventRegistry;
    
    /**
     * @var Symfony\Component\Routing\Generator\UrlGeneratorInterface;
     */ 
    protected $oUrlGenerator;
    
    
    /**
    * Implemented to allow default to be set in instances
    * 
    * @return void
    */ 
    abstract public function setDefaults();


    /**
     * Fetch the route from a url name using the
     * generator, this only helpful for route that dont require substitutions
     * 
     * @param string    $sRouteName     The Route Name
     * @return string the url
     */
    protected function getRoute($sRouteName)
    {
       return $this->getUrlGenerator()->generate($sRouteName,[]);
    }

    
    //------------------------------------------
    # API to add Config Options
    
    
    public function addPlugin(DataTableOptionInterface $oOption)
    {
       $this->aPlugins[] = $oOption;
       
       return $this;
    }
    
    public function addOptionSet(DataTableOptionInterface $oOption)
    {
       $this->aGeneral[] = $oOption;
       
       return $this;
    }
    
    public function addSchema(DataTableOptionInterface $oOption)
    {
       $this->oSchema = $oOption;
       
       return $this;
    }
    
    
    //-------------------------------------------
    # Option Access to allow later modification
    
    /**
     * Return the given plugin option set
     * 
     * @param string    $sPluginSet     The class name of the plugin option class
     * @return DataTableOptionInterface The option set if found
     */
    public function getPlugin($sPluginSet)
    {
        foreach($this->aPlugins as $oOption) {
            $sClassBaseName = substr(strrchr(get_class($oOption), '\\'), 1);
            
            if($sClassBaseName === $sPluginSet) {
                return $oOption;
            }
        }

    }
    
    /**
     * Return the given option set
     * 
     * @param string    $sOptionSet     The class name of the option set
     * @return DataTableOptionInterface The option set if found
     */ 
    public function getOptionSet($sOptionSet)
    {
       foreach($this->aGeneral as $oOption) {
           $sClassBaseName = substr(strrchr(get_class($oOption), '\\'), 1);
            
            if($sClassBaseName === $sOptionSet) {
                return $oOption;
            }
       }
    }
    
    
    /**
     * Return the column schema
     * 
     * @return Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Schema\ColumnSchema
     */ 
    public function getSchema()
    {
        return $this->oSchema;
    }
    
    /**
     * Return the event registry
     * 
     * @return Bolt\Extension\IComeFromTheNet\BookMe\DataTable\DataTableEventRegistry
     */ 
    public function getEventRegistry()
    {
       return $this->oEventRegistry;
    }

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
     * Return the Url Generator
     * 
     * @return Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */ 
    public function getUrlGenerator()
    {
        return $this->oUrlGenerator;
    }

    
    public function __construct(Output $oOutput, UrlGeneratorInterface $oUrl)
    {
        $this->oOutput        = $oOutput;
        $this->oEventRegistry = new DataTableEventRegistry();
        $this->oSchema        = new ColumnSchema();
        $this->oUrlGenerator  = $oUrl;
        
        $this->setDefaults();
        
    }
    
    /**
     * Return the config struct
     * 
     * @return the config as array struct
     */ 
    public function getStruct()
    {
        $aConfigArray = [];
        
        foreach($this->aGeneral as $oOption) {
            $aConfigArray = array_merge($aConfigArray,$oOption->getStruct());
        }
        
        foreach($this->aPlugins as $oOption) {
            $aConfigArray = array_merge($aConfigArray,$oOption->getStruct());
        }
        
        
        $aConfigArray = array_merge($aConfigArray,$this->oSchema->getStruct());
        
        return $aConfigArray;
    }
    
    /**
     * Return the merged config as string
     * 
     * @return string the config object ready to template
     */ 
    public function writeConfig()
    {
        $aConfigArray = $this->getStruct();
        return $this->oOutput->write($aConfigArray)->bytes();
    }
    
    
    /**
     * Fetch flat list of events that need to be added to the datatable plugin at runtime
     * 
     * @return array
     */ 
    public function getEvents()
    {
        return $this->getEventRegistry()->getRegistry();
    }
    
    
}
/* End of Interface */