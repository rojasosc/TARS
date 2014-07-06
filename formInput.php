<?php

/**
 * get_form_value($form_field_name):
 * Gets the value submitted in this form field,
 * or false if nothing was submitted or the field was left blank.
 */
function get_form_value($key, $default = false, $fromPost = true) {
	$superglobal = $_GET;
	if ($fromPost) {
		$superglobal = $_POST;
	}
	if (isset($superglobal[$key]) && !empty($superglobal[$key])) {
		return $superglobal[$key];
	} else {
		return $default;
	}
}

/**
 * get_form_values($form_field_names):
 * Gets an array of $field_name => $field_values using get_form_value() on
 * each element of the given array.
 */
function get_form_values($key_names, $superglobal = true) {
	$values = array();
	foreach ($key_names as $key) {
		$values[$key] = get_form_value($key, false, $superglobal);
	}
	return $values;
}

/**
 * get_invalid_values($form_fields)
 * Given the output of get_form_values(), returns an array of the fields that
 * had no set value (!isset()) or the set value was blank (empty())
 */
function get_invalid_values($values) {
	$falses = array();
	foreach ($values as $key => $value) {
		if (!$value) {
			$falses[] = $key;
		}
	}
	return $falses;
}

