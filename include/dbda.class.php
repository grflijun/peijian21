<?php
class DBDA
{
	private $db_host = "localhost:3306";
	private $db_user = "renyanli";
	private $user_pwd = "dali123";
	private $db_name = "huijie";

	private $temp_db_obj = NULL;

	public function __destruct()
	{
		self::DeleteTempDb();
	}

	public function CreateTempDb()
	{
		self::DeleteTempDb();

		$this->temp_db_obj = new mysqli($this->db_host, $this->db_user, $this->user_pwd, $this->db_name);

		if(mysqli_connect_error())
		{
			$this->temp_db_obj = NULL;
			return false;
		}

		return true;
	}

	public function DeleteTempDb()
	{
		if(NULL != $this->temp_db_obj)
		{
			$this->temp_db_obj->close();
			$this->temp_db_obj = NULL;
		}

		return true;
	}

	public function real_escape_string($str)
	{
		if(NULL != $this->temp_db_obj)
		{
			return $this->temp_db_obj->real_escape_string($str);
		}

		return '';
	}

	public function temp_sql($sql_func)	//数据库的sql操作
	{
		if(NULL == $this->temp_db_obj)
			return false;

		return $this->temp_db_obj->query($sql_func);
//		$result = $this->temp_db_obj->query($sql_func);
//		if($type == 1)
//		{
//			return $result;		//增删改语句返回true或false
//		}
//		else
//		{
//			return $result->fetch_all();	//查询语句返回二维数组
//		}
	}



	//easyui-datagrid用的数据查询
	public function query_grid($sql_cnt, $sql_data)
	{
		$db = new mysqli($this->db_host, $this->db_user, $this->user_pwd, $this->db_name);
		if($db->connect_errno)
			return false;

		$arr_ret = array();

		$rs = $db->query($sql_cnt);
		$row = $rs->fetch_array();
		$arr_ret["total"] = $row[0];

		$rs = $db->query($sql_data);
		$items = array();
		while($row = $rs->fetch_array())
		{
			array_push($items, $row);
		}
		$arr_ret["rows"] = $items;

		return json_encode($arr_ret);
	}

	//easyui-combobox用的数据查询
	public function query_combo($sql_list, $item_add = NULL)
	{
		$db = new mysqli($this->db_host, $this->db_user, $this->user_pwd, $this->db_name);
		if($db->connect_errno)
			return false;

		$data_list = array();
		if(NULL != $item_add)
		{
			array_push($data_list, $item_add);
		}

		$rs = $db->query($sql_list);
		while($row = $rs->fetch_array())
		{
			array_push($data_list, $row);
		}
		return json_encode($data_list);
	}



	/*
	query方法:执行用户给的sql语句,并返回相应的结果
	$sql:用户需要执行的sql语句
	$type:用户需要执行的sql语句的类型
	return:如果是增删语句改返回true或false,如果是查询语句返回二维数组
	*/
	public function query_sql($sql)
	{
		$db = new mysqli($this->db_host, $this->db_user, $this->user_pwd, $this->db_name);
		if(mysqli_connect_error())
			return false;

		return $db->query($sql);
/*
		if(mysqli_connect_error())
		{
			return "连接失败!";
		}

		$result = $db->query($sql);
		if($type == 1)
		{
			return $result;		//增删改语句返回true或false
		}
		else
		{
			return $result->fetch_all();	//查询语句返回二维数组
		}
*/
	}

	//此方法用于ajax中用于对取出的数据（二维数组）进行拼接字符串处理
	public function StrQuery($sql, $type = 1)
	{
		$db = new mysqli($this->db_host, $this->db_user, $this->user_pwd, $this->db_name);
		if(mysqli_connect_error())
		{
			return "连接失败!";
		}

		$result = $db->query($sql);
		if($type == 1)
		{
			return $result;		//增删改语句返回true或false
		}
		else
		{
			$arr = $result->fetch_all();	//查询语句返回二维数组

			$str = "";

			foreach($arr as $v)
			{
				$str = $str.implode("^", $v)."|";
			}
			$str = substr($str, 0, strlen($str) - 1);

			return $str;
		}
	}

	//此方法用于ajax中用于返回为json数据类型时使用
	public function JsonQuery($sql, $type = 1)
	{
		$db = new mysqli($this->db_host, $this->db_user, $this->user_pwd, $this->db_name);
		if(mysqli_connect_error())
		{
			return "连接失败!";
		}

		$result = $db->query($sql);
		if($type == 1)
		{
			return $result;//增删改语句返回true或false
		}
		else
		{
			$arr = $result->fetch_all(MYSQLI_ASSOC);
			return json_encode($arr);
		}
	}
}
?>
