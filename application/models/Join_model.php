<?php defined('BASEPATH') or exit ('No direct script access allowed');//防止直接访问此文件

 	class Join_model extends CI_Model{

 		public function __construct()
		{
			parent::__construct();
			/*加载数据库*/
			$this->load->database();
		}

		/*用户注册输入插入数据库*/
		public function insert_join_info($data){
			$this->db->insert('tb_user', $data);
		}

	}
