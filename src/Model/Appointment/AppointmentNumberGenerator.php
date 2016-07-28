<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Model\Appointment;

/**
 * Generate human friendly appointment numbers
 * 
 * The Prefix, Suffix and starting seed will all come from extension config
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */
class AppointmentNumberGenerator
{
    
    protected $sPrefix;
    
    protected $sSuffix;
    
    protected $iStartingIndex;
    
    
    /**
     * Class Constructor
     * 
     * @param   string  $sPrefix            
     * @param   string  $sSuffix 
     * @param   integer $iStartingIndex 
     */ 
    public function __construct($sPrefix,$sSuffix, $iStartingIndex)
    {
        $this->sPrefix        = $sPrefix;
        $this->sSuffix        = $sSuffix;
        $this->iStartingIndex = $iStartingIndex;
    }
    
    
    /**
     * Return a human friendly appointment number
     * 
     * @param integer $iApptDatabaseIndex   Seed index from database table
     * @return string an appointmen number
     */
    public function getApptNumber($iApptDatabaseIndex)
    {
        return $this->sPrefix.''.($iApptDatabaseIndex+$this->iStartingIndex).''.$this->sSuffix;
    }
    
}
/* End of File */

