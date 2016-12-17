<?php

require __DIR__ . '/../config.php';
require __DIR__ .'/dbConn.class.php';

$doReturnVersions = function ($arr) {
  try {
    $data = unserialize($arr);
    $db = dbConn::getConnection();
    $sql = $db->prepare('SELECT * FROM Bundles WHERE name = :name');
    $sql->execute( array( ':name' => $data['name'] ) );
    $results = $sql->fetchAll(PDO::FETCH_ASSOC);
  } catch ( PDOException $e ){
    echo $e->getMessage();
  }
  if ($results) {
    //login successful
    $data = serialize($results);
    echo "All versions returned.\n";
		return $data;
  }
  else {
    //if no result that means bundle has never been bundled before
    //so, return 0 as version
    $msg = array();
    $msg['message'] = 'No versions currently exist on Deployment' . PHP_EOL;
    $data = serialize($msg);
    echo "No current version exists.\n";
    return $data;
  }
   
};

$server = new Thumper\RpcServer($registry->getConnection());
$server->initServer('doReturnVersions');
$server->setCallback($doReturnVersions);
$server->start();
