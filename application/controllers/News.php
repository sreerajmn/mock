<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class News extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model("news_model");
        $this->lang->load('basic', $this->config->item('language'));
        // redirect if not loggedin
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in['base_url'] != base_url()) {
            $this->session->unset_userdata('logged_in');
            redirect('login');
        }
    }

    public function index($limit = '0') {

        $logged_in = $this->session->userdata('logged_in');

        $data['limit'] = $limit;
        $data['title'] = $this->lang->line('news_events');
        // fetching news list
        $data['result'] = $this->news_model->news_list($limit);
        $this->load->view('header', $data);
        $this->load->view('news/news_list', $data);
        $this->load->view('footer', $data);
    }

    public function add_new() {

        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in['su'] != '1') {
            exit($this->lang->line('permission_denied'));
        }



        $data['title'] = $this->lang->line('add_new') . ' ' . $this->lang->line('news');

        $this->load->view('header', $data);
        $this->load->view('news/new_news', $data);
        $this->load->view('footer', $data);
    }

    public function edit_news($news_id) {

        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in['su'] != '1') {
            exit($this->lang->line('permission_denied'));
        }



        $data['title'] = $this->lang->line('edit') . ' ' . $this->lang->line('news');
        $data['news'] = $this->news_model->get_news($news_id);

        $this->load->view('header', $data);
        $this->load->view('news/edit_news', $data);
        $this->load->view('footer', $data);
    }

    public function insert_news() {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in['su'] != '1') {
            exit($this->lang->line('permission_denied'));
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('title', 'title', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message', "<div class='alert alert-danger'>" . validation_errors() . " </div>");
            redirect('news/add_new/');
        } else {
            $news_id = $this->news_model->insert_news();

            redirect('news/edit_news/' . $news_id);
        }
    }

    public function update_news($news_id) {


        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in['su'] != '1') {
            exit($this->lang->line('permission_denied'));
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('title', 'title', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message', "<div class='alert alert-danger'>" . validation_errors() . " </div>");
            redirect('news/edit_news/' . $news_id);
        } else {
            $news_id = $this->news_model->update_news($news_id);

            redirect('news/edit_news/' . $news_id);
        }
    }

    public function remove_news($news_id) {

        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in['su'] != '1') {
            exit($this->lang->line('permission_denied'));
        }

        if ($this->news_model->remove_news($news_id)) {
            $this->session->set_flashdata('message', "<div class='alert alert-success'>" . $this->lang->line('removed_successfully') . " </div>");
        } else {
            $this->session->set_flashdata('message', "<div class='alert alert-danger'>" . $this->lang->line('error_to_remove') . " </div>");
        }
        redirect('news');
    }

    public function news_detail($news_id) {

        $logged_in = $this->session->userdata('logged_in');

        $data['title'] = $this->lang->line('news');

        $data['news'] = $this->news_model->get_news($news_id);
        $this->load->view('header', $data);
        $this->load->view('news/news_detail', $data);
        $this->load->view('footer', $data);
    }

    function submit_news() {

        if ($this->news_model->submit_result()) {
            $this->session->set_flashdata('message', "<div class='alert alert-success'>" . $this->lang->line('news_submit_successfully') . " </div>");
        } else {
            $this->session->set_flashdata('message', "<div class='alert alert-danger'>" . $this->lang->line('error_to_submit') . " </div>");
        }
        $this->session->unset_userdata('rid');

        redirect('news');
    }

}
