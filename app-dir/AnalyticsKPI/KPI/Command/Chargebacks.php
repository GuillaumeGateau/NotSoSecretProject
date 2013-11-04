<?php
namespace KPI\Command;

class Chargebacks extends Command {
	
	protected $PDO;
    
    function doExecute(\KPI\Controller\Request $request) {
    	//unset($this->PO);
        $template = new \KPI\View\Template("KPI/View/chargebacks.php");
        
		$cbPayProv=$request->getProperty("cbPayProv");
		$cbDate=$request->getProperty("cbDate");
	    $purchDate=$request->getProperty("purchDate");
	    $cardNbr=$request->getProperty("cardNbr");
	    $userid=$request->getProperty("userid");
	    $cbReason=$request->getProperty("cbReason");
	    $cardType=$request->getProperty("cardType");
	    $cbInEuros=$request->getProperty("cbInEuros");
	    $cbDefended=$request->getProperty("cbDefended");
	    $cbWon=$request->getProperty("cbWon");
	    $agentName=$request->getProperty("agentName");
	    $regDate=$request->getProperty("regDate");
	    $cardCountry=$request->getProperty("cardCountry");
	    
	    // Delete values 
	    $cdel=$request->getProperty("cdel");
	    if ($cdel){
	    	if (!isset($this->PDO)) {
            	$instance = \KPI\Base\CB_DBShop::instance();
            	$this->PDO = $instance->getPDO();
        	}
          	$insertStatement = $this->PDO->query("DELETE FROM chargebacks WHERE id = '".$cdel."'");
	    }

		
		// Add values to DB if available
		if ($cbPayProv && $cbDate && $purchDate && $cardNbr && $userid && $cbReason && $cardType && $cbInEuros && $cbDefended && $cbWon && $agentName && $regDate && $cardCountry){
		
		if ($cbPayProv == "Payment Provider" && $cbDate == "Chargeback Date" && $purchDate == "Purchase Date"  && $cardNbr == "Card Number" && $userid == "User Id" && $cbReason == "Chargeback Reason" && $cardType == "Card Type" && $cbInEuros == "Chargeback Amount (in €)" && $cbDefended == "Chargeback Defended" && $cbWon == "Chargeback Won" && $agentName == "Agent Name" && $regDate == "Date of Registration" && $cardCountry == "Card Issiung Country"){
			echo  '<script type="text/javascript">
					$(document).ready(function(){
						alert("Fill out all vals");   
		});
	</script>';
		} else {
		
			if (!isset($this->PDO)) {
            	$instance = \KPI\Base\CB_DBShop::instance();
            	$this->PDO = $instance->getPDO();
        	}
        	
        	$insertStatement = $this->PDO->query("
        		INSERT INTO chargebacks
        			(pay_prov, cb_date, purch_date, card_nbr, userid, reason, card_type, amount, defended, won, agent, reg_date, card_country)
        		VALUES
        			('". $cbPayProv ."','". $cbDate ."', '". $purchDate ."', '". $cardNbr ."', '". $userid ."', '". $cbReason ."', '". $cardType ."', '". $cbAmount ."', '". $cbDefended ."', '". $cbWon ."', '". $agentName ."', '". $regDate ."', '". $cardCountry ."')
        		");
        }
        
    }
       

        $cbTab = new \KPI\Table\CTable();
        $cbTab->add(new \KPI\CBlock\CBPayProv())
        		->add(new \KPI\CBlock\CBDate())
                ->add(new \KPI\CBlock\CBPDate())
                ->add(new \KPI\CBlock\CBCNbr())
                ->add(new \KPI\CBlock\CBUserid())
                ->add(new \KPI\CBlock\CBReason())
                ->add(new \KPI\CBlock\CBCType())
                ->add(new \KPI\CBlock\CBAmount())
                ->add(new \KPI\CBlock\CBDefended())
                ->add(new \KPI\CBlock\CBWon())
                ->add(new \KPI\CBlock\CBAgent())
                ->add(new \KPI\CBlock\CBRDate())
                ->add(new \KPI\CBlock\CBCCountry());
        $t = $cbTab->getTab();
        $template->set("t1Data", $t);

        $this->invoke($template);
    }    
    	
    
}

?>
