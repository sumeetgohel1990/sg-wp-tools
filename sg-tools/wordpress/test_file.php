<?php
/*******************************************************
// Make Array which will be consist of all the file names containng CSS and JS 
********************************************************/
include 'includes/simple_html_dom.php';
include 'includes/php-html-css-js-minifier.php';

$target_path = "uploads/radhika_index.html";
$target_path = "uploads/index.html";

$html = file_get_html($target_path);

global $get_style_and_scripts;

$get_style_and_scripts = array();

if(!empty($html) && null!==$html && $html!==''){
	/// /////////////////////
	///  GET ALL DATA FROM <HEAD> TAG 
	/// /////////////////////
	// 1.  Get all css extension files included http and https urls
	foreach ($html->find('head') as $head_element) {
		/*===================================
		=            STYLE SHEET            =
		===================================*/
		foreach ($head_element->find('link[href$=css]') as $element) {
			if(!empty($element)){
				$get_style_and_scripts['head']['css'][] = $element->href;
			}	// empty element if
		} // $element foreach for href css

		/*=====  End of STYLE SHEET  ======*/

		/*===================================
		=            JavaScript            =
		===================================*/
		foreach ($head_element->find('script[src$=js]') as $element) {

			if(!empty($element)){

				$get_style_and_scripts['head']['js'][] = $element->src;

			}	// empty element if
			
		} // $element foreach for href css

		//== incline js ==/
		foreach ($head_element->find('script') as $element) {

			if(!empty($element) && !empty($element->innertext)){

				$get_style_and_scripts['head']['js_inline'][] = $element->innertext;

			}	// empty element if
			
		} // $element foreach 

		/*=====  End of JavaScript  ======*/

	} // head tag foreach

	// 2.  Get all css extension files included http and https urls

	//css extions
	foreach ($html->find('link[href$=css]') as $element) {

		if(!empty($element)){

			$get_style_and_scripts['css'][] = $element->href;

		}	// empty element if
		
	} // $element foreach for href css

	//== incline css ==/

	//css extions
	foreach ($html->find('style') as $element) {

		if(!empty($element)){

			$get_style_and_scripts['css_inline'][] = $element->innertext;

		}	// empty element if
		
	} // $element foreach for href css

	//js extions
	foreach ($html->find('script[src$=js]') as $element) {

		if(!empty($element)){

			$get_style_and_scripts['js'][] = $element->src;

		}	// empty element if
		
	} // $element foreach for href css

	//== incline js ==/
	foreach ($html->find('script') as $element) {

		if(!empty($element) && !empty($element->innertext)){

			$get_style_and_scripts['js_inline'][] = $element->innertext;

		}	// empty element if
		
	} // $element foreach 

	echo "<pre>";
	print_r($get_style_and_scripts);
	echo "</pre>";

} else {// empty html if
	echo 'No valid HTML file found';
}

function generate_funtion_set($get_key,$get_all_elements){

	global $get_style_and_scripts;



	if($get_key!=='' && $get_all_elements!==''){

		$get_key_element = '';
		$type = ($get_key === 'css')? 'style':'script';
		$inline_type = ($get_key === 'css_inline')? 'style':'script';

		switch ($get_key) {
			case (($get_key === 'css' || $get_key === 'js' ) && is_array($get_all_elements) && null!==$get_all_elements):
				// code...
				foreach ($get_all_elements as $single_element_collection) {


					if(strpos(trim($single_element_collection),'http://') === false  &&
						strpos(trim($single_element_collection),'https://') === false 
				    ) {
								
						$url_to_append = "get_template_directory_uri() . '/". $single_element_collection."'";

					} else {

						$url_to_append = "'". $single_element_collection."'";
					}

					 $link_array = explode('/',$single_element_collection);
					 $file_handle = end($link_array);
					 $file_handle = preg_replace("![^a-z0-9]+!i", "-", $file_handle);
					 $add_in_footer = '';

					 if(!in_array($single_element_collection, $get_style_and_scripts['head'][$get_key]))
					 {
							$add_in_footer = ", true";
					 }



					 $get_key_element.= "wp_enqueue_".$type."('".$file_handle."',". $url_to_append.", array() , null ".$add_in_footer.");"."\n\n";

				}
				return $get_key_element;
				break;

			/*----------  inline css and js   ----------*/
			
			case (($get_key === 'css_inline' || $get_key === 'js_inline' ) && is_array($get_all_elements) && null!==$get_all_elements):
				// code...
				$counter = 1;
				foreach ($get_all_elements as $single_element_collection) {

					$add_in_footer = '';

					 if($get_key === 'js_inline' && !in_array($single_element_collection, $get_style_and_scripts['head']['js_inline']))
					 {
								$add_in_footer = ", true";
					 }

					 //Convert Double quote to single quote
					 $single_element_collection = str_replace('"', "'", trim(preg_replace('/[\s\t\n\r\s]+/', ' ', $single_element_collection)));

					 if($inline_type === 'style'){
					 	$single_element_collection = fn_minify_css($single_element_collection);
					 } else if($inline_type === 'script'){
					 	//$single_element_collection = fn_minify_js($single_element_collection);
					 }


					 $get_key_element.='// NOTICE :- Please map your inline '.$inline_type.' properly to make it working. ( Add first parameter $handle which must be depended css or js included in this function and last parameter [before/after] . '."\n";

					 $get_key_element.= "wp_add_inline_".$inline_type."('jquery-migrate'".","."\"".$single_element_collection."\"".$add_in_footer.");"."\n\n";

					
					$counter++;
				}
				return $get_key_element;
				break;		

			default:
				// code...
				break;
		}

	} else {
		return null;
	}

}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Sumeet Tool - WP Magic Tool</title>
	<script type="text/javascript" src="includes/scripts/shCore.js"></script>
	<script type="text/javascript" src="includes/scripts/shBrushJScript.js"></script>
	<link type="text/css" rel="stylesheet" href="includes/styles/shCoreDefault.css"/>
	<script type="text/javascript">SyntaxHighlighter.all();</script>
</head>

<body style="background: white; font-family: Helvetica">

<h1>WordPress Magic Tool - SG </h1>

<h4>Add CSS and JS function to - Function.php </h4>

<pre class="brush: js;">
**
 * Enqueue scripts and styles using SG Magic Tool.
 *
 *  */
function sg_magic_tool_scripts() {
	// List of Stylesheet to be added - 
	<?php
	
	/*================================================================================
	=            Make WP ENQUE SCRIPT FUNCTION FOR FUNCTION.PHP TO BE ADD            =
	================================================================================*/
	$get_style_and_scripts = array_filter($get_style_and_scripts);

	if(is_array($get_style_and_scripts) && null!== $get_style_and_scripts) {	

		foreach ($get_style_and_scripts as $key => $single_value) {

			if(isset($get_style_and_scripts[$key]) && !empty($get_style_and_scripts[$key]) && null!==$get_style_and_scripts[$key] && is_array($get_style_and_scripts[$key])) {
				$get_generated_function_set = generate_funtion_set($key,$single_value);
				if($get_generated_function_set!=='' & null!==$get_generated_function_set){
					echo $get_generated_function_set;
				}
			}

		} //end foreach get_style_and_scripts

	} // end of if $get_style_and_scripts empty

	/*=====  End of Make WP ENQUE SCRIPT FUNCTION FOR FUNCTION.PHP TO BE ADD  ======*/

	?>

}
add_action( 'wp_enqueue_scripts', 'sg_magic_tool_scripts' );

</pre>

</html>