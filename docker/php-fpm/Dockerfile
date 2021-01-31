FROM yiisoftware/yii2-php:7.3-fpm

RUN docker-php-ext-install mysqli

RUN groupadd -g 1000 appuser && useradd -r -u 1000 -g appuser appuser
USER appuser