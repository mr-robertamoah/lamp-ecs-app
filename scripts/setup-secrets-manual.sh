#!/bin/bash

# Manual setup with user input
echo "Setting up secrets for LAMP stack..."
echo "Enter a secure password for MySQL (or press Enter to generate one):"
read -s USER_PASSWORD

if [ -z "$USER_PASSWORD" ]; then
    SECURE_PASSWORD=$(openssl rand -base64 32)
    echo "Generated secure password: $SECURE_PASSWORD"
else
    SECURE_PASSWORD="$USER_PASSWORD"
fi

# Create MySQL root password in Parameter Store
aws ssm put-parameter \
    --name "/copilot/lampstack-app/production/secrets/MYSQL_ROOT_PASSWORD" \
    --value "$SECURE_PASSWORD" \
    --type "SecureString" \
    --description "MySQL root password for LAMP stack" \
    --overwrite \
    --region eu-west-1 \
    --profile sandbox

# Create DB password for app service
aws ssm put-parameter \
    --name "/copilot/lampstack-app/production/secrets/DB_PASSWORD" \
    --value "$SECURE_PASSWORD" \
    --type "SecureString" \
    --description "Database password for LAMP stack app" \
    --overwrite \
    --region eu-west-1 \
    --profile sandbox   

echo "✅ Secrets created successfully in Parameter Store"