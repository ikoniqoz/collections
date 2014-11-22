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
class Module_Shop_Collections extends Module
{

	/**
	 * New dev version uses YMD as the final decimal format.
	 * Only for dev builds
	 *
	 * @var string
	 */
	public $version = '2.2.1';

	public $mod_details = array(
			      'name'=> 'Collections', //Label of the module
			      'namespace'=>'shop_collections',
			      'product-tab'=> TRUE, //This is to tell the core that we want a tab
			      'prod_tab_order'=> 1, //This is to tell the core that we want a tab
			      'cart'=> FALSE,
			      'has_admin'=> TRUE,
				);


	//List of tables used
	protected $module_tables = array(

			'shop_collections' => array(
				'id' 			=> array('type' => 'INT', 'constraint' => '11', 'unsigned' => TRUE, 'auto_increment' => TRUE, 'primary' => TRUE),
				'name' 			=> array('type' => 'VARCHAR', 'constraint' => '100'),
				'deleted' 		=> array('type' => 'DATETIME', 'null' => TRUE, 'default' => NULL),

			),
			'shop_collections_products' 	=> array(
				'id' 					=> array('type' => 'INT', 'constraint' => '11', 'unsigned' => TRUE, 'auto_increment' => TRUE, 'primary' => TRUE),
				'product_id' 			=> array('type' => 'INT', 'constraint' => '11', 'unsigned' => TRUE, 'default' => 0),
				'collection_id' 			=> array('type' => 'INT', 'constraint' => '11', 'unsigned' => TRUE, 'default' => 0),
				//no need for delete field, we never delete this row, even if prod deleted, we want all the data
			),
	);




	public function __construct()
	{
        $this->load->library('shop/nitrocore_library');     
		$this->ci = get_instance();
	}


	/**
	 * info()
	 * @description: Creates 2 arrays to diplay for the module naviagtion
	 *			   One array is returned based on the user selection in the settings
	 *
	 */
	public function info()
	{

		$info =  array(
			'name' => array(
				'en' => 'NitroCart Collections',
			),
			'description' => array(
				'en' => 'NitroCart <i>A full featured shopping cart system for PyroCMS!</i>',
			),
			'skip_xss' => FALSE,
			'frontend' => TRUE,
			'backend' => TRUE,
			'menu' => FALSE,
			'author' => 'Salvatore Bordonaro',
            'roles' => array(
            	'admin_manage',
	            'admin_collections',
            ),
			'sections' => array()
		);


        $this->load->library('shop/nitrocore_library');
        $info['sections'] = $this->nitrocore_library->get_common_sections_menu();

		$info['sections']['collections'] = array(
			'name' => 'shop_collections:admin:menu',
			'uri' => 'admin/shop_collections/collections',
             'shortcuts' => array( array('name' => 'Create', 'label'=>'Create', 'uri' => 'admin/shop_collections/collections/create','class' => 'add' ) ),
		);

		return $info;

	}


	/*
	 * The menu is handled by the main SHOP module
	 * Not needed here
	 */
    public function admin_menu(&$menu)
    {
    	 //$menu['lang:shop:admin:shop_admin']['Collections'] 		= 'admin/shop_collections/collections';
    	 //$menu['lang:shop:admin:shop']['Collected Products'] 		= 'admin/shop_collections/products';
    }



	public function install()
	{

        if ( CMS_VERSION < '2.2.0' ) {
            return FALSE;
        }
		if(!$this->isRequiredInstalled())
		{
			return FALSE;
		}
		// Install tables
		$tables_installed = $this->install_tables( $this->module_tables );

		// if the tables installed, now time to register this sub-module with
		if( $tables_installed  )
		{
			if($this->install_settings())
			{
				if($this->installMenuItems())
				{
					Events::trigger("SHOPEVT_RegisterModule", $this->mod_details);

					return TRUE;
				}
			}

		}

		return FALSE;

	}


	/*
	 */
	public function uninstall()
	{

		foreach($this->module_tables as $table_name => $table_data)
		{
			$this->dbforge->drop_table($table_name);
		}

		// Remove All settings for this module
		$this->db->delete('settings', array('module' => 'shop_collections'));


		//remove menu items
        $this->db->where('module','collections')->delete('shop_admin_menu');

		//Remove categories from the core module DB
		Events::trigger("SHOPEVT_DeRegisterModule", $this->mod_details);

		return TRUE;

	}

	private function installMenuItems()
	{
    	 //$menu['lang:shop:admin:shop_admin']['Collections'] 		= 'admin/shop_collections/collections';
    	 //$menu['lang:shop:admin:shop']['Collected Products'] 		= 'admin/shop_collections/products';

		//menu
        $data = array();
        $data[] = array(
            'label'         => 'Collections',
            'uri'           => 'admin/shop_collections/collections',
            'menu'          => 'lang:shop:admin:shop_admin',
            'module'        => 'collections',
            'order'         => 33,
            );
    
        $this->db->insert_batch('shop_admin_menu', $data);		

        return TRUE;
	}



	/*
	 */
	public function upgrade($old_version)
	{

		switch ($old_version)
		{
			case '1.0.1':
				break;
			default:
				break;

		}


		return TRUE;

	}


	public function help()
	{
		return "No documentation has been added for this module.<br />Contact the module developer for assistance.";
	}



	private function init_templates()
	{
		 return TRUE;
	}

	private function install_settings()
	{

		$settings = array();

		foreach ($settings as $slug => $setting)
		{
			//set the settings name
			$setting['slug'] = $slug;

			if (!$this->db->insert('settings', $setting))
			{
				return FALSE;
			}
		}

		return TRUE;

	}

	public function isRequiredInstalled()
	{

		$this->ci->load->model('module/module_m');
		$module_core = $this->ci->module_m->get_by('slug', 'shop' );

    	if( $module_core && $module_core->installed == TRUE)
    	{
    		$module = $this->ci->module_m->get_by('slug', 'shop' );
    		if( $module && $module->installed == TRUE)
    		{
				//we can now install this shop module
				return TRUE;
			}
    	}

    	return FALSE;
	}


}
/* End of file details.php */