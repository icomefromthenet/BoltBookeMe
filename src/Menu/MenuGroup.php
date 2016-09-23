<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Menu;

/**
 * This group contain menu items.
 * 
 * Each group will have a name.
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class MenuGroup implements ValidationRulesInterface, MenuOrderInterface, \IteratorAggregate
{
    
    use ValidateMenuTrait;
    use SortMenuTrait;
    
    protected $aMenuItems;
    protected $iOrder;
    protected $sGroupName;
    
    
    public function __construct($sGroupName, $iOrder)
    {
        $this->iOrder       = $iOrder;
        $this->sGroupName   = $sGroupName;
        $this->aMenuItems   = [];
    }

    
    public function addMenuItem(MenuItem $oItem)
    {
        $this->aMenuItems[] = $oItem;
    }
    
    
    public function getMenuItems()
    {
        $this->sortMenu();
        
        return  $this->getIterator();
    }
    
    public function getOrder()
    {
        return $this->iOrder;
    }
    
    
    public function getGroupName()
    {
        return $this->sGroupName;
    }
    
  
    
    //---------------------------------------------------------
    // Validation Interface
    
    
    public function getRules()
    {
         return [
            'integer' => [
                ['group_order']
            ]
            ,'min' => [
                ['group_order',1]
            ]
            ,'lengthMax' => [
                ['group_name',100]
            ]
            ,'required' => [
                ['group_name'],['group_order']
            ]
        ];
    }
    
    
    public function getData()
    {
        return [
            'group_name'  => $this->sGroupName, 
            'group_order' => $this->iOrder,
            
        ];
        
    }
    
    
    //-------------------------------------------------------------------------
    #IteratorAggregate
    
    public function getIterator()
    {
        return new \ArrayIterator($this->aMenuItems);
    }
}
/* End Class */


