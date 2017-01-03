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
    protected $sGroupStyleClasses;
    
    public function __construct($sGroupName, $iOrder, $sGroupStyleClasses)
    {
        $this->iOrder       = $iOrder;
        $this->sGroupName   = $sGroupName;
        $this->aMenuItems   = [];
        $this->sGroupStyleClasses = $sGroupStyleClasses;
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
    
    public function getGroupStyle()
    {
        return $this->sGroupStyleClasses;
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
            'group_style' => $this->sGroupStyleClasses,
            
        ];
        
    }
    
    
    //-------------------------------------------------------------------------
    #IteratorAggregate
    
    public function getIterator()
    {
        return new \ArrayIterator($this->aMenuItems);
    }
    
    
    
    
    //--------------------------------------------------------------------------
    # Visitor
    
    
    public function visit(MenuVisitorInterface $oVisitor)
    {
        $oVisitor->visitMenuGroup($this);
        
        foreach($this->aMenuItems as $oItem) {
            $oItem->visit($oVisitor);
        }
        
    }
    
    
}
/* End Class */


