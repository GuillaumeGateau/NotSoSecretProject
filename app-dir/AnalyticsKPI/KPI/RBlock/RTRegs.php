<?php
namespace KPI\RBlock;

class RTRegs extends RBlockRT {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
        SELECT 
           CAST(CURDATE() AS UNSIGNED) as dimension_time_id,
           COUNT(*) AS new_users_count
         FROM users
         WHERE users.registration_state = 2
           AND disabled = 0
           AND DATE(users.created_at) = DATE(NOW())"
           ;

        $this->setName("#Regs[[Number of registrations - Total number of registrations made today]]");
    }

}

?>