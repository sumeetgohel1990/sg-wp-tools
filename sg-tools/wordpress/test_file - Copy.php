<?php
include 'simple_html_dom.php';
$target_path = "uploads/index.html";
$html = file_get_html($target_path);

$get_style_and_scripts = array();


				// Find all css files between head tag
				foreach($html->find('head') as $element) {

					// -------- Get collection of CSS  -----------//

					foreach ($element->find('link') as $single_head_link) {

						//Get the HTTP and Folder path href of stylesheets.

						if($single_head_link->type === 'text/css'){

							if(strpos(trim($single_head_link->href),'http://') === false  &&
								strpos(trim($single_head_link->href),'https://') === false
							    ) {
								$get_style_and_scripts['head']['css'][] = $single_head_link->href;	
							} else {
								$get_style_and_scripts['head']['css_urls'][] = $single_head_link->href;
							}
							
						}  
					}

					// Get all incine CSS between head
					foreach ($element->find('style') as $single_inline_style) {

						if(trim($single_inline_style->innertext)!=='')
						$get_style_and_scripts['head']['css_inline'][] = $single_inline_style->innertext;
						$get_full_page_inline_css[] = $single_inline_style->innertext;
					}

					//============= END OF COLLECTION OF CSS =============== /

					// -------- Get collection of JS  -----------//


					foreach ($element->find('script') as $single_head_link) {

						//Get the HTTP and Folder path href of stylesheets.

							if(trim($single_head_link->src)!==''):


							if(strpos(trim($single_head_link->src),'http://') === false  &&
								strpos(trim($single_head_link->src),'https://') === false 

							    ) {
								$get_style_and_scripts['head']['js'][] = $single_head_link->src;	
							} else {
								$get_style_and_scripts['head']['js_urls'][] = $single_head_link->src;
							}

							endif;
							
						  
					}

					// Get all incine CSS between head
					foreach ($element->find('script') as $single_inline_js) {

						if(strlen($single_inline_js->innertext) > 0 ){
						$get_style_and_scripts['head']['js_inline'][] = (string)$single_inline_js->innertext;	
						}
						
					}






				} // ================= HEADER CSS & JS DATA COLLTION END ===========================
				 
				// Find all css files rest of the file
				foreach ($html->find('link') as $element_all) {
					if($element_all->type === 'text/css'){
							$get_full_page_css[] = $element_all->href;
					}
				}

				//Get rest of css files - footer 
				$get_style_and_scripts['footer']['css']=  array_diff($get_full_page_css,$get_style_and_scripts['head']['css'],$get_style_and_scripts['head']['css_urls']);

				/////////////////////////////////////////////////////////////
				// -------- Get collection of JS from all page  -----------//
				/////////////////////////////////////////////////////////////


					foreach ($html->find('script') as $single_head_link) {

						//Get the HTTP and Folder path href of stylesheets.

							if(trim($single_head_link->src)!==''):


							if(strpos(trim($single_head_link->src),'http://') === false  &&
								strpos(trim($single_head_link->src),'https://') === false 

							    ) {
								//$get_style_and_scripts['footer']['js'][] = $single_head_link->src;	
								$get_full_page_js[]= $single_head_link->src;

							} else {
								//$get_style_and_scripts['footer']['js_urls'][] = $single_head_link->src;
								$get_full_page_js_urls[]= $single_head_link->src;
								$get_full_page_js[]= $single_head_link->src;

							}

							endif;
							
						  
					}

					// Get all incine CSS between head
					foreach ($html->find('script') as $single_inline_js) {

						if(trim($single_inline_js->innertext)!=='')
						//$get_style_and_scripts['footer']['js_inline'][] = $single_inline_js->innertext;
						$get_full_page_js_inline[] = $single_inline_js->innertext;
					}

					//Get rest of css files - footer 
				$get_style_and_scripts['footer']['js']=  array_diff($get_full_page_js,$get_style_and_scripts['head']['js'],$get_style_and_scripts['head']['js_urls']);

				$get_style_and_scripts['footer']['js_urls']=  array_diff($get_full_page_js_urls,$get_style_and_scripts['head']['js'],$get_style_and_scripts['head']['js_urls']);

				$get_style_and_scripts['footer']['js_inline']=  array_diff($get_full_page_js_inline,$get_style_and_scripts['head']['js_inline']);

				$get_style_and_scripts['all_css'] = $get_full_page_css;
				$get_style_and_scripts['all_js'] = $get_full_page_js;
				$get_style_and_scripts['all_inline_css'] = $get_full_page_inline_css;
				$get_style_and_scripts['all_inline_js'] = $get_full_page_js_inline;

				




				 
				
 

			 
