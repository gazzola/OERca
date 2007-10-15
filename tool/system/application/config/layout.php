<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
|  Theme Settings
| -------------------------------------------------------------------
|  views_folder     = path to the default theme view files
|  views_commons = path to common elements folder inside view/theme
|  views_content = path to content elements folder inside view/theme
|  assets_folder = path to the assets folder 
|  assets_design = path to the default theme assets
|  assets_shared = path to a shared assets folder
|  assets_styles = path to css folder inside assets
|  assets_images = path to images folder inside assets
|  assets_script = path to javascript folder inside assets
| -------------------------------------------------------------------
*/
$config['views_folder']  = "default";
$config['views_commons'] = "common";
$config['views_content'] = "content";
$config['assets_folder'] = "assets";
$config['assets_design'] = "default";
$config['assets_shared'] = "shared";
$config['assets_styles'] = "css";
$config['assets_images'] = "images";
$config['assets_script'] = "script";

/*
| -------------------------------------------------------------------
|  Layout Elements
| -------------------------------------------------------------------
|  layout_model    = common elements model name
|  layout_elements = references all functions in this model so the 
|                    library can automatically call each one of them. 
|                    Don't forget to write them in 'layout_model' ;-)
|  					 prototype: array("function" => "parameter", ...);
|			  		 where function is the funcion name and parameter
|					 is a single value or an array of values to send 
|					 to that function. 
| -------------------------------------------------------------------
*/
$config['layout_model']    = "layout_model";
$config['layout_elements'] = array(
#								"menu"	 	=> "",
#								"copyright" => ""
								);

/*
| -------------------------------------------------------------------
|  Application Properties
| -------------------------------------------------------------------
|  Here you can come up with any setting you find necessary. Bellow
|  we can see some of the usual suspects for a website.
|
|  Note: in order to work all properties must have the 'app_' prefix.
| -------------------------------------------------------------------
*/
$config['app_title']	   = "OCW Tool &ndash; ";
$config['app_keywords']    = "OCW Tool, opencourseware, oer";
$config['app_description'] = "OCW Tool to support dScribe model for OER content publishing";
$config['app_copyright']   = "(c) 2007 University of Michigan All Rights Reserved.";
$config['app_views_path']  = "default/content";
$config['app_views_abspath']  = $_SERVER['DOCUMENT_ROOT']."/ocw_tool/system/application/views/default/content";
$config['app_common_path']  = "default/common";
$config['app_css']  = site_url().$config['assets_folder'].'/'.$config['assets_design'].'/'.$config['assets_styles'];
$config['app_js']  = site_url().$config['assets_folder'].'/'.$config['assets_design'].'/'.$config['assets_script'];
$config['app_img']  = site_url().$config['assets_folder'].'/'.$config['assets_design'].'/'.$config['assets_images'];
$config['app_shared_img']  = site_url().$config['assets_folder'].'/'.$config['assets_shared'].'/'.$config['assets_images'];
?>
