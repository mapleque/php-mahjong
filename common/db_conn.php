<?php

class DBConn extends mysqli
{
	function __construct($addr, $user, $pass, $db, $port = '3306')
	{
		parent::init();
		parent::real_connect($addr, $user, $pass, $db, $port, null, MYSQLI_CLIENT_FOUND_ROWS);
	}

	function __destruct()
	{
		parent::close();
	}

	public function select($query, $bind = null)
	{
		$select_stmt = self::execQuery($query, $bind);

		$variables = [];
		$temp_data = [];

		$metadata = $select_stmt->result_metadata();
		for ($field = $metadata->fetch_field(); $field ; $field = $metadata->fetch_field())
			$variables[] = &$temp_data[$field->name];
		call_user_func_array([ $select_stmt, 'bind_result' ], $variables);

		$result = [];
		while ($select_stmt->fetch()) {
			$obj = [];
			foreach ($temp_data as $k => $v) {
				$obj[$k] = $v;
			}
			$result[] = $obj;
		}

		$select_stmt->close();
		return $result;
	}

	public function insert($query, $bind = null)
	{
		$insert_stmt = self::execQuery($query, $bind);
		if ($insert_stmt === FALSE) {
			return -1;
		}

		$insert_id = $insert_stmt->insert_id;
		$insert_stmt->close();
		return (int)$insert_id;
	}

	public function exec($query, $bind = null)
	{
		$stmt = self::execQuery($query, $bind);
		if ($stmt === FALSE) {
			return -1;
		}

		$matched_rows = $stmt->affected_rows;
		$stmt->close();
		return $matched_rows;
	}

	private function execQuery($query, $params = null)
	{
		$stmt = self::prepare($query);
		if (is_array($params) && count($params) > 0) {
			$params_refs = [];
			$types = '';
			foreach ($params as $k => $v) {
				if ($v === null) {
					static $null = null;
					$params_refs[$k] = &$null;
					$types .= 'i';
				} else {
					$params_refs[$k] = &$params[$k];
					if (is_int($v) || is_bool($v))
						$types .= 'i';
					elseif (is_float($v))
						$types .= 'd';
					elseif (is_string($v))
						$types .= 's';
					else
						$types .= 'b';
				}
			}
			array_unshift($params_refs, $types);
			call_user_func_array([ $stmt, 'bind_param' ], $params_refs);
		}

		if ($stmt->execute()) {
			return $stmt;
		} else {
			$stmt->close();
			return FALSE;
		}
	}
}
