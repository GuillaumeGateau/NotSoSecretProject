<?php
namespace KPI\DBlock;

class PerDayFBRegs extends DBlockPerDay{


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
            SELECT dt.id, SUM(num_of_registrations) FROM fact_registrations AS fr
            INNER JOIN dimension_time AS dt ON dt.id=fr.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id=fr.dimension_location_id
            INNER JOIN dimension_registration_method AS drm ON drm.id=fr.dimension_registration_method_id
            WHERE (drm.id IN (101,201,301,401,501)) AND dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate." ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;

        $this->setName("FB Regs[[Facebook regs. - Number of Facebook connect registrations]]");
        $this->setGraphName("FB registrations");
        $this->setGraphVar("fb_regs");
        $this->setGraphLabel("FB regs");
    }

}

?>
