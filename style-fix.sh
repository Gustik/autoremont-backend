#!/bin/sh

php-cs-fixer fix commands/ 
php-cs-fixer fix models/ 
php-cs-fixer fix modules/ 
php-cs-fixer fix controllers/ 
php-cs-fixer fix views/ 
php-cs-fixer fix tests/ 
