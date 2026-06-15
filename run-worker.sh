#!/bin/bash
# Make sure this file has executable permissions, run `chmod +x run-worker.sh`

# This command runs the queue worker. 
# The timeout controls how long a job may run before the worker kills it.
php artisan queue:work --queue=printing,default --tries=1 --timeout=60