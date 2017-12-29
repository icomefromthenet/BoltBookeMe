<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Fixture;

use DateTime;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Seed\NewRuleSeed;
use Bolt\Extension\IComeFromTheNet\BookMe\Tests\Base\Seed\NewRuleSeriesSeed;

class NewRuleFixture extends BaseFixture
{
   
   
   
    
    public function runFixture(array $aAppConfig, DateTime $oNow)
    {
      
        $oDatabase   = $this->getDatabaseAdapter();
        $aTableNames = $this->getTableNames();
      
        $oSingleDate = clone $oNow;
        $oSingleDate->setDate($oNow->format('Y'),1,14);
        
        $oDayWorkDayRuleStart = clone $oNow;
        $oDayWorkDayRuleStart->setDate($oNow->format('Y'),1,1);
        
        $oDayWorkDayRuleEnd = clone $oNow;
        $oDayWorkDayRuleEnd->setDate($oNow->format('Y'),12,31);
        
        $oHolidayStart = clone $oNow;
        $oHolidayStart->setDate($oNow->format('Y'),8,7);
        
        $oHolidayEnd   = clone $oNow; 
        $oHolidayEnd->setDate($oNow->format('Y'),8,14);
      
        $iNineAmSlot = (12*9) *5;
        $iFivePmSlot = (12*17)*5;
        $iTenPmSlot  = (12*20)*5;    
      
        $iMidaySlot = (12*12)*5;
        $iOnePmSlot = (12*13)*5;
          
        $iEightPmSlot  = (12*18)*5;
        $iEightThirtyPmSlot = ((12*18) + 6)*5;
        
          
        $aNewRulesData =
        [
            'iRepeatWorkDayRule' => [
                'RULE_TYPE_ID'      => $aAppConfig['iRepeatWorkDayRule']['RULE_TYPE_ID'],
                'REPEAT_MINUTE'     => '*',
                'REPEAT_HOUR'       => '*',
                'REPEAT_DAYOFWEEK'  => '1-5',
                'REPEAT_DAYOFMONTH' => '*',
                'REPEAT_MONTH'      => '2-12',
                'REPEAT_WEEKOFYEAR' => '*',
                'START_FROM'        => $oDayWorkDayRuleStart,
                'END_AT'            => $oDayWorkDayRuleEnd,
                'TIMESLOT_ID'       => $aAppConfig['iRepeatWorkDayRule']['TIMESLOT_ID'],
                'OPEN_SLOT'         => $iNineAmSlot,
                'CLOSE_SLOT'        => $iFivePmSlot,
                'CAL_YEAR'          => $oDayWorkDayRuleStart->format('Y'),
                'IS_SINGLE_DAY'     => false,
                'RULE_NAME'         => 'Repeat Work Day Rule A',
                'RULE_DESC'         => 'Repeat Work Day Rule A Desc',
                
                'MonthRanges'    => ['RANGE' => ['2,3,4,5,6,7,8,9,10,11,12'], 'MOD'=> 1],
                'DayMonthRanges' => null,
                'DayWeekRanges'  => ['RANGE' => ['2,3,4,5,6'], 'MOD'=> 1],
                'WeekYearRanges' => null
                
            ],
            
            'iSingleWorkDayRule' => [
                'RULE_TYPE_ID'      => $aAppConfig['iSingleWorkDayRule']['RULE_TYPE_ID'],
                'REPEAT_MINUTE'     => '*',
                'REPEAT_HOUR'       => '*',
                'REPEAT_DAYOFWEEK'  => '*',
                'REPEAT_DAYOFMONTH' => '*',
                'REPEAT_MONTH'      => '*',
                'REPEAT_WEEKOFYEAR' => '*',
                'START_FROM'        => $oSingleDate,
                'END_AT'            => $oSingleDate,
                'TIMESLOT_ID'       => $aAppConfig['iSingleWorkDayRule']['TIMESLOT_ID'],
                'OPEN_SLOT'         => $iFivePmSlot,
                'CLOSE_SLOT'        => $iTenPmSlot,
                'CAL_YEAR'          => $oSingleDate->format('Y'),
                'IS_SINGLE_DAY'     => true,
                'RULE_NAME'         => 'Single Work Day Rule A',
                'RULE_DESC'         => 'Single Work Day Rule A Desc',
                
                'MonthRanges'    => null,
                'DayMonthRanges' => null,
                'DayWeekRanges'  => null,
                'WeekYearRanges' => null
            
            ],
            
            'iRepeatBreakRule' => [
                'RULE_TYPE_ID'      => $aAppConfig['iRepeatBreakRule']['RULE_TYPE_ID'],
                'REPEAT_MINUTE'     => '*',
                'REPEAT_HOUR'       => '*',
                'REPEAT_DAYOFWEEK'  => '1-5',
                'REPEAT_DAYOFMONTH' => '*',
                'REPEAT_MONTH'      => '2-12',
                'REPEAT_WEEKOFYEAR' => '*',
                'START_FROM'        => $oDayWorkDayRuleStart,
                'END_AT'            => $oDayWorkDayRuleEnd,
                'TIMESLOT_ID'       => $aAppConfig['iRepeatBreakRule']['TIMESLOT_ID'],
                'OPEN_SLOT'         => $iMidaySlot,
                'CLOSE_SLOT'        => $iOnePmSlot,
                'CAL_YEAR'          => $oDayWorkDayRuleStart->format('Y'),
                'IS_SINGLE_DAY'     => false,
                'RULE_NAME'         => 'Repeat Break Rule A',
                'RULE_DESC'         => 'Repeat Break Rule A Desc',
                
                'MonthRanges'    => ['RANGE' => ['2,3,4,5,6,7,8,9,10,11,12'], 'MOD'=> 1],
                'DayMonthRanges' => null,
                'DayWeekRanges'  => ['RANGE' => ['2,3,4,5,6'], 'MOD'=> 1],
                'WeekYearRanges' => null
            
            ],
            
            
            'iSingleBreakRule' =>[
                'RULE_TYPE_ID'      => $aAppConfig['iSingleBreakRule']['RULE_TYPE_ID'],
                'REPEAT_MINUTE'     => '*',
                'REPEAT_HOUR'       => '*',
                'REPEAT_DAYOFWEEK'  => '*',
                'REPEAT_DAYOFMONTH' => '*',
                'REPEAT_MONTH'      => '*',
                'REPEAT_WEEKOFYEAR' => '*',
                'START_FROM'        => $oSingleDate,
                'END_AT'            => $oSingleDate,
                'TIMESLOT_ID'       => $aAppConfig['iSingleBreakRule']['TIMESLOT_ID'],
                'OPEN_SLOT'         => $iEightPmSlot,
                'CLOSE_SLOT'        => $iEightThirtyPmSlot,
                'CAL_YEAR'          => $oSingleDate->format('Y'),
                'IS_SINGLE_DAY'     => true,
                'RULE_NAME'         => 'Singe Break Rule A',
                'RULE_DESC'         => 'Singe Break Rule A Desc',
                
                'MonthRanges'    => null,
                'DayMonthRanges' => null,
                'DayWeekRanges'  => null,
                'WeekYearRanges' => null
            
                
            ],
            
            'iRepeatHolidayRule' => [
                'RULE_TYPE_ID'      => $aAppConfig['iRepeatHolidayRule']['RULE_TYPE_ID'],
                'REPEAT_MINUTE'     => '*',
                'REPEAT_HOUR'       => '*',
                'REPEAT_DAYOFWEEK'  => '*',
                'REPEAT_DAYOFMONTH' => '28-30',
                'REPEAT_MONTH'      => '*',
                'REPEAT_WEEKOFYEAR' => '*',
                'START_FROM'        => $oDayWorkDayRuleStart,
                'END_AT'            => $oDayWorkDayRuleEnd,
                'TIMESLOT_ID'       => $aAppConfig['iRepeatHolidayRule']['TIMESLOT_ID'],
                'OPEN_SLOT'         => $iNineAmSlot,
                'CLOSE_SLOT'        => $iFivePmSlot,
                'CAL_YEAR'          => $oDayWorkDayRuleStart->format('Y'),
                'IS_SINGLE_DAY'     => false,
                'RULE_NAME'         => 'Repeat Holiday Rule A',
                'RULE_DESC'         => 'Repeat Holiday Rule A Desc',
                
                'MonthRanges'    => null,
                'DayMonthRanges' => ['RANGE' => [28,29,30], 'MOD'=> 1],
                'DayWeekRanges'  => null,
                'WeekYearRanges' => null
            
            ],
            
            
            'iSingleHolidayRule' => [
               'RULE_TYPE_ID'      => $aAppConfig['iSingleHolidayRule']['RULE_TYPE_ID'],
                'REPEAT_MINUTE'     => '*',
                'REPEAT_HOUR'       => '*',
                'REPEAT_DAYOFWEEK'  => '*',
                'REPEAT_DAYOFMONTH' => '*',
                'REPEAT_MONTH'      => '*',
                'REPEAT_WEEKOFYEAR' => '*',
                'START_FROM'        => $oHolidayStart,
                'END_AT'            => $oHolidayStart,
                'TIMESLOT_ID'       => $aAppConfig['iSingleHolidayRule']['TIMESLOT_ID'],
                'OPEN_SLOT'         => $iNineAmSlot,
                'CLOSE_SLOT'        => $iFivePmSlot,
                'CAL_YEAR'          => $oHolidayStart->format('Y'),
                'IS_SINGLE_DAY'     => true,
                'RULE_NAME'         => 'Single Holiday Rule A',
                'RULE_DESC'         => 'Single Holiday Rule A Desc',
                
                'MonthRanges'    => null,
                'DayMonthRanges' => null,
                'DayWeekRanges'  => null,
                'WeekYearRanges' => null
            
            ],
            
            
            'iRepeatOvertimeRule' => [
                'RULE_TYPE_ID'      => $aAppConfig['iRepeatOvertimeRule']['RULE_TYPE_ID'],
                'REPEAT_MINUTE'     => '*',
                'REPEAT_HOUR'       => '*',
                'REPEAT_DAYOFWEEK'  => '*',
                'REPEAT_DAYOFMONTH' => '28-30',
                'REPEAT_MONTH'      => '*',
                'REPEAT_WEEKOFYEAR' => '*',
                'START_FROM'        => $oDayWorkDayRuleStart,
                'END_AT'            => $oDayWorkDayRuleEnd,
                'TIMESLOT_ID'       => $aAppConfig['iRepeatOvertimeRule']['TIMESLOT_ID'],
                'OPEN_SLOT'         => $iNineAmSlot,
                'CLOSE_SLOT'        => $iFivePmSlot,
                'CAL_YEAR'          => $oDayWorkDayRuleStart->format('Y'),
                'IS_SINGLE_DAY'     => false,
                'RULE_NAME'         => 'Repeat Overtime Rule A',
                'RULE_DESC'         => 'Repeat Overtime Rule A Desc',
                
                'MonthRanges'    => null,
                'DayMonthRanges' => ['RANGE' => [28,29,30], 'MOD'=> 1],
                'DayWeekRanges'  => null,
                'WeekYearRanges' => null
            
            ],
            
            'iSingleOvertimeRule' => [
                'RULE_TYPE_ID'      => $aAppConfig['iSingleOvertimeRule']['RULE_TYPE_ID'],
                'REPEAT_MINUTE'     => '*',
                'REPEAT_HOUR'       => '*',
                'REPEAT_DAYOFWEEK'  => '*',
                'REPEAT_DAYOFMONTH' => '*',
                'REPEAT_MONTH'      => '*',
                'REPEAT_WEEKOFYEAR' => '*',
                'START_FROM'        => $oHolidayStart,
                'END_AT'            => $oHolidayStart,
                'TIMESLOT_ID'       => $aAppConfig['iSingleOvertimeRule']['TIMESLOT_ID'],
                'OPEN_SLOT'         => $iNineAmSlot,
                'CLOSE_SLOT'        => $iFivePmSlot,
                'CAL_YEAR'          => $oHolidayStart->format('Y'),
                'IS_SINGLE_DAY'     => true,
                'RULE_NAME'         => 'Single Overtime Rule A',
                'RULE_DESC'         => 'Single Overtime Rule A desc',
                
                'MonthRanges'    => null,
                'DayMonthRanges' => null,
                'DayWeekRanges'  => null,
                'WeekYearRanges' => null
            
            ],
        ];
        
        $oNewRuleSeed = new NewRuleSeed($oDatabase, $aTableNames, $aNewRulesData);
      
        $aNewRules = $oNewRuleSeed->executeSeed();
      
        
        foreach($aNewRules as $sKey => $iRuleId) {
            $aNewRulesData[$sKey]['RULE_ID'] = $iRuleId;
        }
        
        
        $oNewRuleSeriesSeed = new NewRuleSeriesSeed($oDatabase, $aTableNames, $aNewRulesData);
        
        $oNewRuleSeriesSeed->executeSeed();
      
        return $aNewRules;
        
    }
    
   
    
}
/* End of Class */
