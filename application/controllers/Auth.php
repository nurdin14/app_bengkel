<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model("user_model");
    }

    function index() {
        $this->login();
    }


	public function login()
{
    if(isset($this->session->auth) && $this->session->auth['logged_in']) {
        redirect(base_url("dashboard"));
    }

    $push = [
        "error" => FALSE,
        "username" => NULL,
        "password" => NULL,
        "pageTitle" => "Login",
        "authPage" => TRUE
    ];

    if($this->input->post()) {
        $username = $this->input->post("username");
        $password = $this->input->post("password");

        $push['username'] = $username;
        $push['password'] = $password;

        $query = $this->user_model->get(["username" => $username]);

        $error = FALSE;

        if( $query->num_rows() > 0 ) {
            $fetch = $query->result_array();
            $setSession = [
                     "logged_in" => TRUE,
                     "id" => $fetch['id']
                 ];

                 $this->session->set_userdata("auth", $setSession);
                 redirect(base_url("dashboard"));
        } else {
            $error = "Username atau password yang anda masukkan salah";
        }

        if($error) {
            $push['error'] = $error;
        }
    }

    $this->load->view('header', $push);
    $this->load->view('login', $push);
    $this->load->view('footer', $push);
}

    
    function logout() {

        if($this->session->auth['logged_in']) {
            $this->session->unset_userdata("auth");
            redirect(base_url("auth/login"));
        }
    }
}
