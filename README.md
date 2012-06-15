# ContentBundle
ContentBundle is a simple Console addition for editable content within Symfony2.

## Installation
If you are using Symfony 2.1 you can install by adding the dependencies into the `composer.json` file.

    "require": {
        ...
        "manhattan/contentbundle": "dev-master"        
    },
    "repositories": [
       {
           "type": "package",
           "package": {
               "version": "dev-master",
               "name": "manhattan/contentbundle",
               "source": {
                   "url": "git@bitbucket.org:frodosghost/contentbundle.git",
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
