<?php
namespace KPI\DBlock;

class PerSourceCountries extends DBlockPerSource {

    private $DBlockRegsPerSrc;

    function __construct() {
        parent::__construct();

        $this->qu1 = "
            SELECT IF(source='(direct)','UNKNOWN',IF(source IS NULL,'UNKNOWN',source)) as src, country_code, SUM(num_of_registrations) FROM fact_registrations AS f
            INNER JOIN dimension_time AS dt ON dt.id=f.dimension_time_id
            INNER JOIN dimension_traffic_source AS dts ON dts.id=f.dimension_traffic_source_id
            INNER JOIN dimension_location AS dl ON dl.id = f.dimension_location_id
            WHERE dt.id = ".self::$lastDayAvailable." AND
            (source IN ('".self::$topSourcesList."') OR source='(direct)' or source IS NULL)
            GROUP BY src, country_code;
        ";

        $this->setName("Countries");
    }

    function doExecute() {
        $stmt = $this->PDO->query($this->qu1);
        $result = $stmt->fetchAll();

        $temp = array();
        foreach($result as $key=>$value) {
            if(!isset($temp[$value[0]])) {
                $temp[$value[0]] = array();
            }

            $temp[$value[0]][$value[1]] = $value[2];

        }


        foreach($temp as $src=>$srcCountries) {
            \arsort($temp[$src]);
            \array_splice($temp[$src],3);
        }


        $sources = self::$topSources;
        foreach($sources as $key=>$value) {
            $this->values[$key] = isset($temp[$key]) ? $this->mkCountryList($temp[$key]) : "";
        }
        
    }

    function mkCountryList($countryRegs) {
        $list = "";
        foreach($countryRegs as $key=>$val) {
            $list .= strtoupper($key)." (".$val.")<br/>";
        }
        if($list!="") {
            $list = \substr($list, 0, -5);
        }
        return $list;
    }

}

?>
