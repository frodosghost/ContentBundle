# ContentBundle
ContentBundle is a simple Console addition for editable content within Symfony2.

## Installation
If you are using Symfony 2.1 you can install by adding the dependencies into the `composer.json` file.

    "require": {
        ...
        "agb/content-bundle": "dev-master"        
    },
    "repositories": [
       {
           "type": "package",
           "package": {
               "version": "dev-master",
               "name": "agb/content-bundle",
               "source": {
                   "url": "git@bitbucket.org:frodosghost/agbcontentbundle.git",
                   "type": "git",
                   "reference": "master"
               },
               "dist": {
                   "url": "https://bitbucket.org/frodosghost/agbcontentbundle/get/master.zip",
                   "type": "zip"
               }
           }
       }
    ]
