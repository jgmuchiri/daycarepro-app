<?php
/**
 * @package     daycarepro
 * @copyright   2017 A&M Digital Technologies
 * @author      John Muchiri
 * @link        https://amdtllc.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
$groups = array(
    'admin','manager','staff','parent'
);
foreach ($groups as $group){
    $this->db->insert('groups',array('name'=>$group->name,'description'=>$group->description));
}
?>