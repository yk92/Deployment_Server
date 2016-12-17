<?php

require __DIR__ . '/../config.php';
require __DIR__ .'/dbConn.class.php';

$doDeploy = function ($arr) {

  //scp the bundle over to Production
  //send Production a message saying Bundle was copied
  //receive message from Production and send message to Dev

  /* deployRequest sent over name, version, filename, and location
   *
   * Need to use filename and location to scp push to "location"'s server
   *
   * After scp need to Produce a message and send it to Production that a bundle has been copied
   * How to know which Production server consumes the message?
   * Can an rpcServer from two different places consume the same message?
   */
  $data = unserialize($arr);
  echo "Deploying " . $data['name'] . " component to " . $data['location'] . "...\n";
  exec('scp -P 11222 ~/Bundles/' . $data['filename'] . ' ' . $data['location'] . ':/home/yuval/Bundles/');
  
  $client = new Thumper\RpcClient($registry->getConnection());
  $client->initClient();
  $client->addRequest(serialize($data), 'doInstall', 'doInstall');

  $replies = $client->getReplies(); 
  $rtnMsg = unserialize($replies["doInstall"];

  return $rtnMsg;
};

$server = new Thumper\RpcServer($registry->getConnection());
$server->initServer('doDeploy');
$server->setCallback($doDeploy);
$server->start();
