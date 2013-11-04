<?php
namespace KPI\DBlock;

class PerDayFemRegs extends DBlockPerDay {

    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
            SELECT dt.id, SUM(num_of_registrations+num_of_registrations_disabled) FROM fact_registrations AS fr
            INNER JOIN dimension_time AS dt ON dt.id=fr.dimension_time_id
            INNER JOIN dimension_location AS dl ON dl.id=fr.dimension_location_id
            INNER JOIN dimension_demographics AS dd ON dd.id=fr.dimension_demographics_id
            WHERE dd.gender='FEMALE' AND dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate." ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;

        $this->setName("Fem Regs[[Female Registrations - Number of registrations by female users]]");
        $this->setGraphName("Female Regs");
        $this->setGraphVar("fem_regs");
        $this->setGraphLabel("Fem Regs");
    }

}

?>
