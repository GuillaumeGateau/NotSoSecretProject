<?php
namespace KPI\Command;

class Summary extends Command {
    function doExecute(\KPI\Controller\Request $request) {
    
        $nNote=$request->getProperty("nNote");
        $nKey=$request->getProperty("nKey");
        
        if ($nKey){
            if (!isset($this->PDO)) {
                $instance = \KPI\Base\CB_DBShop::instance();
                $this->PDO = $instance->getPDO();
            }
            
              $q1 = $this->PDO->prepare("SELECT count(*) FROM notes WHERE dimension_time_id = '$nKey'");
              $q1->execute();
              $number_of_rows = $q1->fetchColumn();
              
              if ($number_of_rows == 0){
                  $q2 = $this->PDO->query("INSERT INTO notes (dimension_time_id, created_at, notes) VALUES ('$nKey', NOW(), '$nNote')");
              } else {
                  if ($nNote){              
                      $q2 = $this->PDO->query("UPDATE notes
                      SET created_at = NOW(), notes = '$nNote'
                      WHERE dimension_time_id = '$nKey'");
                  } else {
                      $q2 = $this->PDO->query("DELETE FROM notes WHERE dimension_time_id = '$nKey'");
                  }
              }

        }
        
    
           $this->generateTTable();
    
        $template = new \KPI\View\Template("KPI/View/summary.php");

        $lastDay = \KPI\DBlock\DBlock::getLastDayAvailable();
        
        $date = \DateTime::createFromFormat('Ymd', $lastDay);
        $template->set("lastDay", $date->format('D. Y/m/d'));

        // set country filter if any
        $countryFilter = $request->getProperty("countryFilter");
        if($countryFilter && $countryFilter!="all") {
            $filter = array("country_code"=>$countryFilter);
        }
        else {
            $filter = null;
        }

        $dates=$request->getProperty("rangePick");
        if($dates) {
            $dateRange = explode(' - ',$request->getProperty("rangePick"));
            $startDate = \str_replace('/', '', $dateRange[0]);
            $endDate = \str_replace('/', '', $dateRange[1]);           
            \KPI\DBlock\DBlockPerDay::setDateRange($startDate, $endDate);
            // Note if dates are invalid, default dates will be entered
        }
        else {
            \KPI\DBlock\DBlockPerDay::setDefaultDates();
        }

        
        $dayTab1 = new \KPI\Table\Table();
        $dayTab1->add(new \KPI\DBlock\Days())
                ->add($s = new \KPI\DBlock\PerDaySales($filter))
                ->add($fo = new \KPI\DBlock\PerDayFOSales($filter))
                ->add(new \KPI\DBlock\PerDayFOPromoRate($filter))
                ->add(new \KPI\DBlock\PerDayRNSales($filter))
                ->add($foNum = new \KPI\DBlock\PerDayFONum($filter))
                ->add(new \KPI\DBlock\PerDayRNNum($filter))
                ->add($reg = new \KPI\DBlock\PerDayRegs($filter))
                ->add(new \KPI\DBlockOp\DBlockDiv($s, $reg, "€/Reg[[Euro per Reg - Calculated for 1 day]]", 2))
                ->add(new \KPI\DBlockOp\DBlockDiv($fo, $reg, "€FO/Reg[[FO Euro per Reg - Calculated for 1 day]]", 2))
                ->add(new \KPI\DBlockOp\DBlockPercent($foNum, $reg, "% FO/Reg[[% FO per Reg - Ratio of first orders to number of registrations<br>Calculated for 1 day]]", 2))
                ->add(new \KPI\DBlock\PerDayFailedPayNum($filter))
                ->add($cn = new \KPI\DBlock\PerDayCNNum($filter))
                ->add($cn = new \KPI\DBlock\PerDayPercentCancellations($filter));

        $t = $dayTab1->getTab();
        $template->set("dayData1", $t);

        $dayTab2 = new \KPI\Table\Table();
        $dayTab2->add(new \KPI\DBlock\Days())
                ->add(new \KPI\DBlockOp\DBlockPercent(new \KPI\DBlock\PerDayFO1WePlan($filter),$foNum,"%1WE FO[[1-week FO - Percentage of 1-week plan First Orders]]",2,false,true))
                ->add(new \KPI\DBlockOp\DBlockPercent(new \KPI\DBlock\PerDayFO1MoPlan($filter),$foNum,"%1MO FO[[1-month FO - Percentage of 1-month plan First Orders]]",2,false,true))
                ->add(new \KPI\DBlockOp\DBlockPercent(new \KPI\DBlock\PerDayFO3MoPlan($filter),$foNum,"%3MO FO[[3-month FO - Percentage of 3-month plan First Orders]]",2,false,true))
                ->add(new \KPI\DBlockOp\DBlockPercent(new \KPI\DBlock\PerDayFO6MoPlan($filter),$foNum,"%6MO FO[[6-month FO - Percentage of 6-month plan First Orders]]",2,false,true))
                ->add(new \KPI\DBlockOp\DBlockPercent(new \KPI\DBlock\PerDayFO12MoPlan($filter),$foNum,"%12MO FO[[12-month FO - Percentage of 12-month plan First Orders]]",2,false,true))
                ->add(new \KPI\DBlockOp\DBlockPercent(new \KPI\DBlock\PerDayCN1WePlan($filter), $cn, "%1WE CN[[1-week cancellations - Percentage of cancellations of 1-week subscriptions]]",2,false,true))
                ->add(new \KPI\DBlockOp\DBlockPercent(new \KPI\DBlock\PerDayCN1MoPlan($filter), $cn, "%1MO CN[[1-month cancellations - Percentage of cancellations of 1-month subscriptions]]",2,false,true))
                ->add(new \KPI\DBlockOp\DBlockPercent(new \KPI\DBlock\PerDayCN3MoPlan($filter), $cn, "%3MO CN[[3-month cancellations - Percentage of cancellations of 3-month subscriptions]]",2,false,true))
                ->add(new \KPI\DBlockOp\DBlockPercent(new \KPI\DBlock\PerDayCN6MoPlan($filter), $cn, "%6MO CN[[6-month cancellations - Percentage of cancellations of 6-month subscriptions]]",2,false,true))
                ->add(new \KPI\DBlockOp\DBlockPercent(new \KPI\DBlock\PerDayCN12MoPlan($filter), $cn, "%12MO CN[[12-month cancellations - Percentage of cancellations of 12-month subscriptions]]",2,false,true));

        $t = $dayTab2->getTab();
        $template->set("dayData2", $t);

        $dayTab2b = new \KPI\Table\Table();
        $dayTab2b->add(new \KPI\DBlock\Days())
                ->add(new \KPI\DBlock\PerDayAdyenFOSales($filter))
                ->add(new \KPI\DBlock\PerDayAdyenRNSales($filter))
                ->add(new \KPI\DBlock\PerDayPaylineFOSales($filter))
                ->add(new \KPI\DBlock\PerDayPaylineRNSales($filter))
                ->add(new \KPI\DBlock\PerDayPaymentWallFOSales($filter))
                ->add(new \KPI\DBlock\PerDayPaymentWallRNSales($filter))
                ->add(new \KPI\DBlock\PerDayPayPalFOSales($filter))
                ->add(new \KPI\DBlock\PerDayPayPalRNSales($filter))
                ->add(new \KPI\DBlock\PerDayCreditSales($filter))
                ->add(new \KPI\DBlock\PerDayiPhoneSales($filter))
                ->add(new \KPI\DBlock\PerDayZongSales($filter))
                ->add(new \KPI\DBlock\PerDayOTCSales($filter));

        $t = $dayTab2b->getTab();
        $template->set("dayData2b", $t);
        
        $dayTab2c = new \KPI\Table\Table();
        $dayTab2c->add(new \KPI\DBlock\Days())
                ->add(new \KPI\DBlock\PerDayCreditsUsed($filter))
                ->add(new \KPI\DBlockOp\DBlockSum(new \KPI\DBlock\PerDayMenMicroActions($filter), new \KPI\DBlock\PerDayWomenMicroActions($filter), "Micro Actions[[Micro Actions - Number of times users pay with credits]]"))
                ->add(new \KPI\DBlock\PerDayMenMicroActions($filter))
                ->add(new \KPI\DBlock\PerDayWomenMicroActions($filter))
                ->add(new \KPI\DBlockOp\DBlockSum(new \KPI\DBlock\PerDayMessagesUnlock4Female($filter), new \KPI\DBlock\PerDayMessagesUnlock4Men($filter), "Message Unlocks[[Message Unlocks - Total number of messages unlocked today]]"))
                ->add(new \KPI\DBlock\PerDayMessagesUnlock4Men($filter))
                ->add(new \KPI\DBlock\PerDayMessagesUnlock4Female($filter))
                ->add(new \KPI\DBlock\PerDayChatUnlock4Men($filter))
                ->add(new \KPI\DBlock\PerDayChatUnlock4Female($filter))
                ->add(new \KPI\DBlock\PerDaySmartguessUnlock($filter))
                ->add(new \KPI\DBlock\PerDayFilmstripUnlock($filter))
                ->add(new \KPI\DBlock\PerDayHotseatUnlock($filter));

        $t = $dayTab2c->getTab();
        $template->set("dayData2c", $t);
        
        $dayTab4 = new \KPI\Table\Table();
        $dayTab4->add(new \KPI\DBlock\Days())
                ->add($vis = new \KPI\DBlock\PerDayVisits($filter))
                ->add(new \KPI\DBlock\PerDayNewVisits($filter))
                ->add($logs = new \KPI\DBlock\PerDayLogins($filter))
                ->add($pv = new \KPI\DBlock\PerDayPV($filter))
                ->add(new \KPI\DBlock\PerDayPayPV($filter))
                ->add(new \KPI\DBlockOp\DBlockDiv($pv,$vis, "#PV/Visit[[Number of page views per total number of visits]]"))
                ->add(new \KPI\DBlockOp\DBlockDiv(new \KPI\DBlock\PerDayTimeOnSite($filter),$vis,"Time/Visit[[Time spent - Time spent on the website per visit (in minutes)]]"))
                ->add(new \KPI\DBlock\PerDayDaysToPremium($filter))
                ->add(new \KPI\DBlock\PerDayR2FO($filter));

        $t = $dayTab4->getTab();
        $template->set("dayData4", $t);

        $dayTab5 = new \KPI\Table\Table();
        $dayTab5->add(new \KPI\DBlock\Days())
                ->add($reg)
                ->add(new \KPI\DBlock\PerDayFBRegs($filter))
                ->add(new \KPI\DBlock\PerDayiPhoneRegs($filter))
                ->add(new \KPI\DBlockOp\DBlockPercent(new \KPI\DBlock\PerDayFemRegs($filter),$reg, "%Fem[[Female regs. - Percentage of female registrations]]"))
                ->add(new \KPI\DBlockOp\DBlockPercent($reg,$vis, "V2R[[Visit to Reg. - Conversion rate for Visits to Registration]]"))
                ->add(new \KPI\DBlockOp\DBlockPercent(new \KPI\DBlock\PerDayRegsFromFB($filter),new \KPI\DBlock\PerDayVisitsFromFB($filter),"%V2R-FB[[% V2R Facebook - Percentage of conversion from Visit to Registration for traffic brought by fbperformads]]"))
                ->add(new \KPI\DBlockOp\DBlockPercent(new \KPI\DBlock\PerDayRegsFromGG($filter),new \KPI\DBlock\PerDayVisitsFromGG($filter),"%V2R-GG[[% V2R Google - Percentage of conversion from Visit to Registration for traffic brought by Google]]"))
                ->add(new \KPI\DBlockOp\DBlockDiv(new \KPI\DBlock\PerDayMsgNum($filter),$logs,"Msgs/Log[[ - Number of messages per login]]"))
                ->add(new \KPI\DBlockOp\DBlockDiv(new \KPI\DBlock\PerDayWinkNum($filter),$logs,"Winks/Log[[ - Number of winks per login]]"))
                ->add(new \KPI\DBlockOp\DBlockPercent(new \KPI\DBlock\PerDayReports($filter),$reg, "%Reports[[%reports - Percentage of users reported compared to total registrations]]",2,1))
                ->add(new \KPI\DBlockOp\DBlockPercent(new \KPI\DBlock\PerDayVisitsAfrica($filter),$vis, "%V.Africa[[ - Percentage of visits coming from a country in Africa]]",2,1));

        $t = $dayTab5->getTab();
        $template->set("dayData5", $t);
        
        $dayTab6 = new \KPI\Table\Table();
        $dayTab6->add(new \KPI\DBlock\Days())
                ->add(new \KPI\DBlock\PerDayAboutMe($filter))
                ->add(new \KPI\DBlock\PerDayEducation($filter))
                ->add(new \KPI\DBlock\PerDayEthnicity($filter))
                ->add(new \KPI\DBlock\PerDayReligion($filter))
                ->add(new \KPI\DBlock\PerDayAboutMeActivities($filter))
                ->add(new \KPI\DBlock\PerDayAboutMeMovies($filter))
                ->add(new \KPI\DBlock\PerDayAboutMeTV($filter))
                ->add(new \KPI\DBlock\PerDayAboutMeBooks($filter))
                ->add(new \KPI\DBlock\PerDayAboutMeMusic($filter))
                ->add(new \KPI\DBlock\PerDayAboutMePhoto($filter));

        $t = $dayTab6->getTab();
        $template->set("dayData6", $t);
        
        
        $dayTab7 = new \KPI\Table\Table();
        $dayTab7->add(new \KPI\DBlock\Days())
                ->add(new \KPI\DBlockOp\DBlockSum(new \KPI\DBlock\PerDayWinksFromMen($filter), new \KPI\DBlock\PerDayWinksFromWomen($filter), "Total Winks[[Total Winks - Total number of winks sent by SmartDate users]]"))
                ->add(new \KPI\DBlock\PerDayWinksFromMen($filter))
                ->add(new \KPI\DBlock\PerDayWinksFromWomen($filter))
                ->add(new \KPI\DBlockOp\DBlockSum(new \KPI\DBlock\PerDayMessagesFromMen($filter), new \KPI\DBlock\PerDayMessagesFromWomen($filter), "Total Messages[[Total Messages - Total number of messages sent by SmartDate users]]"))
                ->add(new \KPI\DBlock\PerDayMessagesFromMen($filter))
                ->add(new \KPI\DBlock\PerDayMessagesFromWomen($filter))
                ->add(new \KPI\DBlock\PerDayFacebookPhotoUploads($filter))
                ->add(new \KPI\DBlock\PerDayNotifiersSent($filter));
                
        $t = $dayTab7->getTab();
        $template->set("dayData7", $t);
        
        
        $dayTab8 = new \KPI\Table\Table();
        $dayTab8->add(new \KPI\DBlock\Days())
                ->add(new \KPI\DBlock\PerDayDesktopDownloads($filter))
                ->add(new \KPI\DBlock\PerDayDesktopLogins($filter))
                ->add(new \KPI\DBlock\PerDayAndroidLogins($filter))
                ->add(new \KPI\DBlock\PerDayIPhoneLogins($filter));
                
        $t = $dayTab8->getTab();
        $template->set("dayData8", $t);
        
        
        $dayTab9 = new \KPI\Table\Table();
        $dayTab9->add(new \KPI\DBlock\Days())
                ->add(new \KPI\DBlock\PerDayChatMessagesSent($filter))
                ->add(new \KPI\DBlock\PerDayChatUniqueSenders($filter))
                ->add(new \KPI\DBlock\PerDayChatUniqueRecipients($filter))
                ->add(new \KPI\DBlock\PerDayConversations($filter));
                
        $t = $dayTab9->getTab();
        $template->set("dayData9", $t);
        
        $dayTab10 = new \KPI\Table\Table();
        $dayTab10->add(new \KPI\DBlock\Days())
                ->add(new \KPI\DBlock\PerDayTotalUsers($filter))
                ->add(new \KPI\DBlock\UsersTrackingNew($filter))
                ->add(new \KPI\DBlock\UsersTrackingWindowShoppers($filter))
                ->add(new \KPI\DBlock\UsersTrackingActive($filter))
                ->add(new \KPI\DBlock\UsersTrackingInactive($filter))
                ->add(new \KPI\DBlock\UsersTrackingActiveDaily($filter));

        $t = $dayTab10->getTab();
        $template->set("dayData10", $t);
        

        $template->set("countryFilter", $countryFilter);
        list($startDate,$endDate) = \KPI\DBlock\DBlockPerDay::getDefaultDates();
        $dates = substr($startDate,0,4)."/".substr($startDate,4,2)."/".substr($startDate,6,2).
                " - ".substr($endDate,0,4)."/".substr($endDate,4,2)."/".substr($endDate,6,2);
        $template->set("dateRange", $dates);

        $this->invoke($template);
    }
    
    function generateTTable(){
    
        $ttbl[] = array("df",0.2,0.1,0.05,0.02,0.01);
        $ttbl[] = array("-","80%","90%","95%","98%","99%");
        $ttbl[] = array(1,3.078,6.314,12.706,31.821,63.657);
        $ttbl[] = array(2,1.886,2.92,4.303,6.965,9.925);
        $ttbl[] = array(3,1.638,2.353,3.182,4.541,5.841);
        $ttbl[] = array(4,1.533,2.132,2.776,3.747,4.604);
        $ttbl[] = array(5,1.476,2.015,2.571,3.365,4.032);
        $ttbl[] = array(6,1.44,1.943,2.447,3.143,3.707);
        $ttbl[] = array(7,1.415,1.895,2.365,2.998,3.499);
        $ttbl[] = array(8,1.397,1.86,2.306,2.896,3.355);
        $ttbl[] = array(9,1.383,1.833,2.262,2.821,3.25);
        $ttbl[] = array(10,1.372,1.812,2.228,2.764,3.169);
        $ttbl[] = array(11,1.363,1.796,2.201,2.718,3.106);
        $ttbl[] = array(12,1.356,1.782,2.179,2.681,3.055);
        $ttbl[] = array(13,1.35,1.771,2.16,2.65,3.012);
        $ttbl[] = array(14,1.345,1.761,2.145,2.624,2.977);
        $ttbl[] = array(15,1.341,1.753,2.131,2.602,2.947);
        $ttbl[] = array(16,1.337,1.746,2.12,2.583,2.921);
        $ttbl[] = array(17,1.333,1.74,2.11,2.567,2.898);
        $ttbl[] = array(18,1.33,1.734,2.101,2.552,2.878);
        $ttbl[] = array(19,1.328,1.729,2.093,2.539,2.861);
        $ttbl[] = array(20,1.325,1.725,2.086,2.528,2.845);
        $ttbl[] = array(21,1.323,1.721,2.08,2.518,2.831);
        $ttbl[] = array(22,1.321,1.717,2.074,2.508,2.819);
        $ttbl[] = array(23,1.319,1.714,2.069,2.5,2.807);
        $ttbl[] = array(24,1.318,1.711,2.064,2.492,2.797);
        $ttbl[] = array(25,1.316,1.708,2.06,2.485,2.787);
        $ttbl[] = array(26,1.315,1.706,2.056,2.479,2.779);
        $ttbl[] = array(27,1.314,1.703,2.052,2.473,2.771);
        $ttbl[] = array(28,1.313,1.701,2.048,2.467,2.763);
        $ttbl[] = array(29,1.311,1.699,2.045,2.462,2.756);
        $ttbl[] = array(30,1.31,1.697,2.042,2.457,2.75);
        $ttbl[] = array(31,1.309,1.696,2.04,2.453,2.744);
        $ttbl[] = array(32,1.309,1.694,2.037,2.449,2.738);
        $ttbl[] = array(33,1.308,1.692,2.035,2.445,2.733);
        $ttbl[] = array(34,1.307,1.691,2.032,2.441,2.728);
        $ttbl[] = array(35,1.306,1.69,2.03,2.438,2.724);
        $ttbl[] = array(36,1.306,1.688,2.028,2.434,2.719);
        $ttbl[] = array(37,1.305,1.687,2.026,2.431,2.715);
        $ttbl[] = array(38,1.304,1.686,2.024,2.429,2.712);
        $ttbl[] = array(39,1.304,1.685,2.023,2.426,2.708);
        $ttbl[] = array(40,1.303,1.684,2.021,2.423,2.704);
        $ttbl[] = array(41,1.303,1.683,2.02,2.421,2.701);
        $ttbl[] = array(42,1.302,1.682,2.018,2.418,2.698);
        $ttbl[] = array(43,1.302,1.681,2.017,2.416,2.695);
        $ttbl[] = array(44,1.301,1.68,2.015,2.414,2.692);
        $ttbl[] = array(45,1.301,1.679,2.014,2.412,2.69);
        $ttbl[] = array(46,1.3,1.679,2.013,2.41,2.687);
        $ttbl[] = array(47,1.3,1.678,2.012,2.408,2.685);
        $ttbl[] = array(48,1.299,1.677,2.011,2.407,2.682);
        $ttbl[] = array(49,1.299,1.677,2.01,2.405,2.68);
        $ttbl[] = array(50,1.299,1.676,2.009,2.403,2.678);
        $ttbl[] = array(51,1.298,1.675,2.008,2.402,2.676);
        $ttbl[] = array(52,1.298,1.675,2.007,2.4,2.674);
        $ttbl[] = array(53,1.298,1.674,2.006,2.399,2.672);
        $ttbl[] = array(54,1.297,1.674,2.005,2.397,2.67);
        $ttbl[] = array(55,1.297,1.673,2.004,2.396,2.668);
        $ttbl[] = array(56,1.297,1.673,2.003,2.395,2.667);
        $ttbl[] = array(57,1.297,1.672,2.002,2.394,2.665);
        $ttbl[] = array(58,1.296,1.672,2.002,2.392,2.663);
        $ttbl[] = array(59,1.296,1.671,2.001,2.391,2.662);
        $ttbl[] = array(60,1.296,1.671,2,2.39,2.66);
        $ttbl[] = array(61,1.296,1.67,2,2.389,2.659);
        $ttbl[] = array(62,1.295,1.67,1.999,2.388,2.657);
        $ttbl[] = array(63,1.295,1.669,1.998,2.387,2.656);
        $ttbl[] = array(64,1.295,1.669,1.998,2.386,2.655);
        $ttbl[] = array(65,1.295,1.669,1.997,2.385,2.654);
        $ttbl[] = array(66,1.295,1.668,1.997,2.384,2.652);
        $ttbl[] = array(67,1.294,1.668,1.996,2.383,2.651);
        $ttbl[] = array(68,1.294,1.668,1.995,2.382,2.65);
        $ttbl[] = array(69,1.294,1.667,1.995,2.382,2.649);
        $ttbl[] = array(70,1.294,1.667,1.994,2.381,2.648);
        $ttbl[] = array(71,1.294,1.667,1.994,2.38,2.647);
        $ttbl[] = array(72,1.293,1.666,1.993,2.379,2.646);
        $ttbl[] = array(73,1.293,1.666,1.993,2.379,2.645);
        $ttbl[] = array(74,1.293,1.666,1.993,2.378,2.644);
        $ttbl[] = array(75,1.293,1.665,1.992,2.377,2.643);
        $ttbl[] = array(76,1.293,1.665,1.992,2.376,2.642);
        $ttbl[] = array(77,1.293,1.665,1.991,2.376,2.641);
        $ttbl[] = array(78,1.292,1.665,1.991,2.375,2.64);
        $ttbl[] = array(79,1.292,1.664,1.99,2.374,2.64);
        $ttbl[] = array(80,1.292,1.664,1.99,2.374,2.639);
        $ttbl[] = array(81,1.292,1.664,1.99,2.373,2.638);
        $ttbl[] = array(82,1.292,1.664,1.989,2.373,2.637);
        $ttbl[] = array(83,1.292,1.663,1.989,2.372,2.636);
        $ttbl[] = array(84,1.292,1.663,1.989,2.372,2.636);
        $ttbl[] = array(85,1.292,1.663,1.988,2.371,2.635);
        $ttbl[] = array(86,1.291,1.663,1.988,2.37,2.634);
        $ttbl[] = array(87,1.291,1.663,1.988,2.37,2.634);
        $ttbl[] = array(88,1.291,1.662,1.987,2.369,2.633);
        $ttbl[] = array(89,1.291,1.662,1.987,2.369,2.632);
        $ttbl[] = array(90,1.291,1.662,1.987,2.368,2.632);
        $ttbl[] = array(91,1.291,1.662,1.986,2.368,2.631);
        $ttbl[] = array(92,1.291,1.662,1.986,2.368,2.63);
        $ttbl[] = array(93,1.291,1.661,1.986,2.367,2.63);
        $ttbl[] = array(94,1.291,1.661,1.986,2.367,2.629);
        $ttbl[] = array(95,1.291,1.661,1.985,2.366,2.629);
        $ttbl[] = array(96,1.29,1.661,1.985,2.366,2.628);
        $ttbl[] = array(97,1.29,1.661,1.985,2.365,2.627);
        $ttbl[] = array(98,1.29,1.661,1.984,2.365,2.627);
        $ttbl[] = array(99,1.29,1.66,1.984,2.365,2.626);
        $ttbl[] = array(100,1.29,1.66,1.984,2.364,2.626);
        $ttbl[] = array("inf",1.282,1.645,1.96,2.326,2.576);
        
        $GLOBALS["ttbl"] = $ttbl;
    }
}

?>
