<?php

/**

*/

require 'file_uploader_intial_settings.php';

class FileUploader
{
	private $max_upload_file_size ;
	private $upload_path ;
	private $file ;
	private $file_field_name ;
	private $myfile ;
        
    private $rename_on_upload ;
	private $on_upload_rename_to;
	
	private $image_type ;
        
        private $upload_multiple_files ;
        private $rename_on_duplicate ;
        
        public  $upload_file_ext_types;
        public  $result ;

        function __construct($file_field_name=''){

		$this->file = $_FILES ;
		
		//the default field name of type file will be 'attachments', you could change it in constructor or later using set_property function
		$this->file_field_name = ( $file_field_name ? $file_field_name :'attachments' ) ;

		$this->myfile = $this->file[ $this->file_field_name ] ;                
		
        $this->upload_path = UPLOAD_FOLDER ; 
        $this->max_upload_file_size = MAX_FILE_UPLOAD_SIZE ;
		$this->allowed_ext = ALLOWED_FILE_TYPE ;
		$this->rename_on_duplicate = true ;               
		$this->rename_on_upload  = false ;               
		$this->image_type = array(
                                        "jpg"	=>	array('image/pjpeg','image/jpeg','image/jpg') ,

                                        "png"	=>	array('image/png','image/x-png'),

                                        "gif"   =>	array('image/gif')
                                        );
                //this is only for image upload for other upload assign appropriate file type arrays
                $this->upload_file_ext_types = $this->image_type ; 
                $this->result = array();
                
                $this->check_multiple_upload() ;                 
		}

	public function set_property($file_info){
	
		foreach($file_info as $key=>$value)
					$this->$key = $value ;
				
		$this->file = $_FILES ;
		$this->myfile = $this->file[ $this->file_field_name ] ; 
				
	}     
          
	public function upload(){                        
			
			$this->make_folder_on_need($this->upload_path);
			if($this->upload_multiple_files){
				//convert_file_if_multiple_upload must use after "$this->myfile = $this->file[ $this->file_field_name ]" 
				//thats because both conver functin and upload function uses the$this->myfile property
				$this->convert_file_if_multiple_upload($this->file);
				foreach( $this->file as $this->myfile)
					$this->upload_file() ;
			}else                  
				$this->upload_file();  
	
	return $this->result ;        
	}        
            
	public function get_uploaded_file_name(){
	
		if($this->rename_on_upload){
					static $i = 0 ;
					$rename = $this->on_upload_rename_to ;
					$name = is_array($rename) ?  $rename[$i++] : $rename ;                        
				}else{
					$name = $this->myfile['name'] ;
					$name = preg_replace('#\.(\w*)$#','',$name);
				}
		return $name ;
	}
        
	public function rename_pattern(){
		return '1';
	}
                
 //-----------------------------------------       
        
        private function check_multiple_upload(){
            
            if( is_array( $this->myfile['name'] ) )
                $this->upload_multiple_files = true ;
            else 
                $this->upload_multiple_files = false ;
        }
        
        private function convert_file_if_multiple_upload($file){
/**
 * (PHP 4, PHP 5)<br/>
 * make necessary convertion to $_FILES value here $this->file value if the upload is multiple file upload
 * @param Array $file <p>
 * @return void : but make convertion to a class property file
 * </p><p>
 * make necessary convertion to $_FILES value here $this->file value if the upload is multiple file upload
 * result : $this->file will be array(0=>array(name=>'',type=>'',...),
 *                                      1=>array(name=>'',type=>'',...),
 *                                      2=>array(name=>'',type=>'',...),
 *                                  .....)
 */
            if( $this->upload_multiple_files){
                    $store = array();
                         foreach($this->file [$this->file_field_name] as $key=>$value){
                            $i = 0 ;
                             foreach ($value as $file){
                                 $store[$i++][$key] = $file ; 
                             }
                        }
                    $this->file = $store ;
            }
        }
     
		private function get_uploaded_file_type(){
			
			$type = $this->myfile['type'] ;
					if($type){
						$new_img = array();
						foreach( $this->upload_file_ext_types as $key=>$value)
							$new_img[$key] = implode(',', $value);
							
						$match_ext_array = preg_grep('#'.$type.'#', $new_img);
						foreach ($match_ext_array as $ext=>$ext_info)            break;
					}
			return $ext ;
		}
		
		private function file_type_string_generator($comma_seperated_types){
			
			$type_array = explode(',',$comma_seperated_types);
			$types = $this->upload_file_ext_types ;
			$type_string = '' ;
			foreach( $type_array as $type )
				
				$type_string .= ( is_array( $types[ $type ] ) ) ? 
										implode(',' ,$types[ $type ]) 
										:
										','.$types[ $type ] ;
					return $type_string ;
		}
		
		private function file_type_validation( ){
			
			$uploaded_file_type = $this->myfile['type'] ;
			$allowed_file_type = $this->file_type_string_generator( $this->allowed_ext );
			$pattern =  '"'. strtolower($uploaded_file_type) .'"' ;
			$status =  preg_match( $pattern , $allowed_file_type  , $matches);
			if($status)
				return true ;
			else
				return false ;
		}
		
		private function upload_error(){
			
				$err = $this->file['error']; 
					if( empty($err) )
							return false;
					else
							return $err ;
		}

		private function upload_validation(){
					
			$err = $this-> upload_error() ;
			$max_file_size = $this->max_upload_file_size ;
					if( $err )
							$upload_error = $err ;
					else if( $this->myfile['size']  > $max_file_size )
							$upload_error = 'Uploaded file size '.$this->myfile['size'].' exceeded maximum limit '.$max_file_size ;
					else if( !$this->file_type_validation() )
							$upload_error = 'Uploaded file is not of the type `'.$this->allowed_ext.'`' ;

					return 	$upload_error ;
		}
			
		private function upload_file(){
					   
				$err = $this->upload_validation();
				if( $err ){
	print_r($this->myfile['name']);exit();				
					$this->result[ $this->myfile['name'] ] = array( 
																	'status'   => 0,
																	'err_msg'  => $err
																  );
				}else{
					
					$name = $this->get_uploaded_file_name() ;
					$ext = $this->get_uploaded_file_type() ;
					$path = $this->upload_path ;
													
					if($this->rename_on_duplicate)
						$file_with_path = $this->rename_uploading_file_if_exist($path,$name,$ext);
					else 
						$file_with_path = $path.$name.'.'.$ext ;
									
					$result =  move_uploaded_file( $this->myfile['tmp_name'] ,$file_with_path );
					if($result){    
						$new_name = explode('/', $file_with_path );
						$new_name = $new_name[ count($new_name) -1 ];
					}
					$this->result[ $this->myfile['name'] ] = array( 
																	   'status'     => $result,
																	   'file_name'  => $new_name
																  );
				}
		}
				
		private function rename_uploading_file_if_exist($file_path,$file_name,$ext){
		
			$file_with_path = $file_path.$file_name.'.'.$ext ;
			if( file_exists($file_with_path ) ){		
				$file_name = $file_name.($this->rename_pattern());
				return $this->rename_uploading_file_if_exist($file_path,$file_name,$ext);
			}else 
				return $file_with_path ;
		}
			
		private function make_folder_on_need($folder_path){
			if( !file_exists($folder_path) )
				mkdir ($folder_path);
		}
			

//-----------------------------------------        
}
?>