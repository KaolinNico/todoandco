# Contributing

This file describes how to contribute to ToDoList. When contributing to this repository, you can first discuss the change you wish to make via issue.

Please note that we have rules, please follow them in all your interactions with the project.

## Clone the project

First, you have to [clone](https://github.com/KaolinNico/todoandco.git) the project in your local machine.

## Install the project

1. run `composer install` command to install dependencies.
2. run `php bin/console doctrine:database:create` to create database
3. run `php bin/console doctrine:schema:update --force` to update database
4. run `php bin/console hautelook:fixtures:load` to create fixtures.

Before starting your changes, create a new branch. This project use [Gitflow](https://danielkummer.github.io/git-flow-cheatsheet/index.fr_FR.html)

## Do your changes

You can now do your changes to the code or add a new feature.  
After coding, please make PHPUnit tests to test your code, and run all the tests with `php bin/phpunit` command to be sure your changes work and do not create failing with our tests.

If it is ok, you can submit a pull request, and wait for it to be accepted by the staff.

## Some rules to respect

Using welcoming and inclusive language  
Being respectful of differing viewpoints and experiences  
Accepting constructive criticism


We respect some PSR rules, please respect PSR-1, PSR-12 and PSR-4. Please use  PHPCSFIXER / PHPCS / PHPCBF` to check it.  
This application is developped with Symfony framework 4.4, please check the [Best Practises](https://symfony.com/doc/4.4/best_practices.html)

Thank you !
