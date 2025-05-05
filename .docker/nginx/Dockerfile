FROM nginx:alpine

COPY ./.docker/nginx/conf.d /etc/nginx/conf.d
COPY ./.docker/nginx/nginx.conf /etc/nginx/nginx.conf

COPY ./public /var/www/html/public
