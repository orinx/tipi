<?php
error_reporting( 0 );
//configs

//if you wanna to custom your url, you can change it like below
// Example http://rasengan.im/space/
$base =  "http://" . $_SERVER[ 'HTTP_HOST' ] . substr( $_SERVER[ 'PHP_SELF' ] , 0 , -8 );

// Maxium File Size (default 2MB)
$size_limit = 2097152;

//target folder address
$targetFolder = "file/";

//allow filetype, please use lowercase
$allowFileType = [ "jpg", "png", "gif", "psd", "orinx", "essencious"];

//keep hacker & cracker away ?
if( empty( $_FILES ) ){ die( "an apple a day keeps the doctor away" ); }


$index = 0;
echo "{";

foreach( $_FILES as $file ){

  echo '"file'.$index.'": {';

  //check image size
  if( $file[ 'size' ] > $size_limit ){

    echo '"status": "toolarge"';

  }else{

    //get file extensions
    $fnSp = split( '[.]', $file[ 'name' ] );
    $fileType = strtolower( $fnSp[ count( $fnSp ) -1 ] );

    $pass = false;

    foreach( $allowFileType as $aft ){

      if( $aft == $fileType ){

        $uid_name = uniqid();
        move_uploaded_file( $file[ "tmp_name" ], $targetFolder . $uid_name . "." . $aft );
        echo '"status":"success", "link":"' . $base . $targetFolder . $uid_name . '.' . $aft . '"';
        $pass = true;
        break;

      }

    }

    if( !$pass ){ echo '"status": "filetype_not_support"'; }

  }
  $index++;
  echo "},";
}
echo '"filelen": "' . $index . '"}';
?>
