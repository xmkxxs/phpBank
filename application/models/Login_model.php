<?php defined('BASEPATH') or exit ('No direct script access allowed');

	class Login_model extends CI_Model{

		public function __construct()
		{
			parent::__construct();
			//连接数据库
			$this->load->database();
		}

		//账号是否在数据库中存在
		public function exist_account($username){
			$this->db->where('account',$username);
			$this->db->from('tb_user');
			$result = $this->db->count_all_results();
			//存在则返回1行，不存在返回0
			return $result;
		}

		//匹配用户名和密码是否匹配
		public function match_account_pwd($username,$password){
			//根据用户名获取数据库中的密码
			//$sql="select password from tb_user where account = ?";
			//$result = $this->db->query($sql,array($username));
			$this->db->select('password');
			$this->db->where('account',$username);
			$result = $this->db->get('tb_user');
			$pwd = "";
			foreach ($result->result() as $item) {
				$pwd = $item->password;
			}
			return $pwd;
		}


	}


