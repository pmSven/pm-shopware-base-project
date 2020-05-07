## Base Shopware 5 Repository

This repository is meant to be a base project for all our Shopware 5 projects. If you want to create a new Shopware 5 project please fork from this repository and do the following steps:

- Change the entries in ./src/.env according to your project specifications.
- (Optional) Adjust mutagen.yml, esp. change all 'example-shop'
- Install shopware.
- Be sure that shopware didn't override the config.php values.
- Change the entries in ./src/scripts/.from-dev01-to-local.env according to your project specifications.
- Change the data in the _README.md file.
- Replace this README.md file by the _README.md file.

#### On Mac use mutagen for better performance
    
    brew install mutagen-io/mutagen/mutagen
    
Remove or comment the '# Remove or comment for mutagen' line in the docker-compose.yml
Start the project with `mutagen project start`