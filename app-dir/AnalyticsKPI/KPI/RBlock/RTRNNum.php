<?php
namespace KPI\RBlock;

class RTRNNum extends RBlockRT {

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
           COALESCE(CAST(CURDATE() AS UNSIGNED), 0)  AS dimension_time_id,
           COALESCE(SUM(renewals_count), 0)          AS renewals_count
         FROM
           (
             -- Adyen and Payline
             SELECT
               DATE(payments.created_at) AS report_date, COALESCE(SUM(amount), 0) as renewals_total, COUNT(*) as renewals_count
             FROM payments
             WHERE 
               processor_code IN (0,6)
               AND status_code = 1
               AND DATE(payments.created_at) = CURDATE()
             GROUP BY report_date
             
             UNION
             
             -- paypal renewals for euros
             SELECT 
               DATE(payments.created_at) AS report_date, COALESCE(SUM(amount), 0) as renewals_total, COUNT(*) as renewals_count
             FROM payments LEFT JOIN subscriptions ON subscriptions.user_id = payments.user_id
             WHERE 
               DATE(payments.created_at) = CURDATE()
               AND payments.currency = 'EUR'
               AND (payments.processor_code = 11 OR (processor_code = 3 AND DATE(subscriptions.created_at) < CURDATE()))
               AND payments.status_code = 1
             GROUP BY report_date

             UNION
             
             -- paypal renewals for dollars
             SELECT 
               DATE(payments.created_at) AS report_date, COALESCE(SUM(amount * .7), 0) as renewals_total, COUNT(*) as renewals_count
             FROM payments LEFT JOIN subscriptions ON subscriptions.user_id = payments.user_id
             WHERE 
               DATE(payments.created_at) = CURDATE()
               AND payments.currency = 'USD'
               AND (payments.processor_code = 11 OR (processor_code = 3 AND DATE(subscriptions.created_at) < CURDATE()))
               AND payments.status_code = 1
             GROUP BY report_date
             
             UNION
             
             -- paymentwall renewals
             SELECT
               DATE(payments.created_at) AS report_date, COALESCE(SUM(amount), 0) as renewals_total, COUNT(*) as renewals_count
             FROM payments LEFT JOIN subscriptions ON subscriptions.user_id = payments.user_id
             WHERE 
               processor_code IN (7)
               AND status_code = 1
               AND DATE(payments.created_at) = CURDATE()
               AND subscriptions.last_subscription_created_at != subscriptions.created_at
           ) as renewals"
            ;

        $this->setName("#RN[[Total RN per provider]]");
    }

}

?>