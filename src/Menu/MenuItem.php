<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Menu;

use Valitron\Validator;

/**
 * This item which will generate into a menu link.
 * 
 * The route must be a named route 
 * Query string can be passed in as [key => value] array.
 * Each link should have an icon and help text.
 * Each link have an order which integer.
 * 
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class MenuItem implements ValidationRulesInterface
{
    
    use ValidateMenuTrait;
    
    protected $sMenuName;
    protected $sSubText;
    protected $sRouteName;
    protected $sIconName;
    protected $aQueryParams;
    protected $iOrder;
    
    
    
    public function __construct($sMenuName, $sSubText, $sRouteName, $sIconName, $iOrder = null ,array $aQueryParams = [])
    {
        $this->sMenuName = $sMenuName;
        $this->sSubText  = $sSubText;
        $this->sRouteName = $sRouteName;
        $this->sIconName  = $sIconName;
        $this->aQueryParams = $aQueryParams;
        $this->iOrder     = $iOrder;
    }
    
    
    public function getMenuName()
    {
        return $this->sMenuName;
    }
    
    public function getSubText()
    {
        return $this->sSubText;
    }
    
    public function getRouteName()
    {
        return $this->sRouteName;
    }
    
    public function getIconName()
    {
        return $this->sIconName;
    }
    
    public function getQueryParams()
    {
        return $this->aQueryParams;
    }
    
    public function getOrder()
    {
        return $this->iOrder;
    }
    
    //---------------------------------------------------------
    // Validation Interface
    
    
    
    public function getRules()
    {
          return [
            'integer' => [
                ['item_order']
            ]
            ,'min' => [
                ['item_order',1]
            ]
            ,'lengthMax' => [
                ['item_route',100],['item_name',20],['item_subtext',150],['item_icon',20]
            ]
            ,'required' => [
                ['item_name'],['item_icon'],['item_order'],['item_route']
            ]
        ];
    }
    
    
    public function getData()
    {
        return [ 
          'item_name'       => $this->sMenuName,
          'item_order'      => $this->iOrder,
          'item_icon'       => $this->sIconName,
          'item_subtext'    => $this->sSubText,
          'item_params'     => $this->aQueryParams,
          'item_route'      => $this->sRouteName,    
        ];
        
    }
    
    
    
}
/* End Class */


