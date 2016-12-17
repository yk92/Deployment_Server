# Bundler / Deployment System

This project is a work-in-progress.. 
It is a Bundler / Deployment system that can communicate across multiple remote servers.

The purpose of this project is to create a system similar to NPM that Bundles, Manages, Installs packages consisting of project modules.

For example: The 'Login' Package would consist of all Controllers, Views, Models and other relevant files necessary for Login to work on it490 Project.

This Deployment system gathers all requirements, bundles them into a tar-gz and sends the bundle from dev server to production server where it unpacks the tar and installs required files correctly.

## Getting Started
```
git clone git@github.com:yk92/Deployment_Server.git Deployment

cd Deployment

composer install
```

This will get the repo cloned and all requirements installed on local disk

One caveat does apply - this Bundler is useless to the outside world without it490 Project.

### Prerequisites

AMQP Protocol Libraries - preferably RabbitMQ

IT490 Repo installed and configured

### Installing


## Running the tests

Tests currently being worked on

## Deployment

This Deployment system should theoretically sit on your dedicated Deployment Server as well as on Dev Env and Prod Env. 

The project has been divided into folders containing the requirements for each environment.

## Built With

* Thumper AMQP Libs
* RabbitMQ
* PHP
* JSON

## Versioning

This is currently version 1.0

## Authors

* ** Yuval Klein - Dev / Prod Files, rpcVersion, rpcReturnVersions, rpcDeploy
* ** Branden Robinson - Deployment

## License

This project is licensed under the MIT License

## Acknowledgments

* MunchiesNJ (god those burgers are good)
* Annabella's Kitchen patty melt
* Honey Mustard
* Prof. Chaos (DJ Kehoe) for making us do the stupid carousel
* Inspiration - On a good day

## To Do

* Integrate testing
* Make entire system more robust
* Decouple entire system from IT490 Project so it can be used for any project needing Bundle Mgmt.
* Port to Node.js
