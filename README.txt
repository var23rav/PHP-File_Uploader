| -------------------------------------------------------------------
|  Author    : Vaisakh R
|  code name : var23
|  Email ID  : var23rav@gmail.com
|  Project   : PHP-File_uploader
| -------------------------------------------------------------------

Description
------------
PHP-File_uploader as its name says, is a PHP project and is in need of saving your 
time by making a wrapper over php file uploading process...
Below are the features of PHP-File_Uploader project.It is highly customizable in the meantime
can be used with minimal configuration.
To upload your file, you only need to do two things.
	* Create an object of the class FileUploader 
	* And call upload() function
    tada you are done .Check your pocket, File will be there..
    Isn't it easy! no need to worry about regular cliche programming..
	
Following are various features of this project.
	
 Featured with minimal configuration

1 )	By default class consider the name of input type file field is 'attachments'.
    you could also rename it through the constructor 
	otherwise leave it and use the name 'attachements' for your input field of file types.

2 ) By default it is an image uploader , :D thats what i need to do.. sorry my bad
    But you can make your own uploade by extending this class.
	
 Allow high customization in wide area	

3 )	You could also specify whether rename after file uploaded, to a new name ,
    by default file will uploaded with same name 
	otherwise you could specify the new name for file, which will stored with specified name.

4 ) You could also specify whether rename on file duplication ,
	By default file will be rename according to the renaming pattern .
        you can also overide the default renaming pattern by extending the class.
	If you don't need renaming file onupload you can set that which will overwrite existing file with same name.
	
5 ) This File_uploader is compatible with multiple upload.{ <input type='file' name='attachement' multiple/> }.
	If you try to upload file with same name repeatedly{which is not the usual case} in multiple file upload it uploaded you given,
		but will show the result of the last one only.
    for eg: if your try to upload 'winter.jpg' for all file field	in 'muliple upload' the result will show only for last winte.jpg upload

6 )	Result will be multi-dimensional array in a format
 
		Array
			(
				[file_name_before_upload] => Array
					(
						[status] => 0 or 1
						[file_name] => file_name_after_upload
					)

			)
			
	eg :
	  below is a multiple file uploading result
	  > 'water lilies.psd' uploading failed {status:0} and error was format is not specifie {err_msg}
	  > 'winter.jpg' uploded successfully {status:1} and uploaded with a new name 'winter111.jpg'{ file_name : Winter111.jpg} 
		Array
			(
				[Water lilies.psd] => Array
					(
						[status] => 0
						[err_msg] => Uploaded file is not of the type `gif,png,jpg`
					)

				[Winter.jpg] => Array
					(
						[status] => 1
						[file_name] => Winter111.jpg
					)

			)
			

	
-----------------------------------------------------------------------------------------------

I am intersted in your feedback, mail me for any queries and loved to hear any suggestion to improve it.	
