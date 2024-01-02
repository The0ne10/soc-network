FROM node:20-alpine

RUN mkdir -p /var/www/app
RUN chown -R node /var/www/app
USER node

WORKDIR /var/www/app