echo "<pre>";			
						print_r(array_filter($get_style_and_scripts));
						echo "</pre>";	






		/*		
		echo "<pre>";			
		echo "header css:";
		print_r($get_style_and_scripts['css']['head']);
		echo "all css:";
		print_r($get_style_and_scripts['css']['all']);
		echo "rest of  css:";
		print_r($get_style_and_scripts['css']['footer']);
		echo "</pre>";*/

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Hello SyntaxHighlighter</title>
	<script type="text/javascript" src="includes/scripts/shCore.js"></script>
	<script type="text/javascript" src="includes/scripts/shBrushJScript.js"></script>
	<link type="text/css" rel="stylesheet" href="includes/styles/shCoreDefault.css"/>
	<script type="text/javascript">SyntaxHighlighter.all();</script>
</head>

<body style="background: white; font-family: Helvetica">

<h1>Hello SyntaxHighlighter</h1>
<pre class="brush: js;">
**
 * Enqueue scripts and styles using SG Magic Tool.
 *
 *  */
function sg_magic_tool_scripts() {
	// List of Stylesheet to be added - 
	<?php 
	if(is_array($get_style_and_scripts) && null!== $get_style_and_scripts) {

				foreach ($get_style_and_scripts['all_css'] as $key => $single_element_collection) {

						if(strpos(trim($single_element_collection),'http://') === false  &&
								strpos(trim($single_element_collection),'https://') === false 

							    ) {
								
							$url_to_append = "get_template_directory_uri() . '/". $single_element_collection;
							} else {
								$url_to_append = "'". $single_element_collection;
							}

							 $link_array = explode('/',$single_element_collection);
    						 $file_handle = end($link_array);
    						 $file_handle = preg_replace("![^a-z0-9]+!i", "-", $file_handle);
    						 $add_in_footer = '';

    						 if(in_array($single_element_collection, $get_style_and_scripts['footer']['css']))
    						 {
									$add_in_footer = ", true";
							 }


						 //$url_to_append = "get_template_directory_uri() . '". $single_element_collection;

						?>
	wp_enqueue_style( '<?php echo $file_handle;?>', <?php echo $url_to_append;?>', array(), null  <?php echo $add_in_footer;?> );

							<?php
							
				} //foreach - $get_style_and_scripts



				// FOR INLINE CSS

					
					 

				$counter = 1;
				foreach ($get_style_and_scripts['all_inline_css'] as $key => $single_element_collection) {
 
							 $link_array = explode('/',$single_element_collection);
    						 $file_handle = end($link_array);
    						 $file_handle = preg_replace("![^a-z0-9]+!i", "-", $file_handle);
    						 $add_in_footer = '';

    						 if(in_array($single_element_collection, $get_style_and_scripts['footer']['css']))
    						 {
									$add_in_footer = ",". true;
							 }


						 //$url_to_append = "get_template_directory_uri() . '". $single_element_collection;

						?>
						 wp_add_inline_style( 'custom-inline-style-<?php echo $counter;?>', <?php echo "'".$single_element_collection."'"; ?> );
	
							<?php
							$counter++;
				} //foreach - $get_style_and_scripts -FOR INLINE CSS



				/********************** for js ****************/

				 
				
				
				

				foreach ($get_style_and_scripts['all_js'] as $key => $single_element_collection) {



						if(strpos(trim($single_element_collection),'http://') === false  &&
								strpos(trim($single_element_collection),'https://') === false 

							    ) {
								
							$url_to_append = "get_template_directory_uri() . '/". $single_element_collection;
							} else {
								$url_to_append = "'". $single_element_collection;
							}

							 $link_array = explode('/',$single_element_collection);
    						 $file_handle = end($link_array);
    						 $file_handle = preg_replace("![^a-z0-9]+!i", "-", $file_handle);
    						 $add_in_footer = '';

    						 if(in_array($single_element_collection, $get_style_and_scripts['footer']['js']))
    						 {
									$add_in_footer = ", true";
							 }


						 //$url_to_append = "get_template_directory_uri() . '". $single_element_collection;

						?>
	wp_enqueue_script( '<?php echo $file_handle;?>', <?php echo $url_to_append;?>', array(), null  <?php echo $add_in_footer;?> );

							<?php
							
				} //foreach - $get_style_and_scripts

				

				// FOR INLINE JS

					
					 

				$counter = 1;
				foreach ($get_style_and_scripts['all_inline_js'] as $key => $single_element_collection) {
 
							 $link_array = explode('/',$single_element_collection);
    						 $file_handle = end($link_array);
    						 $file_handle = preg_replace("![^a-z0-9]+!i", "-", $file_handle);
    						 $add_in_footer = '';

    						 if(in_array($single_element_collection, $get_style_and_scripts['footer']['js_inline']))
    						 {
									$add_in_footer = ",true";
							 }


						 //$url_to_append = "get_template_directory_uri() . '". $single_element_collection;

						?>
						 wp_add_inline_script( 'custom-inline-style-<?php echo $counter;?>', <?php echo "'".$single_element_collection."'"; ?> <?php echo $add_in_footer;?>);
	
							<?php
							$counter++;
				} //foreach - $get_style_and_scripts -FOR INLINE js









	} // is array
	?>


	
}
add_action( 'wp_enqueue_scripts', 'sg_magic_tool_scripts' );

</pre>

</html>


