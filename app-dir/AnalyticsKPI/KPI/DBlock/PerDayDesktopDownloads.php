<?php
namespace KPI\DBlock;

class PerDayDesktopDownloads extends DBlockPerDay {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
        SELECT dt.id, SUM(fd.num_of_downloads) 
            FROM fact_downloads AS fd
            LEFT JOIN dimension_time AS dt ON dt.id = fd.dimension_time_id
            LEFT JOIN dimension_location AS dl ON dl.id = fd.dimension_location_id
            WHERE dimension_app_id = 5 AND dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate." ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;

        $this->setName("Desktop Downloads[[Successful downloads of the desktop app]]");
    }

}

?>