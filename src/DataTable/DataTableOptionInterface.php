<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\DataTable;

/**
 * Defines the expected interface of object that configure Datatable
 * 
 * @modified Lewis Dyer <getintouch@icomefromthenet.com>
 * @since  1.0.0
 */
interface DataTableOptionInterface
{
    /**
     * Return a key => value array with options that
     * are to be usesd to bootstrap the datatable widget
     * 
     * @return array
     */ 
    public function getStruct();
    
    
}
/* end of file */