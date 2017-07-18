#!/bin/bash sh

composer update

phpunit

phpstan analyse Exception/ Hydrators/ Services/ Tests/ Twig/ Utils/ --level=4

#tail -f /dev/null