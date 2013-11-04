<?php

namespace KPI\View\Helper;

class MakeTable {

    private $html;
    private $class;
    private $id;

    private $showHeader;
    private $showKeyCol;
    private $RT;
    private $notes;
    
    private $nPDO;
    private $nValues;
    private $tkey;
    private $temp_key = null;
    private $fval;

    function __construct(array $tab, $class="", $id="", $showHeader=true, $showKeyCol=true, $notes=false, $RT=false) {
        $this->class = $class;
        $this->id = $id;
        $this->showHeader = $showHeader;
        $this->showKeyCol = $showKeyCol;
        $this->RT = $RT;
        $this->notes = $notes;
        
        if ($notes == true){
        	$this->getNotes();
        }
        
        $this->genHtml($tab);
    }

    function genHtml(array $tab) {
        $numRows = count($tab);
        $numCols = count($tab[0]);

        $tableClass = $this->class !== "" ? "class=\"" . $this->class . "\"" : "";
        $tableId = $this->id !== "" ? "id=\"" . $this->id . "\"" : "";

        $s = "<table $tableClass $tableId>\n";
        if ($this->showHeader === true) {
            if ($this->showKeyCol === false) {
                \array_shift($tab[0]);
            }
            
            $s .= "\t<thead>";
            foreach ($tab[0] as $name) {
                $arr = \explode("[[", $name);
                $header = $arr[0];
                $ttip = isset($arr[1]) ? $arr[1] : "";
                if($ttip) {
                    $ttip = \array_shift(\explode("]]",$arr[1]));
                    if ($this->RT == false){
	                    $s .= "<th class='ttip' title='$ttip'>$header</th>";
	                } else {
	                	$s .= "<th class='RTtip' title='$ttip'>$header</th>";
	                }
                }
                else {
                    $s .= "<th>$header</th>";
                }
            }
            $s .= "\n\t</thead>\n";
        }

        $s .= "\t<tbody>\n";
        $jstart = $this->showKeyCol === true ? 0 : 1;

       	for ($i = 1; $i < $numRows; $i++) {
       		
       		$s .= "\t\t<tr>\n\t\t";
      	 	
      	 	foreach ($tab[$i] as $key => $DValue){
				
				if ($this->notes == true){
					$this->temp_key = $DValue->keyId;
					if ($key == 0){ $this->fval = $DValue->value; }
				}
				      	 		
       			$s .= "<td ";
       			foreach ($DValue->props as $pkey => $pval){ $s .= $pkey . '="' . $pval . '" '; }	       			
       			$s .= ">" ;
       			
       			if ($key == 0){
       				$s .= "<div class=\"".$this->temp_key."\" style=\"display: inline-block; text-align: left; position: absolute; width: 13px\">";
      	 			if ($this->notes == true){
	       				foreach($this->nValues as $nkey => $nval){

	       					if ($nkey == $DValue->keyId){
	       						$this->temp_key = $nkey;
		       					$s .= "<div class='note_img $this->temp_key' note='$nval' \"><a href='DisplayNote?id=".$this->temp_key."' rel='facebox'><img src=\"img/note_info.png\"></a></div>";
		       				}
	       				}
	       			}
	       			$s .= "</div>&nbsp;&nbsp;";
      	 		}
      	 		
	       		$s .= $DValue->value . "</td>";
		    }
	       		
	       		if ($this->notes == true){
		       		$s .= "<td><div class='note_add'><a href=\"#\" alt=\"Add Note\" key='$this->temp_key' fval='$this->fval' class='osx demo'><img src=\"img/note_add.png\" alt=\"Add Note\"></a></div></td>";
		       	}
	       		//<img src=\"img/note_info.png\"
	       	$s .= "\n\t\t</tr>\n";
	       	
	       
	    }

       
        $s .= "\t</tbody>\n</table>";

        $this->html = $s;
    }

    function getHtml() {
        return $this->html;
    }
    
   function getNotes() {
    	$this->nPDO = new \PDO("mysql:host=analytics002.smartdate.com;port=3306;dbname=analytics_kpi_production", "analytics_ro", "dce478655");
    	$this->nPDO->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    	$qu2 = "SELECT dimension_time_id, notes FROM notes ORDER BY dimension_time_id DESC";
    	
    	$stmt = $this->nPDO->query($qu2);
    	$result = $stmt->fetchAll();
    	
    	$temp = array();
    	foreach($result as $key=>$value) {
            $temp[$value[0]] = $value[1];
        }
        
        $this->nValues = $temp;
        
        //var_dump($this->nValues);
    }

}

?>