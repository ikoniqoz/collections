<?php if (!defined('BASEPATH'))  exit('No direct script access allowed');

 # SHOP for PyroCMS
$route['admin/shop_collections/collection(/:any)']			   = 'admin/collections/index$1';
$route['admin/shop_collections/products/list(/:num)?']              = 'admin/products/index$1';