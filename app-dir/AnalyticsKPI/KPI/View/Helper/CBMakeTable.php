<?php

namespace KPI\View\Helper;

class CBMakeTable {

    private $html;
    private $class;
    private $id;

    private $showHeader;
    private $showKeyCol;

    function __construct(array $tab, $class="", $id="", $showHeader=true, $showKeyCol=true) {
        $this->class = $class;
        $this->id = $id;
        $this->showHeader = $showHeader;
        $this->showKeyCol = $showKeyCol;
        
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
                    $s .= "<th class='ttip' title='$ttip'>$header</th>";
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
       			$s .= "<td ";

       			foreach ($DValue->props as $pkey => $pval){
       				$s .= $pkey . '="' . $pval . '" '; 
       			}	       			
       			//$s .= ">" . $DValue->id ." - ". $DValue->value . "</td>";	
	       		$s .= ">" . $DValue->value . "</td>";		
		    }
	       	
	       	$s .= '<td><a href="" class="cbdel" cbid="'.$DValue->id.'"><img src="img/bullet_delete.png"></td>';
	       	
	       	$s .= "\n\t\t</tr>\n";
	      
	    }

       
        $s .= "\t</tbody>\n</table>";
        
        $s .= '
        <div id="pager" class="pager">
		<form>
		<div id="pager_left">
		<img src="img/first.png" class="first"/>
		<img src="img/prev.png" class="prev"/>
		</div>
		<input size="4" type="text" class="pagedisplay" disabled="disabled"/>
		<div id="pager_right">
		<img src="img/next.png" class="next"/>
		<img src="img/last.png" class="last"/>
		</div>
		<select class="pagesize">
			<option selected="selected"  value="15">15</option>
			<option value="30">30</option>
			<option value="50">50</option>
		</select>
		</form>
		</div>
        ';
        
        

        $this->html = $s;
    }

    function getHtml() {
        return $this->html;
    }

}

?>