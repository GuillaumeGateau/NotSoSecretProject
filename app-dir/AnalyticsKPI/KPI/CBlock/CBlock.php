<?php
namespace KPI\CBlock;

abstract class CBlock {

    protected $PDO;
    
    protected $values=array();
    protected $executed=false;

    protected $filters=array();
    protected $limit;

    private $name = "";
    private $type = "";
    
    public $sN = null;
    public $sAvg = null;
    public $sVar = null;
    
    private $db_type = null;

    protected static $lastDayAvailable;
    protected static $topCountriesList;
    protected static $topCountries;

    function __construct(array $filters=null, $limit=null) {  
        if (!isset($this->PDO)) {
	            $instance = \KPI\Base\CB_DBShop::instance();
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

      /*  if (!self::$lastDayAvailable) {
            // Find most recent 7 days with available data
            $query = "
            SELECT dimension_time_id FROM fact_payments AS fp
            ORDER BY dimension_time_id DESC LIMIT 1;";
            $stmt = $this->PDO->query($query);
            $result = $stmt->fetch();
            self::$lastDayAvailable = $result[0];
        }

        if(!self::$topCountriesList) {
            $query = "SELECT country, country_code, SUM(total_revenue) FROM fact_payments AS fp
            INNER JOIN dimension_time AS dt ON dt.id=fp.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id=fp.dimension_location_id
            WHERE dt.id = ".self::$lastDayAvailable."
            GROUP BY country, country_code HAVING SUM(total_revenue)>0
            ORDER BY SUM(total_revenue) DESC
            LIMIT 7";

            $stmt = $this->PDO->query($query);
            $result = $stmt->fetchAll();
            //var_dump($result);exit;
            
            $topCountries = array();
            $topCountriesList = array();
            foreach ($result as $key => $value) {
                $topCountries[$value['country_code']] = utf8_encode($value['country']);
                $topCountriesList[] = $value['country_code'];
            }

            self::$topCountries = $topCountries;
            self::$topCountriesList = join('\',\'', $topCountriesList);
        }*/

        $this->executed = false;
    }

    function execute() {
        if(!$this->cacheGet()) {
            $this->doExecute(); 
            $this->cachePut();
        }
        if ($this->type != "CB" ) { $this->runStats(); }
        $this->executed = true;

		//var_dump($this);
        return $this;
    }
    
    function getCol() {
        if(!$this->executed) {
            $this->execute();
        }
        if ($this->type != "CB" ) { $this->runStats(); }
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
        // return unique ID for this CBlock, to store in cache
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
    
    
    function getsN() {
    	return $this->sN;
    }
    
    function getsAvg() {
    	return $this->sAvg;
    }
    
    function getsVar(){
    	return $this->sVar;
    }
    
    function runStats() {
  		if ($this->values){
  			$sN = count($this->values);

    		$sDF = $sN - 1;
    		$this->sN = $sN;				//set sample count
    		$this->sDF = $sDF;				//set degrees of freedom
    		
    		$sAvg = 0;						//reset all variables for t-calc
    		$distAvg = 0;
    		$sVarT = 0;
			$sVar = 0;
			
			foreach ($this->values as $b){	//calculate set average
				$sAvg = $sAvg + $b;
			}
			if ($sN !== 0){ $sAvg = $sAvg / $sN; }
			$this->sAvg = $sAvg;
			
			foreach ($this->values as $b){	//calculate set variance
				$sqDif = pow($b - $this->sAvg, 2);
				$distAvg = $distAvg + $sqDif;
			}
			
			$sDF !== 0 ? $sVarT = $distAvg / $sDF : $sVarT = 0;
			$sVar = sqrt($sVarT);
			$this->sVar = $sVar;
    	}
    }


    protected abstract function doExecute();
    abstract function isCachable(); //whether the block should be cached (return true or false);

    abstract function getCacheStart(); // in seconds since 0:00
    abstract function getCachePeriod(); // in seconds (with cacheStart offset)
    //abstract function cacheIsValid($lastTime);

}

?>
