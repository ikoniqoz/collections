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
class Collections extends Admin_Controller
{

	protected $section = 'collections';

	private $data;

	public function __construct()
	{
		parent::__construct();
        Events::trigger('SHOPEVT_ShopAdminController');

		$this->lang->load('shop_collections');


		$this->data = new StdClass;

		// Load all the required classes
		$this->load->model('shop_collections/collections_m');
		$this->load->library('form_validation');

        $this->template
                    //->append_js('shop::admin/admin.js')
                    ->append_js('shop::admin/plugins/buttons.js')
                    //->append_js('shop::admin/util.js')
                    ->append_css('shop::admin/admin.css')
                    //->append_css('shop::admin/stags.css')
                    ->append_css('shop::admin/tables.css')
                    //->append_css('shop::admin/lists.css')
                    //->append_css('shop::admin/pagination.css')
                    ->append_css('shop::admin/deprecated.css')
                    ->append_css('shop::admin/buttons/buttons.css')
                    ->append_css('shop::admin/buttons/font-awesome.min.css');
	}

	/**
	 * List all items
	 */
	public function index()
	{
		//check if has access
		role_or_die('shop_collections', 'admin_collections');

		$this->data->collections = $this->collections_m->get_all();

		$this->template
				->title($this->module_details['name'])
				->build('admin/collections/list', $this->data);
	}


	/**
	 * Create a new Brand
	 */
	public function create()
	{

		//check if has access
		role_or_die('shop_collections', 'admin_collections');

		$this->data = (object) array();

		// Set validation rules
		$this->form_validation->set_rules($this->collections_m->_create_validation_rules);

		// if postback-validate
		if ($this->form_validation->run())
		{
			//Get all the POST
			$input = $this->input->post();

			//Create a new collection and retrieve the ID
			$id = $this->collections_m->create($input);

			//Session message
			$this->session->set_flashdata('success', lang('shop_collection:create_success'));

			if($input['btnAction']=='save_exit')
			{
				redirect('admin/shop_collections/collections/');
			}

			//Redirect
			redirect('admin/shop_collections/collections/edit/'.$id);

		}
		else
		{
			foreach ($this->collections_m->_create_validation_rules as $key => $value)
			{
				$this->data->{$value['field']} = '';
			}
		}


		// Build page
		$this->template
			->title($this->module_details['name'])
			->append_metadata($this->load->view('fragments/wysiwyg', $this->data, TRUE))
			->build('admin/collections/create', $this->data);
	}


	/**
	 *	We need to alter edit to stop allow changing product.
	 *	Product and collection can not change
	 */
	public function edit( $id = null)
	{

		//check if has access
		role_or_die('shop_collections', 'admin_collections');


		//check if we have an id and if is numeric
		if( ! $id || ! is_numeric($id) )
		{
			$this->session->set_flashdata('error', lang('shop_collection:invalid_id') );
			redirect('admin/shop_collections/collections');
		}

		// Get row
		$row = $this->collections_m->get($id);


		// Check if exist
		if (!$row)
		{
			$this->session->set_flashdata('error', lang('shop_collection:not_found'));
			redirect('admin/shop_collections/collections');
		}


		$this->data = (object) $row;
		$this->form_validation->set_rules($this->collections_m->_edit_validation_rules);

		// if postback-validate
		if ($this->form_validation->run())
		{
			$input = $this->input->post();
			$this->collections_m->edit($id,$input);

			//now that the basic data is saved lets assign image
			//$this->upload_image($id);

			Events::trigger('evt_collection_changed', $id );

			$this->session->set_flashdata('success',  lang('shop_collection:update_success'));

			if($input['btnAction']=='save_exit')
			{
				redirect('admin/shop_collections/collections/');
			}

			redirect("admin/shop_collections/collections/edit/{$id}");
		}

		// Build page
		$this->template
			->enable_parser(TRUE)
			->title($this->module_details['name'])
			->append_metadata($this->load->view('fragments/wysiwyg', $this->data, TRUE))
			->build('admin/collections/edit', $this->data);
	}



