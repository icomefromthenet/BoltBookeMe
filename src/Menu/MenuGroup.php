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
class MenuGroup implements ValidationRulesInterface
{
    
    use ValidateMenuTrait;
    
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
        
        return $this->aMenuItems;
    }
    
    public function getOrder()
    {
        return $this->iOrder;
    }
    
    
    public function getGroupName()
    {
        return $this->sGroupName;
    }
    
    //---------------------------------------------------
    # Sort Helpers
    
    protected function sortMenu() 
    {
        usort($this->aMenuItems,[$this,'compareMenuItemOrder']);    
    }
    
    public function compareMenuItemOrder($a, $b)
    {
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    }
    
    //---------------------------------------------------------
    // Validation Interface
    
    
    public function getRules()
    {
        
    }
    
    
    public function getData()
    {
        return [
            'group_name'  => $this->sGroupName, 
            'group_order' => $this->iOrder,
            
        ];
        
    }
    
}
/* End Class */


