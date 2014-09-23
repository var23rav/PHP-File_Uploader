<?php

require('../file_uploader.php');//importing FileUploader class

function show_arr($str){//used to format result
	echo '<pre>';
	print_r($str);
	echo '<pre>';
}

/*
Simplest configuration for uploading file .
This will be an image upload by default 
and it use all intial setting specified in the file_uploader_intial_settings.php file
*/
$uploader = new FileUploader();//instantiating the class, no field name is specified show assumes 'attachements' is input file filed name
$uploadresult = $uploader->upload();//uploading  files
show_arr($uploadresult);//show_arr($uploader->result) ; //show result of file uploading

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

//If you choose some name other 'attachements' to your input field type file for example as 'myfilename' { <input type='file' name='myfilename'/> }
//Use code as follows
/*
$uploader = new FileUploader();//specify the input file field name in the constructor of FileUploader
$uploader->upload();
show_arr($uploader->result) ;
*/
?>