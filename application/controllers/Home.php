<?php defined('BASEPATH') or exit ('No direct script access allowed');//防止直接访问此文件

	class Home extends CI_Controller{

		public function __construct()
		{
			parent::__construct();
			$this->load->helper('url');
			//加载表单验证类
			$this->load->library('form_validation');
			//加载session类
			$this->load->library('session');
			//加载模型
			$this->load->model('Login_model');
		}

		//加载index.html首页
		public function index(){
			$this->load->view("index.html");
		}

		//登录
		public function login(){
			//前端只进行了非空验证,后台进行账号验证，全数字且15位
			/**
			 * 通过表单验证类，设置验证规则,first参数是页面中表单域的name值，second参数是给其起个名字，third是验证规则，以竖线隔开
			 * 规则如required非空，integer必须为数字，且哪个规则先写，就先检查哪个规则，检查到错误后，后面的规则就不检查了，返回false
			 **/
			$this->form_validation->set_rules('user','账号','required|integer|exact_length[15]');
			//让验证规则跑起来
			if($this->form_validation->run() == false){
				//如果验证不通过，报错提示信息
				$error_info['error_info'] = validation_errors();
				//echo $error_info;
				$this->load->view("index.html",$error_info);
			}else{
				//格式正确，获取数据进行数据库比对，判断用户是否存在，是否密码相匹配
				$username = $this->input->post("user");
				$password = $this->input->post("password");
				//首先查询账户是否存在,存在则返回1行，不存在返回0
				$does_the_account_exist = $this->Login_model->exist_account($username);
				if($does_the_account_exist==1){
					//匹配用户名和密码是否匹配
					$pwd = $this->Login_model->match_account_pwd($username,$password);
					//对比数据库密码和用户输入密码是否一致
					if($pwd == $password){
						//用户名放入session
						$this->session->set_userdata('account', $username);
						//前端页面判断，如果erroe_info是'success'的话，转跳到登录成功页面
						$error_info['error_info'] = "success";
						$this->load->view("index.html",$error_info);
					}else{
						$error_info['error_info'] = "密码不正确";
						$this->load->view("index.html",$error_info);
					}
				}else{
					//账户不存在
					$error_info['error_info'] = "账户不存在";
					$this->load->view("index.html",$error_info);
				}
			}
		}//login() END

	}
