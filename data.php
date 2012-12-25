<?php
error_reporting(0);

//Configs

//if you wanna to custom your url, you can change it like below
// Example http://rasengan.im/space/
$base =  "http://".$_SERVER['SERVER_ADDR'].substr($_SERVER['PHP_SELF'],0,-8);

// Maxium File Size (default 1MB)
$size_limit = 1024*1024;

//keep hacker & cracker away ?
if(empty($_FILES)){ die("an apple a day keeps the doctor away"); }

//target folder address
$targetFolder = "./file/";

$index = 0;
echo "{";
foreach($_FILES as $file){
  echo '"file'.$index.'": {';
  if($file['size'] > $size_limit){
    //image is too large
    echo '"status": "toolarge"';
  }else{

    if (($file["type"] == "image/gif")
     || ($file["type"] == "image/jpeg")
     || ($file["type"] == "image/png")
     || ($file["type"] == "image/x-png")){
      if ($file["error"] > 0)
        echo "Error： ".$file["error"];
      else{
        if ($file["type"] == "image/gif")
          $type = "gif";
        else if ($file["type"] == "image/jpeg")
          $type = "jpg";
        else if ($file["type"] == "image/png" || $file["type"] == "image/x-png")
          $type = "png";

          $uid_name = uniqid();
          move_uploaded_file($file["tmp_name"], $targetFolder.$uid_name.".".$type);
          echo '"status":"success", "id":"'.$uid_name.'"';
        }
      }else{
      //mine type error
      echo '"status": "mineerr"';
    }
  }
  $index++;
  echo "},";
}
echo '"filelen": "'.$index.'"';
echo "}";
?>
