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
	if (isset($superglobal[$key])) {
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

const FORM_VALIDATE_NOTEMPTY = 1;
const FORM_VALIDATE_IGNORE = 2;
const FORM_VALIDATE_EMAIL = 3;
const FORM_VALIDATE_OTHERFIELD = 4;
const FORM_VALIDATE_NUMERIC = 5;
const FORM_VALIDATE_NUMSTR = 6;
const FORM_VALIDATE_UPLOAD = 7;

/**
 * get_invalid_values($form_fields)
 * Given the output of get_form_values(), returns an array of the fields that
 * are not valid for the given value 
 */
function get_invalid_values($values, $params = array()) {
	$invalids = array();
	foreach ($values as $key => $value) {
		$validate_as = isset($params[$key]) ? $params[$key] :
			array('type' => FORM_VALIDATE_NOTEMPTY);
		$invalid = false;
		if (is_int($validate_as)) {
			$validate_as = array('type' => $validate_as);
		} elseif (!isset($validate_as['type'])) {
			$validate_as = array('type' => FORM_VALIDATE_NOTEMPTY);
		}
		switch ($validate_as['type']) {
		case FORM_VALIDATE_NOTEMPTY:
			$invalid = ($value === false) || ($value === '');
			break;
		case FORM_VALIDATE_IGNORE:
			$invalid = false;
			break;
		case FORM_VALIDATE_EMAIL:
			$invalid = filter_var($value, FILTER_VALIDATE_EMAIL) === false;
			break;
		case FORM_VALIDATE_OTHERFIELD:
			if (isset($validate_as['field'])) {
				$field_key = $validate_as['field'];
				$invalid = isset($values[$field_key]) ?
					($values[$field_key] !== $value) : true;
			} else {
				$invalid = true;
			}
			break;
		case FORM_VALIDATE_NUMERIC:
			if (isset($validate_as['reject_signed'])) {
				if ($value[0] == '-' || $value[0] == '+') {
					$value = '';
				}
			}
			if (isset($validate_as['reject_decimal'])) {
				if (strpos($value, '.') !== false) {
					$value = '';
				}
			}
			$invalid = filter_var($value, FILTER_VALIDATE_FLOAT, $validate_as) === false;
			break;
		case FORM_VALIDATE_NUMSTR:
			$value = preg_replace('/([^\d]+)/', '', $value);
			if (isset($validate_as['min_length'])) {
				if (strlen($value) < $validate_as['min_length']) {
					$value = '';
				}
			}
			if (isset($validate_as['max_length'])) {
				if (strlen($value) > $validate_as['max_length']) {
					$value = '';
				}
			}
			$invalid = !is_numeric($value);
			break;
		}
		if ($invalid) {
			$invalids[] = $key;
		}
	}
	return $invalids;
}

