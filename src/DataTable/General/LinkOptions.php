<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\DataTable\General;

use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\DataTableOptionInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\FunctionReferenceType;


/**
 * Configures links that can be used for (C) in CRUD. 
 * 
 * This not part of DataTable JS library, but app specific
 * 
 * @modified Lewis Dyer <getintouch@icomefromthenet.com>
 * @since  1.0.0
 */
class LinkOptions implements DataTableOptionInterface
{
   /**
    * @var array
    */ 
   protected $aConfigStruct;
   
   
   
   public function __construct()
   {
       $this->aConfigStruct = [
          'crudLinks' => [],
       ];
       
   }
   
   public function addLink($sRel, $sLink) 
   {
       $this->aConfigStruct['crudLinks'][] = ['rel' => $sRel, 'link' => $sLink];
   }
   
   
   /**
    * Return the config struct
    * 
    * @return array
    */ 
   public function getStruct()
   {
       return $this->aConfigStruct;
   }
   
    
}
/* End of class */