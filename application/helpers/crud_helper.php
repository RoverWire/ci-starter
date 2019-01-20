<?php

/**
 * Validate Selection
 *
 * An alternative to check a dropdown selected option
 *
 * @param  mixed  $value1 value to compare
 * @param  mixed  $value2 value to compare
 * @return string         selected or empty
 */

if (! function_exists('validate_select')) {
	function validate_select($value1, $value2)
	{
		return ($value1 == $value2) ? 'selected' : '';
	}
}

/**
 * Validate Checkbox
 *
 * An alternative to validate if a checkbox is checked or not
 *
 * @access	public
 * @param	mixed  $value1 value to compare
 * @param	mixed  $value2 value to compare
 * @return	string         checked or empty
 */

if (! function_exists('validate_checkbox'))
{
	function validate_checkbox($value1, $value2)
	{
		return ($value1 == $value2) ? 'checked' : '';
	}
}
