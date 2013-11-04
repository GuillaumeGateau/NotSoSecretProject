<?php
namespace KPI\Command;

class DisplayNote extends Command {
	
	protected $PDO;
    
    function doExecute(\KPI\Controller\Request $request) {
        
		$id=$request->getProperty("id");
		
	    if ($id){
	    	if (!isset($this->PDO)) {
            	$instance = \KPI\Base\CB_DBShop::instance();
            	$this->PDO = $instance->getPDO();
        	}
          	$qu1 = $this->PDO->query("SELECT notes FROM notes WHERE dimension_time_id = '$id' ");
          	
	        $result = $qu1->fetchAll();

    	    $temp = array();
        	
        	echo "<div style='font-size: 12px;'>";
        	
        	foreach($result as $key => $value) {
            	echo nl2br($value[0]);
	        }
	        
	        echo "</div>";
	
	    }


 
    	
    
}
}

?>
