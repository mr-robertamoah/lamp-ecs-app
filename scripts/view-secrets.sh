#!/bin/bash

echo "Viewing Parameter Store secrets..."

# View MySQL root password
echo "MySQL Root Password:"
aws ssm get-parameter \
    --name "/copilot/lampstack-app/production/secrets/MYSQL_ROOT_PASSWORD" \
    --with-decryption \
    --query 'Parameter.Value' \
    --output text \
    --region eu-west-1 \
    --profile sandbox

# View DB password
echo "DB Password:"
aws ssm get-parameter \
    --name "/copilot/lampstack-app/production/secrets/DB_PASSWORD" \
    --with-decryption \
    --query 'Parameter.Value' \
    --output text \
    --region eu-west-1 \
    --profile sandbox