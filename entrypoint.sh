#!/bin/bash sh

composer update

phpunit --coverage-clover build/logs/clover.xml

sh -c 'php vendor/bin/coveralls -v'