	/**
	 * Simple delete, will need to work on validation and return messages
	 * @param unknown_type $id
	 */
	public function delete($id = null, $ret_cat = 0)
	{
		if($input = $this->input->post())
		{
			if(isset($input['btnAction']))
			{
				$this->_deleteMany();
			}
		}

		//check if has access
		role_or_die('shop_collections', 'admin_collections');

		//check if we have an id and if is numeric
		if( ! $id || ! is_numeric($id) )
		{
			$this->session->set_flashdata('error', lang('shop_collection:invalid_id') );
			redirect('admin/shop_collections/collections');
		}

		if($this->collections_m->delete($id))
		{
			Events::trigger('evt_collection_deleted', $id );

			if($this->input->is_ajax_request())
			{
				echo (json_encode(
					array(
						'status'=>JSONStatus::Success,
						)
					)
				);
				exit;
			}

		}

		if($ret_cat>0)
			redirect('admin/shop_collections/collections/edit/'.$ret_cat);
		else
			redirect('admin/shop_collections/collections');
	}

	private function _deleteMany()
	{

		$input = $this->input->post();


		if(isset($input['action_to']))
		{
			foreach( $input['action_to'] as $key => $value )
			{
				$this->collections_m->delete( $value );
			}
		}

		redirect('admin/shop_collections/collections');
	}


	public function link($product_id,$collection_id)
	{
		//allow access

		//take params and create, if fail, thats ok shouldmean its already done
		$this->load->model('shop_collections/collections_products_m');

		$link_id = $this->collections_products_m->insert(array('product_id'=>$product_id,'collection_id'=>$collection_id));

		$return_array = array();
		$return_array['status'] = JSONStatus::Success;
		$return_array['is_linked'] = TRUE;
		$return_array['link_id'] = $link_id;
		$return_array['product_id'] = $product_id;
		$return_array['collection_id'] = $collection_id;

		echo json_encode($return_array);exit;
	}


	public function unlink($product_id,$collection_id, $link_id)
	{
		//allow access

		$this->load->model('shop_collections/collections_products_m');

		$this->collections_products_m->delete($link_id);

		$return_array = array();
		$return_array['status'] = JSONStatus::Success;
		$return_array['is_linked'] = FALSE;
		$return_array['link_id'] = '';
		$return_array['product_id'] = $product_id;
		$return_array['collection_id'] = $collection_id;

		echo json_encode($return_array);exit;
	}


	/**
	 * products/clear = remove all products in a collection
	 * @param  string  $actionto      [description]
	 * @param  integer $collection_id [description]
	 * @return [type]                 [description]
	 */
	public function clear( $actionto = 'products', $collection_id = -1 )
	{

		switch($actionto)
		{
			case 'products':
				$this->load->model('shop_collections/collections_products_m');
				$count = $this->collections_products_m->clear_products_by($collection_id);
				echo "{$count} products removed from this collection.";die;
				break;
			default:
				break;
		}

		die;
	}

	public function add( $actionto = 'all', $collection_id = -1 )
	{

		switch($actionto)
		{
			case 'all':
				$this->load->model('shop_collections/collections_products_m');
				$count = $this->collections_products_m->add_all_to($collection_id);
				echo "{$count} products added to this collection.";die;
				break;
			default:
				break;
		}

		die;
	}


	public function products($collection_id)
	{
		//check if has access
		role_or_die('shop_collections', 'admin_collections');


		$this->data->collections = $this->collections_m->get_all();


		$this->load->model('shop_collections/collections_products_m');
		$collection = $this->collections_m->get($collection_id);

		if(!$collection)
		{
			$this->session->set_flashdata('error','No valid collection was selected.');
			redirect('admin/shop_collections/collections');
		}
		//now we have a collection, lets get all our products

		$this->data->products = $this->collections_products_m->geAllByCollection($collection->id);


		// Build the view with shop/views/admin/products.php
		$this->template->title($this->module_details['name'])
				->enable_parser(TRUE)
				->set('collection',$collection)
				//->append_js('admin/filter.js')
				//->append_js('module::admin/products.js')
				->build('admin/collections/list', $this->data);
	}
}