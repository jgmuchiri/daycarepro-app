<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @file      : children.php
 * @author    : JMuchiri
 * @Copyright 2017 A&M Digital Technologies
 */
class Children extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        setRedirect();
        allow('admin,manager,staff,parent');
        $this->load->model('My_invoice', 'invoice');
        $this->module = 'modules/children/';
    }

    /*
     * default page
     * @return void
     */
    function index()
    {
        if(is('parent')) {
            $children = $this->parent->getChildren();
            page('modules/parent/index', compact('children'));
        } else {
            page($this->module.'index');
        }
    }

    /*
     * child registration form
     * @return void
     */

    function register()
    {
        page($this->module.'register');
    }

    function roster()
    {
        if(isset($_GET['group']) && $_GET['group']>0){
            $this->db->select('*');
            $this->db->where('child_group.group_id',$_GET['group']);
            $this->db->from('children');
            $this->db->join('child_group','child_group.child_id=children.id','left');
            $children=$this->db->get()->result();
        }else{
            if(isset($_GET['inactive'])){
                $this->db->where('status',0);
            }
            if(isset($_GET['active']) || isset($_GET['daily'])){
                $this->db->where('status',1);
            }
            $children = $this->db->get('children')->result();
        }

        $this->load->view($this->module.'roster', compact('children'));
    }

    function storeGroup()
    {
        allow('admin');
        $this->form_validation->set_rules('name', lang('name'), 'required|xss_clean|trim|is_unique[child_groups.name]');
        $this->form_validation->set_rules('description', lang('description'), 'xss_clean|trim');
        if($this->form_validation->run() == true) {
            $this->db->insert('child_groups',
                [
                    'name' => $this->input->post('name'),
                    'description' => $this->input->post('description'),
                    'created_at' => date_stamp()
                ]
            );
            if($this->db->affected_rows()>0) {
                flash('success', lang('Child group created! You can now assign children'));
            } else {
                flash('error', lang('request_error'));
            }
        } else {
            validation_errors();
            flash('error');
        }
        redirect('children#groups');
    }

    function childrenToGroup()
    {
        allow('admin');
        $this->form_validation->set_rules('child_id[]', lang('children'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('group_id', lang('group'), 'required|trim|xss_clean');
        if($this->form_validation->run() == true) {
            $this->db->where('group_id', $this->input->post('group_id'))->delete('child_group');
            foreach ($this->input->post('child_id') as $child) {
                $this->db->insert('child_group', [
                    'child_id' => $child,
                    'group_id' => $this->input->post('group_id'),
                    'created_at' => date_stamp()
                ]);
            }
            flash('success', lang('request_success'));
        } else {
            validation_errors();
            flash('error');
        }
        redirect('children?group='.$this->input->post('group_id').'#groups');
    }

    function staffToGroup()
    {
        allow('admin');
        $this->form_validation->set_rules('user_id[]', lang('children'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('group_id', lang('group'), 'required|trim|xss_clean');
        if($this->form_validation->run() == true) {
            $this->db->where('group_id', $this->input->post('group_id'))->delete('child_group_staff');
            foreach ($this->input->post('user_id') as $user) {
                $this->db->insert('child_group_staff', [
                    'user_id' => $user,
                    'group_id' => $this->input->post('group_id'),
                    'created_at'=>date_stamp()
                ]);
            }
            flash('success', lang('request_success'));
        } else {
            validation_errors();
            flash('error');
        }
        redirect('children?group='.$this->input->post('group_id').'#groups');
    }
}