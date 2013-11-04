<?php
namespace KPI\RBlock;

class RTRNSales extends RBlockRT {

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
           COALESCE(CAST(CURDATE() AS UNSIGNED), 0)      AS dimension_time_id,
           COALESCE(ROUND(SUM(amount), 2), 0)       AS renewals_amount
         FROM
           (
             -- Adyen
             SELECT
               created_at as report_date, amount
             FROM payments
             WHERE 
               processor_code IN (0,6)
               AND status_code = 1
               AND DATE(payments.created_at) = CURDATE()

             UNION
             
             -- paypal renewals for euros
             SELECT 
               DATE(payments.created_at) AS report_date,
               COALESCE(SUM(payments.amount), 0) AS amount
             FROM payments LEFT JOIN subscriptions on payments.user_id = subscriptions.user_id
             WHERE 
               DATE(payments.created_at) = CURDATE()
               AND payments.currency = 'EUR'
               -- Temp fix for paypal RN
               AND (payments.processor_code = 11 or (payments.processor_code = 3 AND DATE(subscriptions.created_at) < CURDATE()))
               AND payments.status_code = 1

             UNION
             
             -- paypal renewals for dollars
             SELECT 
               DATE(payments.created_at) AS report_date, 
               COALESCE(SUM(payments.amount * .7 ),0) AS amount
             FROM payments LEFT JOIN subscriptions on payments.user_id = subscriptions.user_id
             WHERE 
               DATE(payments.created_at) = CURDATE()
               AND payments.currency = 'USD'
               -- Temp fix for paypal RN
               AND (payments.processor_code = 11 or (payments.processor_code = 3 AND DATE(subscriptions.created_at) < CURDATE()))
               AND payments.status_code = 1

             UNION
             
             -- paymentwall renewals
             SELECT
               payments.created_at as report_date, 
               amount
             FROM payments
             JOIN subscriptions ON subscriptions.user_id = payments.user_id
             WHERE 
               processor_code IN (7)
               AND status_code = 1
               AND DATE(payments.created_at) = CURDATE()
               AND DATE(subscriptions.created_at) < CURDATE()
           ) as renewals"
            ;

        $this->setName("€RN[[Total RN revenue per provider - Note: non euro currencies are not converted]]");
    }

}

?>