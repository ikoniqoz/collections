<?php if (!defined('BASEPATH'))  exit('No direct script access allowed');
/**
 * NitroCart	NitroCart.net - A full featured shopping cart system for PyroCMS
 *
 * @author		Salvatore Bordonaro
 * @version		2.2.0.2050
 * @website		http://nitrocart.net
 *           	http://www.inspiredgroup.com.au
 *
 * @system		PyroCMS 2.2.x
 *
 */
class Events_Shop_Collections
{

	public $mod_details = array(
			      'name'=> 'Collections', //Label of the module
			      'namespace'=>'shop_collections',
			      'product-tab'=> TRUE, //This is to tell the core that we want a tab
			      'prod_tab_order'=> 20, //This is to tell the core that we want a tab
			      'cart'=> FALSE,
			      'has_admin'=> TRUE,
				);



	public function __get($var)
	{
		if (isset(get_instance()->$var))
		{
			return get_instance()->$var;
		}
	}

	// Put code here for everywhere
	public function __construct()
	{
		//New events to replace all of the above -
		Events::register('SHOPEVT_AdminProductGet', array($this, 'shopevt_admin_product_get'));
		Events::register('SHOPEVT_AdminProductDelete', array($this, 'shopevt_admin_product_delete'));
		Events::register('SHOPEVT_AdminProductDuplicate', array($this, 'shopevt_admin_product_duplicate'));
		Events::register('SHOPEVT_AdminProductListGetFilters', array($this, 'shopevt_adminproduct_list_get_filters'));		
	}

	public function shopevt_adminproduct_list_get_filters($o)
	{
		$this->load->model('shop_collections/collections_m');
		$o->modules = $this->collections_m->get_products_filter( $o->modules );	
	}



	public function shopevt_admin_product_delete($deleted_product_id)
	{
		$this->load->model('shop_collections/collections_products_m');

		$this->collections_products_m->delete_by_product( $deleted_product_id );
	}

	public function shopevt_admin_product_duplicate($duplicateData = array())
	{
		$or_id  = $duplicateData['OriginalProduct'];
		$new_id = $duplicateData['NewProduct'];

		$this->load->model('shop_collections/collections_products_m');
		$this->collections_products_m->product_duplicated( $or_id ,$new_id );

	}


	/**
	 * This will be called when the admin product data has been requested.
	 * It will inform all other modules to fetch any data that may be associated
	 * The ID of the product is passed (always by ID and Never by SLUG)
	 */
	public function shopevt_admin_product_get($product)
	{
		// Send data back
		$this->load->model('shop_collections/collections_m');
		$this->load->model('shop_collections/collections_products_m');

		//get a dropdown lost of available categries
		$product->modules['shop_collections']['list'] = 	$this->collections_m->get_tree2( 0 );

		$results = $this->collections_products_m->get_by_product($product->id);

		$product->modules['shop_collections']['assigned'] = $this->collections_products_m->prepare_results_for_admin_tab($results);

		$product->module_tabs[] = (object) $this->mod_details;

	}

}
/* End of file events.php */