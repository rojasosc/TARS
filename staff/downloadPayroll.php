<?php

require_once "../db.php";

// encloses and escapes the given field if necessary
// source: http://stackoverflow.com/a/217434/835995
function encode_csv_field($string) {
    if(strpos($string, ',') !== false || strpos($string, '"') !== false || strpos($string, "\n") !== false) {
        $string = '"' . str_replace('"', '""', $string) . '"';
    }
    return $string;
}
try {
	$currentTermID = Configuration::get(Configuration::CURRENT_TERM);
	if($currentTermID) {
		$term = Term::getTermByID($currentTermID);
		if($term) {
			$fileName = "payroll-{$term->getYear()}-{$term->getSemester()}.csv";
			$assistants = Application::findApplications(null, null, null, $term, APPROVED, 'pay');
		}
	}
} catch (PDOException $ex) {
	//TODO: Error Handling
}


header("Content-Type: text/csv; header=present");
header("Content-Disposition: attachment; filename=$fileName");
header("Pragma: no-cache");
header("Expires: 0");

/* Table header */
echo 'University ID,First Name,Last Name,Email,CRN,Type,Class Year,Compensation,Paperwork Complete' . "\r\n";

/* Insert each position into the spreadsheet */
foreach($assistants as $assistant){
	$student = $assistant->getCreator();
	$position = $assistant->getPosition();
	$section = $position->getSection();

	/* Column values */
	$universityID = $student->getUniversityID();
	$firstName = $student->getFirstName();
	$lastName = $student->getLastName();
	$email = $student->getEmail();
	$crn = $section->getCRN();
	$type = $position->getTypeTitle();
	$classYear = $student->getClassYear();
	$compensation = $assistant->getCompensation();

	/* Echo each column value */
	echo implode(',',
		array_map(function ($field) {
			return encode_csv_field($field);
		}, array($universityID, $firstName, $lastName, $email, $crn, $type, $classYear, $compensation,'')))."\r\n";
}

