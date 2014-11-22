<?php if (!defined('BASEPATH'))  exit('No direct script access allowed');
/**
 * SHOP			A full featured shopping cart system for PyroCMS
 *
 * @author		Salvatore Bordonaro
 * @version		1.0.0.051
 * @website		http://www.inspiredgroup.com.au/
 * @system		PyroCMS 2.1.x
 *
 */

class Collections_m extends MY_Model
{

    public $_table = 'shop_collections';

	public	$_create_validation_rules = array(
			array(
				'field' => 'name',
				'label' => 'Name',
				'rules' => 'trim|required|max_length[100]'
			),
		);

	public	$_edit_validation_rules = array(
			array(
				'field' => 'name',
				'label' => 'Name',
				'rules' => 'trim|required|max_length[100]'
			),
	);


	public function __construct()
	{
		parent::__construct();

	}


	public function create($input)
	{

		$this->load->helper('shop_admin');

		$to_insert = array(
			'name' => strip_tags($input['name']),
		);

		$id = $this->insert($to_insert);

		return $id;

	}


	/**
	 *
	 * @return INT id of the updated row for success
	 * @access public
	 */
	public function edit($id, $input)
	{
		// Prepare
		$to_update = array(
			'name' => strip_tags($input['name']),
		);

		return $this->update($id, $to_update);
	}

	public function get_tree2( $parent_id = 0 , $return_array = array() , $prefix = '' )
	{

		// selecting the parents
		$children = $this->get_all();

		foreach($children as $child)
		{
			$return_array[$child->id] = $child->name;
		}

		return $return_array;

	}

	public function get_products_filter($i_array)
	{
		$collections = $this->get_all();

		foreach ($collections as $key => $value) 
		{
			$i_array["By Collection"]["shop_collections,{$value->id}|{$value->id}"] = $value->name;
		}

		return $i_array;
	}	

}