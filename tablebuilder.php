<?php 

function starttable() {
	return "<table cellpadding='0' cellspacing='0' class='db-table' align='center'>";
}

function maketableheader($fields) {
	$viewtable = '';
	
	$viewtable .= "<tr>";
	foreach ($fields as $field) {
		$viewtable .= "<th>$field</th>";
	}
	$viewtable .= "</tr>";
	
	return $viewtable;
}

function maketable($result, $fields) {
	$viewtable = '';
			
	foreach($result as $row) {
		$viewtable .= makerow($row);
	}
	
	return $viewtable;
}

function makerow($row) {
	$viewtable = '';
	
	$viewtable .= "<tr>";
	for ($i=0; $i< sizeof($row); $i++) {
		if (isset($row[$i])) {
			$viewtable .= "<td>" .$row[$i]. "</td>" ;
		}
		else {
			$viewtable .= "<td>&nbsp;</td>" ;
		}
	}
	$viewtable .= "</tr>" ;
	return $viewtable;
}

function endtable() {
	return "</table>";
}

?>
