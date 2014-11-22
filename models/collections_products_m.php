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
class Collections_products_m extends MY_Model
{

	public $_table = 'shop_collections_products';

	public function __construct()
	{
		parent::__construct();
	}

	public function get_by_product($product_id)
	{
		return $this->where('product_id',$product_id)->get_all();
	}
	public function geAllByCollection($collection_id)
	{
		//return $this->where('collection_id',$collection_id)->get_all();

		return $this
			->select('shop_collections_products.product_id,shop_collections_products.collection_id,shop_products.*')
			->where('collection_id',$collection_id)
			->join('shop_products','shop_products.id = shop_collections_products.product_id','left')
			->get_all();

	}

	public function prepare_results_for_admin_tab($results)
	{

		$return_val = array();

		foreach($results as $result)
		{
			$return_val[$result->collection_id] = $result->id;
		}

		return $return_val;

	}

	//this function duplicates all the records for original product id
	//with a new product id and the same category assignments
	public function product_duplicated($original_product_id,$new_product_id)
	{
		//fetch all rows where prod id = $or_id
		$original_product_cats = $this->where('product_id',$original_product_id)->get_all();

		foreach($original_product_cats AS $linkage)
		{
			//create the input
			$to_insert = array(
					'product_id' => $new_product_id ,
					'collection_id' => $linkage->collection_id,
			);

			//Add record
			$this->insert($to_insert); //returns id

		}

		return TRUE;

	}

	public function delete_by_product( $deleted_product_id )
	{
		return TRUE;
		
		//since collections are not exposed to the outside, we dont need to delete this data
		//return $this->delete_by('product_id',$deleted_product_id);
	}



	public function clear_products_by($collection_id = 0)
	{
		$count = $this->count_by('collection_id',$collection_id);
		if( $this->delete_by('collection_id',$collection_id) )
		{
			return $count;
		}
		return 0;
	}

	public function add_all_to($collection_id = 0)
	{
		$this->load->model('shop/admin/products_admin_m');
		$products = $this->products_admin_m->get_all();
		$count = 0;
		foreach($products as $product)
		{

			$c = $this->db->where('product_id',$product->id)->where('collection_id',$collection_id)->from($this->_table)->count_all_results();

			if($c > 0)
			{
				continue;
			}

			if($this->insert(array('product_id'=>$product->id,'collection_id'=>$collection_id)))
			{
				$count++;
			}
		}

		return $count;
	}


}