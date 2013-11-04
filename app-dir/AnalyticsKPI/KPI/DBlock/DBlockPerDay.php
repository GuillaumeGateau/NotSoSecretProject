<?php
namespace KPI\DBlock;

abstract class DBlockPerDay extends DBlock {

    private static $DATE_RANGE = 10;

    protected $qu1;
    protected $qu2;
    protected $values=array();

    protected static $startDate=null;
    protected static $endDate=null;
    protected static $days=array();
    

    function __construct(array $filters=null, $limit=null) {

        parent::__construct($filters,$limit);

        // If date range undefined, set it to a week
        if(!self::$startDate || !self::$endDate) {
            // Find most recent 10 days with available data
            self::setDefaultDates();
        }
        
    }

    static function setDateRange($start, $end) {
        if(!self::$startDate || !self::$endDate) {
            self::setDefaultDates();
        }
        if (self::checkDateFormat($start) && self::checkDateFormat($end)) {
            self::$endDate = min(array($end, self::$endDate));
            self::$startDate = min(array($start, self::$endDate));
            self::$days = self::genDayArr(self::$startDate, self::$endDate);
        }
        return true;
    }

    static function setDefaultDates() {
        $db = new LastDays(self::$DATE_RANGE);
        self::$endDate = $db->getEndDate();
        self::$startDate = $db->getStartDate();
        self::$days = self::genDayArr(self::$startDate, self::$endDate);
    }

    static function getDefaultDates() {
        return array(self::$startDate,self::$endDate);
    }

    private static function genDayArr($start, $end) {
        $days = array();
        $days[] = $end;
        $date = \DateTime::createFromFormat('Ymd', $end);
        $dayInterval = new \DateInterval("P1D");
        do {
            $date->sub($dayInterval);
            $d = $date->format('Ymd');
            $days[] = $d;
        } while((int) $d  > (int) $start);
        
        return $days;
    }

    private static function checkDateFormat($date) {
        if(!$date) {
            return false;
        }
         if (\preg_match("/^[0-9]{8}$/", $date)) {
            $year = (int) \substr($date, 0, 4);
            $month = (int) \substr($date, 4,2);
            $day = (int) \substr($date, 6,2);
            if($year>2000 && $year<2200) {
                if ($month >= 1 && $month <= 12) {
                    $monthsArr = array(31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
                    if ($day >= 1 && $day <= $monthsArr[$month - 1]) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    function doExecute() {
        $stmt = $this->PDO->query($this->qu1);
        $result = $stmt->fetchAll();
	
	    $temp = array();
        foreach($result as $key=>$value) {
        $temp[$value[0]] = $value[1];
            
        }
        
        $days = self::$days;
        foreach($days as $day) {
            $this->values[$day] = isset($temp[$day]) ? $temp[$day] : 0;
        }
        
    }

    function isCachable() {
        return true;
    }

    function getCacheStart() {
        return 18000;
    }

    function getCachePeriod() {
        return 86400;
    }
    

    
}

?>
