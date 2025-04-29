FROM php:8.2-cli

RUN docker-php-ext-install mysqli

COPY . /app

WORKDIR /app

EXPOSE 3000

CMD ["php", "-S", "0.0.0.0:3000", "-t", "/app"]
