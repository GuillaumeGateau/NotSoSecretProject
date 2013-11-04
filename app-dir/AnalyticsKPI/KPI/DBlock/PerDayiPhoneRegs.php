<?php
namespace KPI\DBlock;

class PerDayiPhoneRegs extends DBlockPerDay {

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
            INNER JOIN dimension_registration_method AS drm ON drm.id=fr.dimension_registration_method_id
            WHERE (drm.id=201 or drm.id=202) AND dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate." ".
            $whereFilters.
            "GROUP BY dt.id
            ORDER BY dt.id DESC"
        ;

        $this->setName("iPhone Regs[[iPhone Regs - Numbers of Registrations made through iPhone]]");
        $this->setGraphName("iPhone Regs");
        $this->setGraphVar("iphonreg");
        $this->setGraphLabel("iPhone Regs");
    }

}

?>
