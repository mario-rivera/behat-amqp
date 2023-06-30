#!/bin/sh

echo "Running composer"
composer install --working-dir=/app

# # Check for rabbit
# /app/docker/shell/netcat-wait.sh rabbitmq:5672

# # Create rabbit schema
# if [ $? -eq 0 ]; then
#     php scripts/rabbit_up.php
# else
#     echo "Rabbit MQ not detected skipping schema creation"
# fi

sh