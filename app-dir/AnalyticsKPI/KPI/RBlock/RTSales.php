<?php
namespace KPI\RBlock;

class RTSales extends RBlockRT {


    function __construct(array $filters=null, $limit=null) {
        parent::__construct($filters,$limit);

        $whereFilters = "";
        if($this->filters) {
            foreach($this->filters as $key=>$value) {
                $whereFilters .= "AND $key = '$value' ";
            }
        }

        $this->qu1 = "
          SELECT  CAST(report_day AS UNSIGNED) as dimension_time_id, SUM(total_amount) AS total_amount FROM (

         SELECT * FROM (
             SELECT
               DATE(NOW()) AS report_day,
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
                 ELSE 'unknown source'
               END              AS payment_provider,
               COUNT(*)         AS payments_count,
               SUM(amount)      AS total_amount
             FROM payments
             -- paymentwall and paypal are included below
             WHERE DATE(created_at) = DATE( NOW() ) AND processor_code NOT IN (3, 11, 7)
             GROUP BY payment_status, payment_provider

         ) AS payments_t

         UNION
         
         -- micropayments
         SELECT * FROM (
           SELECT 
             DATE(NOW())             AS report_day,
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
           GROUP BY payment_status, payment_provider

         ) AS micropayments_t

         UNION
         -- itunes
         SELECT * FROM (

             SELECT
               DATE(NOW())                   AS report_day,
               'Authorised'                  AS payment_status,
               'iTunes'                      AS payment_provider,
               COUNT(*)                      AS payments_count,
               SUM(amount)                   AS total_amount
             FROM itunes_notifications
             WHERE DATE(created_at) = DATE( NOW() )
             GROUP BY payment_status
         ) AS itunes_t

         UNION

         SELECT * FROM (
             -- paypal total amount 
             SELECT
               DATE(NOW())                           AS report_day,
               CASE 
                 WHEN status_code IN (0,1) THEN  'Authorised'
                 ELSE                            'Refused'
               END                                   AS payment_status,
               CASE
                 WHEN processor_code = 11 THEN 'Paypal RN'
                 WHEN processor_code = 3  THEN 'Paypal'
               END                                   AS payment_provider,
               COUNT(*)                              AS payments_count,
               SUM(payments.amount)             AS total_amount
             FROM payments 
             WHERE DATE(payments.created_at) = DATE( NOW() )
             AND processor_code IN (11,3)
             GROUP BY payment_status

         ) AS paypal_t

         UNION 
         SELECT * FROM (

           SELECT
             DATE(NOW()) AS report_day,
             CASE 
               WHEN transaction_type IN (0, 1)   THEN 'Authorised'
               WHEN transaction_type IN (2, 12) THEN 'Refused'
               WHEN transaction_type IS NULL    THEN NULL
               ELSE 'refused unknown reason'
             END                                        AS payment_status,
             'Paymentwall'                              AS payment_provider,
             COUNT(*)                                   AS payments_count,
             COALESCE(SUM(premium_plans.amount),0)      AS total_amount
           FROM paymentwall_notifications 
           JOIN premium_plans ON premium_plans.id = paymentwall_notifications.premium_plan_id
           WHERE DATE(paymentwall_notifications.created_at) = DATE( NOW() )
           GROUP BY payment_status

         ) AS paymentwall_t
       ) AS general_payment_report
       WHERE payment_status = 'Authorised'
       GROUP BY dimension_time_id"
            ;

        $this->setName("Total €[[Total revenue - Note: non euro currencies are not converted]]");
    }

}

?>