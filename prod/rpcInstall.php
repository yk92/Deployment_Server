<?php
//This rpc server sits on Production and handles the Installation of bundles

require __DIR__ . '/config.php';

$doInstall = function ($arr) {
  //Message received that bundle was copied over.
  //Take bundle from ~/Bundles/ unpack it and move everything where it has to go
  $data = unserialize($arr);
  exec('cd ~/Bundles/');
  exec('tar xvfz ' . $data['filename']);
  exec('cd tmp/');

  $str = file_get_contents('config.json');
  $json = json_decode($str, true);

  foreach($json[$data['name']] as $key => $value){
    exec('echo nugget | sudo -S cp ' . $value . ' ' . $key . $value);
  }

  $response = array();
  $response['message'] = "Successfuly installed bundle.";

  return serialize($response);
    
};

$server = new Thumper\RpcServer($registry->getConnection());
$server->initServer('doInstall');
$server->setCallback($doInstall);
$server->start();
