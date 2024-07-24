#!/bin/bash
set -e

# Function to wait for MySQL to be ready
wait_for_mysql() {
    until mysqladmin ping -h"$DB_HOST" -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" --silent; do
        echo "Waiting for MySQL to be ready..."
        sleep 1
    done
}

# Execute migrations if needed
execute_migrations() {
    # Check if a marker file exists to indicate that migrations have already been executed
    if [ ! -e /var/www/html/ExpensesTracker/App/Migration/.migrations_completed ]; then
        echo "Executing database migrations..."
        php /var/www/html/ExpensesTracker/App/Migration/migrating_file.php
        php /var/www/html/ExpensesTracker/App/Migration/seed.php

        # Create a marker file to indicate that migrations have been completed
        touch /var/www/html/ExpensesTracker/App/Migration/.migrations_completed
    else
        echo "Database migrations have already been executed. Skipping."
    fi
}


# Function to run every minute
run_every_minute() {
    while true; do
        echo "Running script every minute..."
        php /var/www/html/ExpensesTracker/Command/cronTab.php
        sleep 60  # Sleep for 60 seconds (1 minute)
    done
}

# Main entry point
main() {
    wait_for_mysql
    execute_migrations

    # Wait for 2 minutes before starting the first run_every_minute
    echo "Waiting for 10 seconds before starting the script every minute..."
    sleep 10  # Sleep for 120 seconds (2 minutes)

    # Start tasks to run every minute in the background
    run_every_minute &

    # Start Apache in the foreground
    apache2-foreground
}

main "$@"
