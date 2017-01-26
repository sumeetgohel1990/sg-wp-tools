<?php
include 'simple_html_dom.php';

if(isset($_POST['generate_script'])  &&  $_POST['generate_script']  === 'Generate Script' ) {

	if(isset($_POST['get_script']) && $_POST['get_script'] === 'get_header_footer_script'){

		var_dump($_FILES);

		if(isset($_FILES['get_index_html'])) {

			$allowed =  array('html');
			$filename = $_FILES['get_index_html']['name'];
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			if(in_array($ext,$allowed) ) {
			    
			    // Create DOM from URL or file

			    $target_path = "uploads/";

			    if(false === is_dir($target_path)){
			    	mkdir($target_path,0755,true);
			    }

				$target_path = $target_path . basename( $_FILES['get_index_html']['name']); 


				$check_file= "/".$target_path;

				if(!file_exists($check_file)) {

					if(move_uploaded_file($_FILES['get_index_html']['tmp_name'], $target_path)) {
					    echo "The file ".  basename( $_FILES['get_index_html']['name']). 
					    " has been uploaded";
					} else{
					    echo "There was an error uploading the file, please try again!";
					}

				}

				$html = file_get_html($target_path);

				// Find all images 
				foreach($html->find('head') as $element) {

					foreach ($element->find('link') as $value) {

						echo $value->href."</br>";
						
					}
					
				       
				}
			} else {
				$get_string = implode(",", $allowed);
				echo 'Invalid File uploaded. Allowed extensions are as below '.$get_string;

			}
		}
	}


}

?>
