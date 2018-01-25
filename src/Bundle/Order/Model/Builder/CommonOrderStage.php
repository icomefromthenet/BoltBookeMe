<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Order\Model\Builder;


abstract class CommonOrderStage
{
    
    const STAGE_PACKAGE     = 1;
    
    const STAGE_SURCHARGE   = 2;
    
    const STAGE_COUPON      = 3;
    
    
    abstract protected function doBeforeStage()


    abstract protected function doAfterStage()
    
    
    abstract protected function doStageAction();
    
    
    
    /**
     * Return the stage which this action Run
     * 
     * @return string
     */
    abstract public function getStage(); 
    
    
    
    public function runBeforeStage()
    {
        return $this->doBeforeStage();
        
    }
    
    
    public function runAfterStage()
    {
        return $this->doAfterStage();
    }
    
    
    public function runStageAction() 
    {
        return $this->doStageAction();
        
    }
    
}
/* End of Class */