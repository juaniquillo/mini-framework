<?php

$options = getopt("p:");

$port = $options['p'] ?? '8000';

exec('cd public && php -S localhost:'.$port);