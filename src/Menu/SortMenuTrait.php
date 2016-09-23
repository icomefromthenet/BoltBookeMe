<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Menu;



trait SortMenuTrait
{
 
    protected function sortMenu() 
    {
        usort($this->aMenuItems,[$this,'compareMenuOrder']);    
    }
    
    protected function compareMenuOrder (MenuOrderInterface $a, MenuOrderInterface $b)
    {
        if ($a->getOrder() == $b->getOrder()) {
            return 0;
        }
        return ($a->getOrder() < $b->getOrder()) ? -1 : 1;
    }
  
}
/* End of Class */

