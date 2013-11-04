<?php
namespace KPI\DBlock;

class PerDayAboutMeMovies extends DBlockPerDay{


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
            SELECT dt.id, (about_me.filled/users.users)*100
            FROM (SELECT dt.id AS dates, SUM(fup.num_of_users) AS users
            FROM fact_users_profile AS fup
            LEFT JOIN dimension_time AS dt ON dt.id = fup.dimension_registered_time_id
            LEFT JOIN dimension_location AS dl ON dl.id = fup.dimension_location_id
            WHERE fup.dimension_registered_time_id>=".self::$startDate." AND fup.dimension_registered_time_id <= ".self::$endDate." ".
            $whereFilters."
            GROUP BY dt.id) AS users
            LEFT JOIN dimension_time AS dt
            ON dt.id = users.dates
            LEFT JOIN (SELECT dt.id AS dates, SUM(fup.num_of_users_with_about_me_movies) AS filled
            FROM fact_users_profile AS fup
            LEFT JOIN dimension_time AS dt ON dt.id = fup.dimension_registered_time_id
            LEFT JOIN dimension_location AS dl ON dl.id = fup.dimension_location_id
            WHERE fup.dimension_registered_time_id>=".self::$startDate." AND fup.dimension_time_id <= ".self::$endDate." ".
            $whereFilters."
            GROUP BY dt.id) AS about_me
            ON dt.id = about_me.dates
            WHERE dt.id >= ".self::$startDate." AND dt.id <= ".self::$endDate."
            ORDER BY dt.id DESC";

        $this->setName("%Movies[[%Movies - % of users registering on this day with About Me: Movies completed on date of sign-up]]");
        $this->setType("float");
        $this->setASCIIName("%Movies");
        $this->setGraphName("%Movies");
        $this->setGraphVar("movies");
        $this->setGraphLabel("%MoviesCompleted");
    }

}

?>
