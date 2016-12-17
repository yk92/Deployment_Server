<?php

	require __DIR__ . '/../config.php';

	$request = array();
	$request['name'] = $argv[1];

	$client = new Thumper\RpcClient($registry->getConnection());
	$client->initClient();
	$client->addRequest(serialize($request), 'doReturnVersions', 'doReturnVersions');

	//consume latest version
  $replies = $client->getReplies();
  echo "Requesting Version Listing for " . $argv[1] . " component...\n";  
  $data = unserialize($replies["doReturnVersions"]);

  if ( $data['message'] ) 
    echo $data['message'];
  // ***need to format the versions table nicer*** 
  else { 
    echo "Component   |    Version   |   Latest\n";
    echo "-------------------------------------\n";
    foreach($data as $elem){
      echo $elem['name'] . "           " . $elem['version'] . "             ";
      if ($elem['Latest']) echo "Yes\n";
      else echo "No\n";
    }

    echo "Please choose which version to deploy: \n";

    $handle = fopen("php://stdin", "r");
    $version = fgets($handle);
    fclose($handle);

    $request['version'] = $version;
    $request['filename'] = $argv[1] . '_Version' . $version . ' _Bundle.tar.gz';
    $request['location'] = $argv[2];
    //Build msg to deployment with version to deploy
	  $client = new Thumper\RpcClient($registry->getConnection());
	  $client->initClient();
	  $client->addRequest(serialize($request), 'doDeploy', 'doDeploy');

	  //consume latest version
    $replies = $client->getReplies();
    echo "Requesting deployment of " . $argv[1] . " version " . $version . "...\n";
    $data = unserialize($replies['doDeploy']);

    if ($data['message']){
      echo "Deployment was successful...\n";
      echo $data['message'];
    }
    else
      echo "Deployment failed...\n";
  }
