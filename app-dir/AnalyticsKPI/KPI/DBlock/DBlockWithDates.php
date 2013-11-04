<?php
namespace KPI\DBlock;

abstract class DBlock {

    protected $PDO;
    
    protected $values=array();
    protected $executed=false;

    protected $filters=array();
    protected $limit;

    private $name = "";
    private $type = "";

    protected static $lastDayAvailable;
    protected static $topCountriesList;
    protected static $topCountries;

    protected static $startDate=null;
    protected static $endDate=null;
    protected static $days=array();

    function __construct(array $filters=null, $limit=null) {
        if (!isset($this->PDO)) {
            $instance = \KPI\Base\DBShop::instance();
            $this->PDO = $instance->getPDO();
        }

        if($filters!=null) {
            $this->filters = $filters;
        }

        if($limit!==null) {
            $this->limit = $limit;
        }
        else {
            $this->limit = 10;
        }

        if (!self::$lastDayAvailable) {
            // Find most recent 7 days with available data
            $query = "
            SELECT dimension_time_id FROM fact_payments AS fp
            ORDER BY dimension_time_id DESC LIMIT 1;";
            $stmt = $this->PDO->query($query);
            $result = $stmt->fetch();
            self::$lastDayAvailable = $result[0];
        }

        // If date range undefined, set it to a week
        if(!self::$startDate || !self::$endDate) {
            // Find most recent 10 days with available data
            self::setDefaultDates();
        }

        $this->executed = false;
    }

    function execute() {
        if(!$this->cacheGet()) {
            $this->doExecute();            
            $this->cachePut();
        }
        $this->executed = true;
        return $this;
    }
    
    function getCol() {
        if(!$this->executed) {
            $this->execute();
        }
        return $this->values;
    }

    function getV($key) {
        
        if(!$this->executed) {
            $this->execute();
        }
        return \array_key_exists($key, $this->values) ? $this->values[$key] : 0;
    }


    static function getTopCountries() {
        return isset(self::$topCountries) ? self::$topCountries : array();
    }

    static function getLastDayAvailable() {
        if(!self::$lastDayAvailable) {
            // Find most recent 7 days with available data
            $db = new LastDays();
            self::$lastDayAvailable = $db->getEndDate();
        }
        return self::$lastDayAvailable;
    }

    function setName($name) {
        $this->name = $name;
    }

    function getName() {
        return $this->name;
    }


    function cachePut() {
        if(!$this->isCachable()) {
            return;
        }
        
        $cachePath = \KPI\base\ApplicationConfig::getValue('cachePath');
        $id = $this->getCacheId();
        \file_put_contents($cachePath.$id, \serialize(array("data"=>$this->values,"day"=>self::$lastDayAvailable)));

    }

    function cacheGet() {
        if(!$this->isCachable()) {
            return false;
        }

        $cachePath = \KPI\base\ApplicationConfig::getValue('cachePath');
        $id = $this->getCacheId();
        
        $filemtime = @filemtime($cachePath.$id); // num of seconds since 1970
        if(!$filemtime) { 
            // file no exist. No cache for this.
            return false;
        }


        
        $cachePeriod = $this->getCachePeriod();
        if(time()-$filemtime >= $cachePeriod) {
            // cache has expired
            return false;
        }

        $cacheStart = $this->getCacheStart();
        if($cacheStart) {
            $unixStart = strtotime(date('m/d/Y').' 00:00:00')+$cacheStart;
            if($filemtime < $unixStart && $unixStart < time()) {
                return false;
            }
        }

        $ser = \file_get_contents($cachePath.$id, FILE_USE_INCLUDE_PATH);
        if(!$ser) {
            return false;
        }
        $data = \unserialize($ser);

        $cacheDay = $data["day"];
        if(self::$lastDayAvailable > $cacheDay) {

            return false;
        }

        
        $this->values = $data["data"];
        return true;
    }

    function getCacheId() {
        // return unique ID for this DBlock, to store in cache
        //echo \end(explode('\\', \get_class($this)))."-".$this->qu1."<br/>";
        $id = \end(explode('\\', \get_class($this)))."-".md5($this->qu1);

        return $id;
    }

    function getType() {
        return $this->type;
    }

    function setType($val) {
        $this->type = $val;
    }


    protected abstract function doExecute();
    abstract function isCachable(); //whether the block should be cached (return true or false);

    abstract function getCacheStart(); // in seconds since 0:00
    abstract function getCachePeriod(); // in seconds (with cacheStart offset)
    //abstract function cacheIsValid($lastTime);

}

?>
