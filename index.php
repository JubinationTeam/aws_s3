<?php 

require 's3.php';

S3::setAuth('AKIAJ3BYZ3TUJCHUMOJQ','dXvqeXjVRNtwcVdF3b/XG/fH56HZzc6ythdrYwor');

//function putObject($input, $bucket, $uri, $acl = self::ACL_PRIVATE, $metaHeaders = array(), $requestHeaders = array(), $storageClass = self::STORAGE_CLASS_STANDARD, $serverSideEncryption = self::SSE_NONE)



$data_back = json_decode(file_get_contents('php://input'));
 
//downloading file
$url = $data_back->{"url"};
$name =$data_back->{"key"};
file_put_contents("$name", file_get_contents($url));

header('Content-Type: application/json');


//upload to s3
try{
	if(S3::putObject(
		S3::inputFile("$name", false)	,
		'uploads-usa-std',
		"$name",
		S3::ACL_PUBLIC_READ,
		array(),
		array(),
		S3::STORAGE_CLASS_RRS
		)){
		$s3_url="https://s3.amazonaws.com/uploads-usa-std/".$name;
		$res="success";
		$data->response=$res;
		$data->link = $s3_url;
		echo json_encode($data);
        unlink($name);
	}
	else{
		$res="error";
		$data->response=$res;
		echo json_encode($data);
        unlink($name);
	}
}
catch(Exception $e){}


