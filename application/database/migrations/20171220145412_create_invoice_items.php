<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_invoice_items extends CI_Migration
{

    /**
     * up (create table)
     *
     * @return void
     */
    public function up()
    {

        // Add Fields.
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => '11',
            ),
            'invoice_id' => array(
                'type' => 'INT',
                'constraint' => '11',
            ),
            'item_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'description' => array(
                'type' => 'TEXT',
            ),
            'price' => array(
                'type' => 'FLOAT',
            ),
            'qty' => array(
                'type' => 'INT',
                'constraint' => '11',
            ),
            'discount' => array(
                'type' => 'FLOAT',
            ),
        ));

        // Add Primary Key.
        $this->dbforge->add_key("id", TRUE);

        // Table attributes.

        $attributes = array(
            'ENGINE' => 'InnoDB',
        );

        // Create Table invoice_items
        $this->dbforge->create_table("invoice_items", TRUE, $attributes);

    }

    /**
     * down (drop table)
     *
     * @return void
     */
    public function down()
    {
        // Drop table invoice_items
        $this->dbforge->drop_table("invoice_items", TRUE);
    }

}
