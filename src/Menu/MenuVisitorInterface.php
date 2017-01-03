<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Menu;


/**
 * Interface for the Visitors used during menu generation.
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 * 
 */ 
interface MenuVisitorInterface 
{
    
    public function visitMenuGroup(MenuGroup $oGroup);

    
    public function visitMenuItem(MenuItem $oItem);
    
    
}
/* End of File */