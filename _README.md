# Online Shop der DIHK-Bildungs-GmbH

![DIHK](./documentation/img/logo.png "DIHK") 

:man_student: ![PM_DIHK](https://img.shields.io/:PM-DIHK-004b89.svg "PM_DIHK") :woman_student:

[Live URL](https://www.dihk-bildung.shop/)

[Stage URL](https://stage.dihk-bildung.shop/)


## Installation

- start Docker (`docker-compose up [-d]`) in ./src/
- pull DB and Media (`./pull-from-stage.sh -p`) in ./src/scripts/
- put `dihk.local` in host file (`127.0.0.1 localhost dihk.local`) in /etc
- open [http://dihk.local/](http://dihk.local/) to see the shop frontend.
- open [http://dihk.local/backend](http://dihk.local/backend) to see the shop.
- open [http://localhost:8083/](http://localhost:8083/) to see phpmyadmin.

Finally it should look like this:

![alt text](./documentation/img/frontpage.png "Homepage")


## Live Deployment

- merge `development` into`master`
- create a release with the prefix `prod` as the name and an overview of the changes as the description.
- this will trigger a deployment to the [Live Server](https://www.dihk-bildung.shop/)


### Important slack channels
- #dihk
- #dihk-build
- #dihk-daily
- #dihk-logs
- #dihk-seo_sea


### Deployment

Always test your tickets on Chrome, Firefox, IE and also on mobile(320px) up to Desktop(1080p).
Whenever you merge your PR into development, follow these steps:

1. Check if the deployment was successfully using [github](https://github.com/) or [slack](https://pmdev-team.slack.com/home). Be sure that no test fails! If a test fails, correct that test before you start a new ticket.
2. Clear cache on stage and recompile the theme. Update/Install/Activate your plugin if needed.
3. Check if your task works on stage.

A quick way to create a Pull-Request is to use [hub](https://github.com/github/hub) with the command `hub pull-request -b development -o -e -r <list_of_people>`

### Tipps/Tricks and infos

If you work on a task that is not a one-hit-wonder keep infos about it in confluence. Please add this info by the time you merge your PR into development. So that we share our knowledge.

These infos can be added below this [entry point](https://pmsoftware.atlassian.net/wiki/spaces/DIHK/pages/359890945/Anleitungen).

### Code Style

Please let us use the same code style configuration. 

You can easily import the symfony config by [pulling the settings](https://www.jetbrains.com/help/phpstorm/sharing-your-ide-settings.html#settings-repository) from the provided [repo](https://github.com/pmSven/phpstorm_settings).

Please use type declaration and enforce the type checks using `declare(strict_types=1);`. In addition take the warnings of phpstorm seriously once you installed the following plugins:

### Mandatory Plugins for PHP-Storm

1. [PHP Annotations](https://plugins.jetbrains.com/plugin/7320-php-annotations)
2. [Php Inspections (EA Extended)](https://plugins.jetbrains.com/plugin/7622-php-inspections-ea-extended-)
3. [Shopware 3.3.0](https://plugins.jetbrains.com/plugin/7410-shopware/update/67762) Version 3.3.0!
4. [Symfony Support](https://plugins.jetbrains.com/plugin/7219-symfony-support)
