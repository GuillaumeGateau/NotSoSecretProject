<?php
namespace KPI\Command;

class Notes extends \KPI\Command\Command {

    protected $PDO;
    
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
                  echo "Note added successfully!";
              } else {
                  if ($nNote){              
                      $q2 = $this->PDO->query("UPDATE notes
                      SET created_at = NOW(), notes = '$nNote'
                      WHERE dimension_time_id = '$nKey'");
                      echo "Note updated successfully!";
                  } else {
                      $q2 = $this->PDO->query("DELETE FROM notes WHERE dimension_time_id = '$nKey'");
                      echo "Note deleted successfully!";
                  }
              }
        }
        
        
}
}
?>
