curl -sS https://getcomposer.org/installer | php

php composer.phar install

if ! [ -d "./temp" ]; then
  mkdir "./temp"
fi
