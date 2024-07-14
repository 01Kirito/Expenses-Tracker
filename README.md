# Installation steps
## Creating the database
run the App/Migration/Schema_design.php   
## Feeding the users for testing , not neccessary
run the App/Migration/Seeder/seed_category.php
## Creating an image for the Redis in the docker 
### This command below will create an image and then run it in the container this will have default configure like username = "root" password = "" port="6379" and so on , that mean you don't need to config it
docker run -d --name my-redis-stack -p 6379:6379  redis/redis-stack-server:latest
## Now you should import the postman file 
