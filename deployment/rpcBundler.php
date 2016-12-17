<?php

require __DIR__ . '/../config.php';
require __DIR__ .'/dbConn.class.php';

$doBundle = function ($arr) {
  try {
	  $data = unserialize($arr);
    $db = dbConn::getConnection();
    $sql = $db->prepare('UPDATE Bundles SET Latest=FALSE WHERE Latest=TRUE AND name = :name');
		$sql->execute( array( ':name' => $data['name'] ) );
		$sql = $db->prepare('INSERT INTO Bundles VALUES (:name, :version, TRUE');
		$sql->execute( array( ':name' => $data['name'], ':version' => $data['version']);
  } catch ( PDOException $e ){
    echo $e->getMessage();
  }
	
  echo "Copying the latest version of " . $data['name'] . " to Deployment server...\n";  
  //do scp to get latest bundle and save to ~/Bundles/
  exec('scp -P 11222 yuval@10.0.2.2:' . $data['location'] . $data['filename'] . ' /home/yuval/Bundles/');
  echo "Successfully copied new bundle...\n";
  $msg = array();
  $msg['success'] = "Bundle operation completed successfully";
	$data = serialize($msg);
	return $data;
};

$server = new Thumper\RpcServer($registry->getConnection());
$server->initServer('doBundle');
$server->setCallback($doBundle);
$server->start();

