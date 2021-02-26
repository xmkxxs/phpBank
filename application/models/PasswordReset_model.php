<?php defined('BASEPATH') or exit ('No direct script access allowed');

	class PasswordReset_model extends CI_Model{

		public function __construct()
		{
			parent::__construct();
			//连接数据库
			$this->load->database();
		}

		//根据用户名获取用户的密保问题
		public function get_question_by_account_model($account){
			$this->db->where('account',$account);
			$this->db->select('question');
			$query = $this->db->get('tb_user');
			$question='';
			foreach ($query->result() as $row){
				$question = $row->question;
			}
			return $question;
		}

		//根据用户名获取用户密保答案
		public function get_answer_by_account_model($account){
			$this->db->where('account',$account);
			$this->db->select('answer');
			$query = $this->db->get('tb_user');
			$answer='';
			foreach ($query->result() as $row){
				$answer = $row->answer;
			}
			return $answer;
		}

		//更新密码根据账号
		public function updata_pwd_by_account($account,$new_pwd){
			$data = array(
				'password' => $new_pwd,
			);
			$this->db->where('account', $account);
			$this->db->update('tb_user', $data);
		}

	}
