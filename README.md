ScriptDesigner
======================================

ТРЕБОВАНИЯ
----------

php-fpm mysql nginx git


РАЗВЕРТЫВАНИЕ
-------------

Прочтите инструкцию по установке [Composer](http://getcomposer.org/), на [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).




Развертывание кода
~~~
sudo apt-get install php5.4-intl

mkrdir ssv2
cd ssv2
git clone git@github.com:romi45/ssv2.git .
composer install --no-dev --optimize-autoloader


sudo php yii core/configure/nginx-ssl onetheway.ru
php yii core/configure/db root toortoor one 0 one_user mY80Kpxd02
sh deploy/migrate.sh
sh deploy/rbac.sh
php yii user/user/create Owner romi45 agilovr@gmail.com asd123asd123
~~~


Конфигурация хоста и БД
~~~
sudo php yii core/configure/nginx ssv2
php yii core/configure/db root toortoor one 0 ss Q2qur7W9
~~~

Если развертываете dev-окружение:
~~~
php yii core/configure/dev
~~~


Установка сервера мгновенных сообщений "centrifugo"
~~~
curl -s https://packagecloud.io/install/repositories/FZambia/centrifugo/script.deb.sh | sudo bash
sudo apt-get install centrifugo=1.5.1-0

centrifugo --config="/home/ss/apps/ssv2/config/centrifugo.json" --log_file="/home/ss/apps/ssv2/runtime/logs/centrifugo.log"
~~~

Настройка NGINX как прокси для centrifugo
https://fzambia.gitbooks.io/centrifugal/content/deploy/nginx.html

Теперь Вы можете залогиниться как владелец: romi45/asd123asd123

Как поставить самоподписанный сертификат на локальный хост и разрешить его в браузере
https://ru.wikipedia.org/wiki/Самозаверенный_сертификат
https://www.8host.com/blog/sozdanie-ssl-sertifikata-na-apache-v-debian-8/
https://grevi.ch/blog/apache2-config-with-multiple-ssl-virtualhosts-on-one-ip-and-one-port-via-subject-alternative-names-san
https://wiki.webmoney.ru/projects/webmoney/wiki/Установка_корневого_сертификата_в_браузере_Google_Chrome


Установка Node.js
http://tohtml.it/post/73123118833/быстрая-настройка-и-простая-поддержка-nodejs

curl -sL https://deb.nodesource.com/setup_7.x | sudo -E bash -
sudo apt-get install -y nodejs
sudo npm install ws
sudo npm install forever -g

forever stopall
forever start -l run.log -e error.log -d recorder.js
