<?php 

namespace WP_Smart_Image_Resize;

class File_Helper{


  public static function append_prefix($file, $size){

    if( ! is_array($size)){
      $size = wp_sir_get_size_dimensions($size);
  }

  $file_info = pathinfo($file);

    return sprintf(
        '%s-%dx%d.%s',
        $file_info['filename'],
        $size[ 'width' ],
        $size[ 'height' ],
        $file_info['extension']
    );
  }

  
}