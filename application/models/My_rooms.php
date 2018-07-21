<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class My_rooms extends CI_Model
{

    protected $table = 'child_rooms';

    function __construct()
    {
        parent::__construct();
    }

    /**
     * @return mixed
     */
    function getCount()
    {
        return $this->db->count_all_results($this->table);
    }

    /**
     * @return bool
     */
    function store()
    {
        $this->db->insert('child_rooms',
            [
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'created_at' => date_stamp()
            ]
        );

        if($this->db->affected_rows()>0)
            return true;

        return false;
    }

    /**
     * @return bool
     */
    function update()
    {
        $this->db->update('child_rooms',
            [
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'created_at' => date_stamp()
            ],
            ['id' => $this->input->post('room_id')]
        );

        if($this->db->affected_rows()>0)
            return true;

        return false;
    }

    /**
     * @param $id
     * @return mixed
     */
    function children($id){
       $children= $this->db->select('children.id as child_id,children.first_name,children.last_name,child_rooms.name,child_rooms.description,child_room.child_id,child_room.room_id')
            ->from('children')
            ->join('child_room', 'child_room.child_id=children.id')
            ->join('child_rooms', 'child_rooms.id=child_room.room_id')
            ->where('child_rooms.id', $id)
            ->get()
            ->result();
       return $children;
    }

    /**
     * @param $id
     * @return mixed
     */
    function staff($id){
        $staff = $this->db->select('*')
            ->from('users')
            ->join('child_room_staff', 'child_room_staff.user_id=users.id')
            ->where('child_room_staff.room_id', $id)
            ->get()
            ->result();
        return $staff;
    }

    function check_unique_name($id='',$name){
        $this->db->where('name', $name);
        if($id) {
            $this->db->where_not_in('id', $id);
        }
        return $this->db->get('child_rooms')->num_rows();
    }
}