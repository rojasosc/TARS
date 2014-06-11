<?php
	include("../db.php");
	$term = CURRENT_TERM;
	$fileName = "payroll" . CURRENT_TERM . ".xls";
	$assistants = getPayrollByTerm($term);
	header("Content-Type: application/vnd.ms-excel");
	
	/* Table header */
	echo 'University ID'. "\t". 'First Name' . "\t" . 'Last Name' . "\t" . 'Email' . "\t" .'CRN' . "\t" . 'Type' . "\t" . 'Class Year' . "\t" . 'Compensation' . "\n";

	/* Insert each position into the spreadsheet */
	foreach($assistants as $assistant){
		$student = $assistant->getStudent();
		$position = $assistant->getPosition();
		$course = $position->getCourse();
		
		/* Column values */
		$universityID = $student->getUniversityID();
		$firstName = $student->getFirstName();
		$lastName = $student->getLastName();
		$email = $student->getEmail();
		$crn = $course->getCRN();
		$type = $position->getPositionType();
		$classYear = $student->getClassYear();
		$compensation = $assistant->getCompensation();
		
		/* Echo each column value */
		echo $universityID ."\t". $firstName . "\t" . $lastName . "\t" . $email . "\t" . $crn . "\t" . $type . "\t" . $classYear . "\t" . $compensation ."\n";

	}
	header("Content-disposition: attachment; filename=".$fileName);
?>