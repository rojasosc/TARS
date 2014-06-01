<?php

include("../db.php");

$assistants = getPayrollByTerm($term);


header("Content-Type: application/vnd.ms-excel");
echo 'ID'. "\t". 'First Name' . "\t" . 'Last Name' . "\t" . 'Email' . "\t" .'Course' . "\t" . 'Type' . "\t" . 'Class Year' . "\t" . 'Compensation' . "\n";
foreach($assistants as $assistant){

	echo $assistant['studentID']. "\t". $assistant['firstName'] . "\t" . $assistant['lastName'] . "\t" . $assistant['email'] . "\t" . $assistant['courseNumber'] . "\t" . $assistant['type'] . "\t" . $assistant['classYear'] . "\t" . $assistant['compensation'] ."\n";

}

header("Content-disposition: attachment; filename=spreadsheet.xls");

?>