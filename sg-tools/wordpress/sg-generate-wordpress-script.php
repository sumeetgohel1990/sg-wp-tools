<?php
/**
Author of code is : Sumeet Gohel
Description 	  : To get ready script for wordpress
*/

?>

<form action="form-action.php" method="post" enctype="multipart/form-data">


<input required="required"  type="radio" name="get_script" value="get_header_footer_script"> Get Header and Footer Script 

<br/>

<label>Upload your Index File :</label>
<input type="file" name="get_index_html"  id="get_index_html" value="" >
<br/>

<input type="submit" name="generate_script" id="generate_script" value="Generate Script">
</form>




