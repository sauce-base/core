#!/bin/sh

set -a
source .env
set +a

mkcert -key-file docker/development/ssl/app.key -cert-file docker/development/ssl/app.cert $APP_HOST *.$APP_HOST
