# ContentBundle
ContentBundle is a simple Console addition for editable content within Symfony2.

## Installation
If you are using Symfony 2.1 you can install by adding the dependencies into the `composer.json` file.

    "require": {
        ...
        "manhattan/content-bundle": "dev-master"        
    },
    "repositories": [
       {
           "type": "package",
           "package": {
               "version": "dev-master",
               "name": "manhattan/content-bundle",
               "source": {
                   "url": "ggit@bitbucket.org:frodosghost/contentbundle.git",
                   "type": "git",
                   "reference": "master"
               },
               "dist": {
                   "url": "https://bitbucket.org/frodosghost/contentbundle/get/master.zip",
                   "type": "zip"
               }
           }
       }
    ]
