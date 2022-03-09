<?php namespace Raydragneel\Herauth\Validations;


class HerauthRules
{
    public function is_unique_db(string $str = null, string $field): bool
	{
		// Grab any data for exclusion of a single row.
		[$db,$field, $ignoreField, $ignoreValue] = array_pad(explode(',', $field), 5, null);

		// Break the table and field apart
		sscanf($field, '%[^.].%[^.]', $table, $field);

		$db = db_connect($db ?? null);
		
		$row = $db->table($table)
			  ->select('1')
			  ->where($field, $str)
			  ->limit(1);
		if (! empty($ignoreField) && ! empty($ignoreValue) && ! preg_match('/^\{(\w+)\}$/', $ignoreValue))
		{
			$row = $row->where("{$ignoreField} !=", $ignoreValue);
		}
		
		return (bool) ($row->get()->getRow() === null);
	}

	public function sometime_len(string $str, $length = 6): bool
    {
        if (empty($str)) {
            return true;
        }
        if (strlen($str) >= $length) {
            return true;
        }
        return false;
    }
}