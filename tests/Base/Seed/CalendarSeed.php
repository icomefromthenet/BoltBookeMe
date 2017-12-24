<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Seed;

use Doctrine\DBAL\Connection;

class CalendarSeed extends BaseSeed
{
    
    
    protected $iStartYear;
    
    
    
    protected $iEndYear;
    
    
    protected function buildCalendar($iStartYear, $iEndYear)
    {
        $oDatabase     = $this->getDatabase();
        $aTableNames   = $this->getTableNames();
        
        $sCalTableName = $aTableNames['bm_calendar'];
        $aSql          = [];
        $sIntsTable    = $aTableNames['bm_ints'];
        
        $sStartDate = $iStartYear."-01-01";
        $sLastYear  = $iEndYear."-12-31";
        
        $aSql[] = " INSERT INTO $sCalTableName (calendar_date) ";
		$aSql[] = " SELECT CAST('".$sStartDate."' AS DATETIME) + INTERVAL a.i*10000 + b.i*1000 + c.i*100 + d.i*10 + e.i DAY ";
		$aSql[] = " FROM $sIntsTable a JOIN $sIntsTable b JOIN $sIntsTable c JOIN $sIntsTable d JOIN $sIntsTable e ";
		$aSql[] = " WHERE (a.i*10000 + b.i*1000 + c.i*100 + d.i*10 + e.i) <= DATEDIFF(CAST('".$sLastYear."' AS DATETIME), CAST('".$sStartDate."' AS DATETIME)) ";
		$aSql[] = " ORDER BY 1 ";
	
	    $sSql = implode(PHP_EOL,$aSql);
	    $oDatabase->executeUpdate($sSql,[]);
        
    
        
    	$aSql = [];
            
    	
    	$aSql[] =" UPDATE $sCalTableName ";
    	$aSql[] =" SET is_week_day = CASE WHEN dayofweek(calendar_date) IN (1,7) THEN 0 ELSE 1 END, ";
    	$aSql[] ="	y = YEAR(calendar_date), ";
    	$aSql[] ="	q = quarter(calendar_date), ";
    	$aSql[] ="	m = MONTH(calendar_date), ";
    	$aSql[] ="	d = dayofmonth(calendar_date), ";
    	$aSql[] ="	dw = dayofweek(calendar_date), ";
    	$aSql[] ="	month_name = monthname(calendar_date), ";
    	$aSql[] ="	day_name = dayname(calendar_date), ";
    	$aSql[] ="	w = week(calendar_date) ";
    	
        $sSql = implode(PHP_EOL,$aSql);
	    $oDatabase->executeUpdate($sSql, [], []);
        
    }
    
    
    protected function buildWeeks($iStartYear)
    {
        $oDatabase     = $this->getDatabase();
        $aTableNames   = $this->getTableNames();
        
        
        
        $sCalTableName      = $aTableNames['bm_calendar'];
        $sCalWeekTableName  = $aTableNames['bm_calendar_weeks'];
        $aSql               = [];
       
        $aSql[] =" INSERT INTO `$sCalWeekTableName` (`y`,`m`,`w`,`open_date`,`close_date`) ";
        $aSql[] =" SELECT `c`.`y`, `c`.`m`, `c`.`w`, min(`c`.`calendar_date`), max(`c`.`calendar_date`)  ";
        $aSql[] =" FROM `$sCalTableName` c ";
        $aSql[] =" WHERE `c`.`y` >= ".$iStartYear . ' ';
        $aSql[] =" GROUP BY `c`.`y`,`c`.`w` ";

        $sSql = implode(PHP_EOL,$aSql);
	    $oDatabase->executeUpdate($sSql, [], []);
        
    }
    
    
    protected function buildMonths($iStartYear)
    {
        $oDatabase     = $this->getDatabase();
        $aTableNames   = $this->getTableNames();
        
        $sCalTableName      = $aTableNames['bm_calendar'];
        $sCalMonthTableName  = $aTableNames['bm_calendar_months'];
        $aSql               = [];
       
       
        $aSql[] =" INSERT INTO `$sCalMonthTableName` (`y`,`m`,`month_name`,`m_sweek`,`m_eweek`) ";
    	$aSql[] =" SELECT `c`.`y`, `c`.`m`, max(`c`.`month_name`) as month_name ";
    	$aSql[] ="        ,min(`c`.`w`) AS a, max(`c`.`w`) AS b ";
    	$aSql[] =" FROM $sCalTableName c ";
    	$aSql[] =" WHERE `c`.`y` >= ".$iStartYear . ' ';
        $aSql[] =" GROUP BY `c`.`y`,`c`.`m` ";
           
    
        $sSql = implode(PHP_EOL,$aSql);
	    $oDatabase->executeUpdate($sSql, [], []);
       
        
    }
    
    protected function buildQuarters($iStartYear)
    {
        $oDatabase     = $this->getDatabase();
        $aTableNames   = $this->getTableNames();
        
        $sCalTableName      = $aTableNames['bm_calendar'];
        $sCalQuarTableName  = $aTableNames['bm_calendar_quarters'];
        $aSql               = [];
       
       
        $aSql[] =" INSERT INTO `$sCalQuarTableName` (`y`,`q`,`m_start`,`m_end`) ";
    	$aSql[] =" SELECT `c`.`y`,`c`.`q` ";
    	$aSql[] ="		,min(`c`.`calendar_date`) ";
    	$aSql[] ="		,max(`c`.`calendar_date`) ";
    	$aSql[] =" FROM `$sCalTableName` c ";
    	$aSql[] =" WHERE `c`.`y` >= ".$iStartYear . ' ';
        $aSql[] =" GROUP BY `c`.`y`,`c`.`q`; ";
           
    
        $sSql = implode(PHP_EOL,$aSql);
	    $oDatabase->executeUpdate($sSql, [], []);
        
        
    }
    
    
    protected function buildYears($iStartYear)
    {
        $oDatabase     = $this->getDatabase();
        $aTableNames   = $this->getTableNames();
        
        $sCalTableName      = $aTableNames['bm_calendar'];
        $sCalYearTableName  = $aTableNames['bm_calendar_years'];
        $aSql               = [];
       
       
        $aSql[] =" INSERT INTO `$sCalYearTableName` (`y`,`y_start`,`y_end`) ";
	    $aSql[] =" SELECT `c`.`y`,min(`c`.`calendar_date`),max(`c`.`calendar_date`) ";
	    $aSql[] =" FROM `$sCalTableName` c ";
	    $aSql[] =" WHERE `c`.`y` >= ".$iStartYear . ' ';
	    $aSql[] =" GROUP BY `c`.`y` ";
    
        $sSql = implode(PHP_EOL,$aSql);
	    $oDatabase->executeUpdate($sSql, [], []);
       
        
    }
    
    
    
    
    
    protected function doExecuteSeed()
    {
        $iStartYear = $this->iStartYear;
        $iEndYear   = $this->iEndYear;
       
        $this->buildCalendar($iStartYear, $iEndYear);
        $this->buildMonths($iStartYear);
        $this->buildQuarters($iStartYear);
        $this->buildWeeks($iStartYear);
        $this->buildYears($iStartYear);
        
    }
    
    
    public function __construct(Connection $oDatabase, array $aTableNames, $iStartYear, $iEndYear)
    {
       
        parent::__construct($oDatabase, $aTableNames);
        
        $this->iStartYear = $iStartYear;
        $this->iEndYear   = $iEndYear;
    }
    
    
    
    
}
/* End of Class */
