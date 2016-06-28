<?php 

$string = "<?php


/* Location: ./application/models/$m_file */
/* Derived from Harviacode  */
/* Modified by Vivek Raghunathan ".date('Y-m-d H:i:s')." */
/* Email : vivekra@dqserv.com */
/* http://dqserv.com */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class " . $m . " extends CI_Model
{

    public \$table = '$table_name';
    public \$id = '$pk';
    public \$order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // get all
    function get_all()
    {
        \$this->db->order_by(\$this->id, \$this->order);
        return \$this->db->get(\$this->table)->result();
    }
	
	// get all data array
    function get_all_".$c_url."()
    {
        \$this->db->order_by(\$this->id, \$this->order);
        return \$this->db->get(\$this->table)->result_array();
    }

    // get data by id
    function get_by_id(\$id)
    {
        \$this->db->where(\$this->id, \$id);
        return \$this->db->get(\$this->table)->row();
    }
	
	
	// get data array by id
    function get_".$c_url."(\$id)
    {
        \$this->db->where(\$this->id, \$id);
        return \$this->db->get(\$this->table)->row_array();
    }
	    
    // get total rows
    function total_rows(\$q = NULL) {
        \$this->db->like('$pk', \$q);";

foreach ($non_pk as $row) {
    $string .= "\n\t\$this->db->or_like('" . $row['column_name'] ."', \$q);";
}    

$string .= "\n\t\$this->db->from(\$this->table);
        return \$this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data(\$limit, \$start = 0, \$sort_column = '',\$sort_by = '',\$q = NULL) {
		if(\$sort_column!='' && \$sort_by!= '' ){
            \$this->db->order_by(\$sort_column, \$sort_by);
        }
		else { 
        \$this->db->order_by(\$this->id, \$this->order);
		}
        \$this->db->like('$pk', \$q);";

foreach ($non_pk as $row) {
    $string .= "\n\t\$this->db->or_like('" . $row['column_name'] ."', \$q);";
}    

$string .= "\n\t\$this->db->limit(\$limit, \$start);
        return \$this->db->get(\$this->table)->result();
    }

    // insert data
    function insert(\$data)
    {
        \$this->db->insert(\$this->table, \$data);
    }

    // update data
    function update(\$id, \$data)
    {
        \$this->db->where(\$this->id, \$id);
        \$this->db->update(\$this->table, \$data);
    }

    // delete data
    function delete(\$id)
    {
        \$this->db->where(\$this->id, \$id);
        \$this->db->delete(\$this->table);
    }
	
	// check_exist
    function check_exist(\$val,\$col,\$id)
    {
        \$this->db->where(\$col, \$val);
		if(\$id){
		\$this->db->where(\$this->id.' !=', \$id);
		}
        \$this->db->from(\$this->table);
        return \$this->db->count_all_results();
    }

}

/* End of file $m_file */";




$hasil_model = createFile($string, $target."models/" . $m_file);

?>