<?php
namespace KPI\RBlock;

class RTFOSales extends RBlockRT {

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
            CAST(report_day AS UNSIGNED) as dimension_time_id,
            SUM(first.first_orders_amount) AS first_orders_amount
            FROM
          (
            -- payline and adyen
            SELECT 
              DATE(p.report_date) AS report_day,
              COUNT(*) AS first_orders,
              SUM(p.amount) AS first_orders_amount
            FROM subscriptions 
            JOIN (
              SELECT CURDATE() as report_date, amount, user_id
              FROM payments 
              WHERE 
                processor_code in (1,5)
                AND DATE(created_at) = CURDATE()
                AND payments.status_code = 1
            ) AS p ON p.user_id = subscriptions.user_id
            WHERE (DATE(subscriptions.created_at) = CURDATE() or DATE(subscriptions.last_subscription_created_at) = CURDATE())
                AND DATE(expires_at) >= CURDATE()

            UNION
            
            -- itunes
            SELECT CURDATE() AS report_day, COUNT(amount) AS first_orders, SUM(amount) AS first_orders_amount
            FROM itunes_notifications 
            JOIN subscriptions on itunes_notifications.user_id = subscriptions.user_id
            WHERE 
              DATE(subscriptions.created_at) = CURDATE() AND DATE(expires_at) >= CURDATE()

            UNION
            
            -- paymentwall
            SELECT 
              CURDATE() AS report_day,
              COUNT(*) AS first_orders,
              COALESCE(SUM(p.amount), 0) AS first_orders_amount
            FROM subscriptions 
            JOIN (
              SELECT CURDATE() as report_date, amount, user_id
              FROM payments 
              WHERE 
                processor_code in (7)
                AND DATE(created_at) = CURDATE()
                AND status_code = 1
            ) AS p ON p.user_id = subscriptions.user_id
            WHERE DATE(subscriptions.created_at) = CURDATE() AND DATE(expires_at) >= CURDATE()


            UNION

            -- paypal first order from EUR
            SELECT 
              CURDATE() AS report_day,
              COUNT(*) AS first_orders,
              COALESCE(SUM(payments.amount), 0) AS first_orders_amount
            FROM payments LEFT JOIN subscriptions on payments.user_id = subscriptions.user_id
            WHERE DATE(payments.created_at) = CURDATE()
            AND (payments.processor_code = 3 AND DATE(subscriptions.created_at) = CURDATE())
            AND payments.status_code = 1
            AND payments.currency = 'EUR'

            UNION
            
            -- paypal first order from US
            SELECT 
              CURDATE() AS report_day,
              COUNT(*) AS first_orders,
              COALESCE(SUM(payments.amount * .7 ), 0) AS first_orders_amount
            FROM payments LEFT JOIN subscriptions on payments.user_id = subscriptions.user_id
            WHERE DATE(payments.created_at) = CURDATE()
            AND (payments.processor_code = 3 AND DATE(subscriptions.created_at) = CURDATE())
            AND payments.status_code = 1
            AND payments.currency = 'USD'
             ) as first"
            ;

        $this->setName("€FO[[Total FO revenue per provider - Note: non euro currencies are not converted]]");
    }

}

?>