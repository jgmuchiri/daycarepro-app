<?php
use phpDocumentor\Reflection\Types\Null_;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * @file      : child.php
 * @author    : JMuchiri
 * @Copyright 2017 A&M Digital Technologies
 */
class Child extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        auth();
        $this->load->model('My_invoice', 'invoice');
        $this->load->model('My_food', 'food');
        $this->load->model('My_user');
        $this->module = 'child/';
        $this->title = lang('child');
    }

    /*
     * default page
     * @return void
     */
    public function index($daycare_id = NULL,$id)
    {        
        if (!authorizedToChild(user_id(), $id)) {
            flash('error', lang('You do not have permission to view this child\'s profile'));
            redirectPrev();
        }

        $this->session->set_userdata('view_child_id', $id);
        $child = $this->child->child($id);

        $pickups = $this->db->where('child_id', $id)->get('child_pickup')->result();
        if (empty($child)) {
            flash('error', lang('record_not_found'));
            redirect($daycare_id.'/children');
        }
        dashboard_page($this->module . 'index', compact('child', 'pickups'),$daycare_id);
    }

    public function store($daycare_id = NULL)
    {
        allow(['admin', 'manager', 'staff', 'parent']);

        if ($this->_validate_child()) {
            $register = $this->child->register(true,$daycare_id);            
            if (false !== $register) {
                flash('success', lang('request_success'));
                redirect($daycare_id.'/child/' . $register);
            } else {
                flash('error', lang('request_error'));
            }
        } else {
            set_flash(['nickname', 'first_name', 'last_name', 'national_id', 'bday', 'blood_type', 'gender', 'ethnicity', 'religion', 'birthplace']);

            validation_errors();
            flash('danger');
        }
        redirect($daycare_id.'/children', 'refresh');
    }

    /*
     * validate and update child information
     * @params int $id
     * @return void
     */

    public function update($daycare_id = NULL)
    {      
        allow(['admin', 'manager', 'staff']);

        if ($this->_validate_child()) {
            $this->child->update_child($this->input->post('child_id') , $daycare_id);
        } else {
            set_flash(['nickname', 'first_name', 'last_name', 'national_id', 'bday', 'blood_type', 'gender', 'status']);
            validation_errors();
            flash('danger');       
        }
        redirectPrev();
    }

    /*
     * deleting is currently disable. Only sets record as inactive
     * @return void
     */
    public function deleteChild($id)
    {
        allow('admin');
        $this->db->where('id', $id);
        if ($this->db->update('children', ['status', 0])) {
            flash('success', lang('request_success'));
        } else {
            flash('danger', lang('request_error'));
        }
        redirect('children', 'refresh');
    }

    /*
     * upload photos to specific db
     * @param $id int
     * @param $db string
     */

    public function uploadPhoto($id = '')
    {
        allow(['admin', 'manager', 'staff']);
        if($this->child->uploadPhoto($id)){
            flash('success', lang('request_success'));

        }else{
            flash('error',lang('request_error'));
        }

        redirectPrev();
    }

    public function invoice($status = '')
    {
        $data['status'] = $status;
        page($this->module . 'accounting/index', $data);
    }

    public function reports($daycare_id,$id)
    {       
        if (!authorizedToChild($this->user->uid(), $id)) {
            flash('error', lang('You do not have permission to view this child\'s profile'));
            redirectPrev();
        }

        $child = $this->child->first($id);       
        $attendance = $this->db->where('child_id', $id)->order_by('id', 'DESC')->get('child_checkin');        
        $nyForm = $this->db->where('child_id', $id)->get('form_ny_attendance')->row();       
        page($this->module . 'reports/index', compact('child', 'attendance', 'nyForm','daycare_id'));
    }

    /*
     * check_in
     */
    public function checkInOut()
    {
        allow(['admin', 'manager', 'staff']);

        $child_id = uri_segment(3);
        $parents = $this->child->getParents($child_id)->result();
        $authPickups=$this->db->where('child_id', $child_id)->get('child_pickup')->result();

        $action = uri_segment(4);
        $this->load->view($this->module . 'check_in_out', compact('child_id','parents','authPickups','action'));
    }

    /*
     * check_out
     */
    public function checkOut($id)
    {
        allow(['admin', 'manager', 'staff']);

        $data = [
            'child_id' => $id,
            'parents' => $this->child->getParents($id)->result(),
            'authPickups' => $this->db->where('child_id', $id)->get('child_pickup')->result(),
        ];

        $this->load->view($this->module . 'check_out', $data);
    }

    /*
     * check in
     */
    public function doCheckIn($child_id)
    {
        allow(['admin', 'manager', 'staff']);

        $this->form_validation->set_rules('in_guardian', lang('authorized_pickup'), 'required|trim|xss_clean');
        if ($this->form_validation->run() == true) {
            if ($this->child->check_in($child_id)) {
                flash('success', lang('request_success'));
            } else {
                flash('danger', lang('request_error'));
            }
        } else {
            validation_errors();
            flash('danger');
        }
        redirectPrev();
    }

    /*
     * check out
     */
    public function doCheckOut($child_id)
    {
        allow(['admin', 'manager', 'staff']);

        $this->form_validation->set_rules('out_guardian', lang('authorized_pickup'), 'required|trim|xss_clean');
        if ($this->form_validation->run() == true) {
            if ($this->child->check_out($child_id)) {
                flash('success', lang('request_success'));
            } else {
                flash('danger', lang('request_error'));
            }
        } else {
            validation_errors();
            flash('danger');
        }
        redirectPrev();
    }

    /*
     * assign parent
     */
    public function assignParent($child_id)
    {
        allow(['admin', 'manager', 'staff']);

        $this->load->view($this->module . 'assign_parent', compact('child_id'));
    }

    public function doAssignParent($daycare_id,$child_id)
    {        
        allow(['admin', 'manager', 'staff']);

        $this->child_id = $child_id;
        $this->form_validation->set_rules('parent', lang('parent'), 'required|trim|xss_clean|callback_user_not_assigned');
        if ($this->form_validation->run() == true) {
            $data = [
                'user_id' => $this->input->post('parent'),
                'child_id' => $child_id,
            ];
            if ($this->db->insert('child_parents', $data)) {
                flash('success', lang('request_success'));

                $parent = $this->My_user->first($this->input->post('parent'));
                $child = $this->child->first($child_id);
                $data = [
                    'to' => $parent->email,
                    'subject' => lang('assigned_child_subject'),
                    'message' => sprintf(lang('assigned_child_message'), $child->first_name . ' ' . $child->last_name, format_date($child->bday, false)),
                ];
                $this->mailer->send($data);
            }
        } else {
            flash('danger');
            validation_errors();
        }
        redirectPrev();
    }

    /*
     * user_not_assigned
     * ensure user has not already been assigned
     */
    public function user_not_assigned()
    {
        $user_id = $this->input->post('parent');
        $this->db->where('user_id', $user_id);
        $this->db->where('child_id', $this->child_id);
        $query = $this->db->get('child_parents');

        if (count((array) $query->row())) {
            $this->form_validation->set_message('user_not_assigned', lang('user_already_assigned'));
            flash('danger', lang('request_error'));
            return false;
        } else {
            return true;
        }
    }

    /*
     * removeParent
     */
    public function removeParent($child_id, $parent_id)
    {
        allow(['admin', 'manager', 'staff']);

        if ($this->db->where('child_id', $child_id)
            ->where('user_id', $parent_id)
            ->delete('child_parents')) {
            flash('success', lang('request_success'));
        } else {
            flash('danger', lang('request_error'));
        }
        redirectPrev();
    }


    protected function _validate_child(){
        $this->form_validation
        ->set_rules('nickname', lang('nickname'), 'trim|xss_clean')
        ->set_rules('first_name', lang('first_name'), 'required|trim|xss_clean')
        ->set_rules('last_name', lang('last_name'), 'required|trim|xss_clean')
        ->set_rules('national_id', lang('national_id'), 'required')
        ->set_rules('bday', lang('birthday'), 'required|trim|xss_clean')
        ->set_rules('blood_type', lang('birthday'), 'trim|xss_clean')
        ->set_rules('gender', lang('gender'), 'required|trim|xss_clean')
        ->set_rules('ethnicity', lang('Ethnicity'), 'trim|xss_clean')
        ->set_rules('religion', lang('religion'), 'trim|xss_clean')
        ->set_rules('birthplace', lang('birthplace'), 'trim|xss_clean')
        ->set_rules('status', lang('status'), 'required|trim|xss_clean');

        if($this->form_validation->run() == true) return true;

        return false;
    }
}
