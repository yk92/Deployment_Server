<?php

	require __DIR__ . '/../config.php';

	//make thumper request to database to get latest version
	$requestForVersion = array();
	$requestForVersion['name'] = $argv[1];

	$client = new Thumper\RpcClient($registry->getConnection());
	$client->initClient();
	$client->addRequest(serialize($requestForVersion), 'doVersion', 'doVersion');

	//consume latest version
  $replies = $client->getReplies();
  echo "Requesting current version from Deployment server...\n";
	$data = unserialize($replies["doVersion"]);

	//handle latest version - create new version number
  $currentVersionNumber = $data['version']+ 1;
  echo "Creating a new bundle version...\n";

  //create tmp folder to place files in
  exec('mkdir tmp');
	
	//read config.ini to get files to place in folder
  $str = file_get_contents('config.json');
  $json = json_decode($str, true);
  foreach ($json[$argv[1]] as $elem){
    exec('cp ' . $elem . ' ~/bundleMgmt/tmp/');
  }

	//copy config into folder
	exec('cp config.json ~/bundleMgmt/tmp/');

  //tarzip tmp folder
  echo "Bundling all components of " . $argv[1] . "...\n";
  $filename = $argv[1] . '_Version' . $currentVersionNumber . '_Bundle.tar.gz';
	exec('tar -czvf ' . $filename. ' ~/bundleMgmt/tmp/');
	//request to deployment to pull tarzip folder
	$deployRequest = array();
  $deployRequest['name'] = $argv[1];
  $deployRequest['version'] = $currentVersionNumber;
	$deployRequest['filename'] = $filename;
	//sending location of tar to deployment
	$deployRequest['location'] = __DIR__ . '/' . $argv[1] . 'Bundle.tar.gz';

	//now, actually send
	$deployClient = new Thumper\RpcClient($registry->getConnection());
	$client->initClient();
	$client->addRequest(serialize($deployRequest), 'doBundle', 'doBundle');

	//consume success message
	$replies = $client->getReplies();
	$msg = $unserialize($replies['doBundle']);
	echo $msg['success'];
