<?php
namespace KPI\RBlock;

class RTCountByProvider extends RBlockRT {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
        SELECT  CONCAT(payment_status, ' - ', payment_provider) as provider, SUM(payments_count) AS total_count FROM (

             SELECT * FROM (
                 SELECT
                   CURDATE()        AS report_day,
                   CASE status_code
                     WHEN 1 THEN 'Authorised'
                     ELSE 'Refused'
                   END              AS payment_status,
                   CASE processor_code
                     WHEN 0 THEN 'Adyen RN'
                     WHEN 1 THEN 'Adyen'
                     WHEN 2 THEN 'Adyen Ideal'
                     WHEN 3 THEN 'Paypal'
                     WHEN 4 THEN 'Adyen Mistercash'
                     WHEN 5 THEN 'Payline'
                     WHEN 6 THEN 'Payline RN'
                     WHEN 7 THEN 'Paymentwall'
                     WHEN 8 THEN 'Payline Micropayments'
					 WHEN 11 THEN 'Paypal RN'
                     ELSE 'unknown source'
                   END              AS payment_provider,
                   COUNT(*)         AS payments_count,
                   SUM(amount)      AS total_amount
                 FROM payments
                 -- paymentwall and paypal are included below
                 WHERE DATE(created_at) = DATE( NOW() ) AND processor_code NOT IN (3, 11, 7)
                 GROUP BY report_day, payment_status, payment_provider

             ) AS payments_t

             UNION
         
             -- micropayments
             SELECT * FROM (
               SELECT 
                 CURDATE()               AS report_day,
                 CASE status_code
                   WHEN 1 THEN 'Authorised'
                   ELSE 'Refused'
                 END                     AS payment_status,
                 'Payline Micropayments' AS payment_provider,
                 COUNT(*)                AS payment_count,
                 SUM(credit_plans.price) AS total_amount
               FROM credit_subscriptions
               INNER JOIN credit_plans ON credit_subscriptions.credit_plan_id = credit_plans.id
               WHERE DATE(credit_subscriptions.created_at) = DATE( NOW() )
               GROUP BY report_day, payment_status, payment_provider

             ) AS micropayments_t

             UNION
             
             -- itunes
             SELECT * FROM (

                 SELECT
                   CURDATE()                     AS report_day,
                   'Authorised'                  AS payment_status,
                   'iTunes'                      AS payment_provider,
                   COUNT(*)                      AS payments_count,
                   SUM(amount)                   AS total_amount
                 FROM itunes_notifications
                 WHERE DATE(created_at) = DATE( NOW() )
                 GROUP BY report_day, payment_status, payment_provider
             ) AS itunes_t

             UNION

             -- Paypal
             SELECT * FROM (
                 -- paypal total amount 
                 SELECT
                   CURDATE()                      AS report_day,
                   CASE 
                     WHEN status_code = 1 THEN  'Authorised'
                     ELSE                       'Refused'
                   END                            AS payment_status,
                   CASE
                     WHEN processor_code = 11 THEN 'Paypal RN'
                     -- Temp fix for paypal RN
                     WHEN processor_code = 3 AND DATE(subscriptions.created_at) < CURDATE() THEN 'Paypal RN'
                     WHEN processor_code = 3  THEN 'Paypal'
                   END                            AS payment_provider,
                   COUNT(*)                       AS payments_count,
                   SUM(payments.amount)           AS total_amount
                 FROM payments LEFT JOIN subscriptions on payments.user_id = subscriptions.user_id
                 WHERE DATE(payments.created_at) = CURDATE()
                 AND processor_code IN (11, 3)
                 GROUP BY report_day, payment_status, payment_provider

             ) AS paypal_t

             UNION
             
             -- Paymentwall
             SELECT * FROM (
                 -- Paymentwall total amount 
                 SELECT
                   CURDATE()                      AS report_day,
                   CASE 
                     WHEN status_code = 1 THEN  'Authorised'
                     ELSE                       'Refused'
                   END                            AS payment_status,
                   CASE
                     WHEN processor_code = 7 AND DATE(subscriptions.created_at) < CURDATE() THEN 'Paymentwall RN'
                     WHEN processor_code = 7  THEN 'Paymentwall'
                   END                            AS payment_provider,
                   COUNT(*)                       AS payments_count,
                   SUM(payments.amount)           AS total_amount
                 FROM payments LEFT JOIN subscriptions on payments.user_id = subscriptions.user_id
                 WHERE DATE(payments.created_at) = CURDATE()
                 AND processor_code = 7
                 GROUP BY report_day, payment_status, payment_provider

             ) AS paymentwall_t
           ) AS general_payment_report
       GROUP BY provider
       ORDER BY provider"
            ;

        $this->setName("Total Payments[[Total payments per provider]]");
    }

}

?>