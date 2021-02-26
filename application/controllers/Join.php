<?php defined('BASEPATH') or exit ('No direct script access allowed');//防止直接访问此文件

	class Join extends CI_Controller{

		public function __construct()
		{
			parent::__construct();
			/*加载路径辅助函数*/
			$this->load->helper('url');
			/*加载表单验证类*/
			$this->load->library('form_validation');
			/*加载model*/
			$this->load->model('Join_model');
		}

		/*加载注册页面*/
		public function index(){
			$this->load->view('singup/register.html');
		}

		/*注册，获取form*/
		public function singup(){
			/*设置表单验证规则*/
			$this->form_validation->set_rules('name','姓名','required|max_length[6]|min_length[3]');
			$this->form_validation->set_rules('password','密码','required|alpha_numeric');
			$this->form_validation->set_rules('mobile','手机号','required|numeric|exact_length[11]');
			$this->form_validation->set_rules('email','邮箱','valid_email');
			$this->form_validation->set_rules('question','密保问题','required');
			$this->form_validation->set_rules('answer','密保答案','required');
			/*让验证规则跑起来*/
			if($this->form_validation->run() == false){
				$error_info['error_info'] = validation_errors();
				$this->load->view("singup/register.html",$error_info);
			}else{
				/*获取用户输入的姓名、密码、电话、[邮箱]、密保问题、密保答案*/
				$name = $this->input->post('name');				$password = $this->input->post('password');
				$mobile = $this->input->post('mobile');			$email = $this->input->post('email');
				$question = $this->input->post('question');		$answer = $this->input->post('answer');
				/*生成3个5位整数连一起成15位银行卡号*/
				$num = '1234567890';
				$rands1= substr(str_shuffle($num),0,5);/*打乱字符串生成5位长度*/
				$rands2= substr(str_shuffle($num),0,5);/*打乱字符串生成5位长度*/
				$rands3= substr(str_shuffle($num),0,5);/*打乱字符串生成5位长度*/
				$account = $rands2.$rands1.$rands3;
				/*将用户数据插入数据库*/
				$data = array(
					'account' => $account,
					'name' => $name,
					'password' => $password,
					'mobile' => $mobile,
					'email' => $email,
					'balance' => '0',
					'question' => $question,
					'answer' => $answer,
				);
				/*model调用*/
				$this->Join_model->insert_join_info($data);
				$act['account'] = $account;
				$this->load->view("singup/register.html",$act);
			}
		}



	}
