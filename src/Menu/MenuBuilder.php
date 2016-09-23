<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Menu;

use Bolt\Application;

class MenuBuilder implements \IteratorAggregate
{
    
    use SortMenuTrait;
    
    
    protected $aMenuItems;
    
    
    public function __construct()
    {
        $this->aMenuItems = [];
        
    }
    
    public function addMenuGroup(MenuGroup $oMenuGroup)
    {
        $this->aMenuItems[] = $oMenuGroup;
        
    }
    
    public function getMenuGroups()
    {
        $this->sortMenu();
        
        return $this->getIterator();
    }
    
    
    public function validate()
    {
        # pass one validate the groups
        foreach($this->getIterator() as $oMenuGroup) {
            $oMenuGroup->validate();
        }
        
        # pass two validate the item
        foreach($this->getIterator() as $oMenuGroup) {
           foreach($oMenuGroup as $oMenuItem) {
                $oMenuItem->validate();   
           }
            
        }
        
        return true;
    }
    
    
    
   //-------------------------------------------------------------------------
    #IteratorAggregate
    
    public function getIterator()
    {
        return new \ArrayIterator($this->aMenuItems);
    }
    
}
/* End of class */ 