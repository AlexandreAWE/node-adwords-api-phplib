curl -sS https://getcomposer.org/installer | php

php composer.phar require googleads/googleads-php-lib

if ! [ -d "./temp" ]; then
  mkdir "./temp"
fi
