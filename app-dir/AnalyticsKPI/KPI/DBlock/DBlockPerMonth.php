<?php
namespace KPI\DBlock;

abstract class DBlockPerMonth extends DBlock {

    private static $DATE_RANGE = 12;

    protected $qu1;
    protected $values=array();

    protected static $startDate=null;
    protected static $endDate=null;
    protected static $months=array();


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        // If date range undefined, set it to a week
        if(!self::$startDate || !self::$endDate) {
            // Find most recent 10 days with available data
            $db = new LastMonths(self::$DATE_RANGE);
            self::$endDate = $db->getEndDate();
            self::$startDate = $db->getStartDate();
            self::$months = array_keys($db->getCol());
        }
        
    }

    static function setDateRange($start, $end) {
        if(!self::checkDateFormat($start) || !self::checkDateFormat($end)) {
            return false;
        }
        self::$endDate = min(array($end,self::$endDate));
        self::$startDate = min(array($start,self::$endDate));
        self::$days = self::genDayArr(self::$startDate, self::$endDate);

        return true;
    }

    private static function checkDateFormat($date) {
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
        
        $months = self::$months;
        foreach($months as $month) {
            /*if(isset($temp[$month])) {
                if(\is_numeric($temp[$month])) {
                    $this->values[$month] = \preg_match("/^[0-9]*\.[0-9]+$/", $temp[$month]) ? \number_format($temp[$month],2,'.',' ') : \number_format($temp[$month],0,'.',' ');
                }
                else {
                    $this->values[$month] = $temp[$month];
                }
            }
            else {
                $this->values[$month] = 0;
            }*/
            $this->values[$month] = isset($temp[$month]) ? $temp[$month] : 0;
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
