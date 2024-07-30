# Setup project using the docker 
### 1-Go to the project folder by the command below
cd Expenses-Tracker
### 2-Get firebase auth service JSON file to the private directory 
you can follow the steps in this link to create the file with a project on Firebase (https://help.emarsys.com/hc/en-us/articles/360004905238-Android-integration-Mobile-Engage-Firebase-authentication-for-Push-messages#:~:text=4.-,Service%20Account%20JSON%20file,be%20downloaded%20to%20your%20computer.)
### 3-Rename .env.example file to .env by the below command 
ren .env.example .env   
### 4-Then configure, cause you use docker you donot need to configure all of it you just need to put the file(auth service) name to the variable GOOGLE_APPLICATION_CREDENTIALS
### 5-Then run command 
#### but be careful before running it to check if the myStartupScript.sh is lf(Line Feed), if doesn't make it to lf in editors.
docker-compose -p project up
### Now you can test endpoints by importing the environments and api collections from the postman and use it 

## Also if you want to access the containers we can use this command for database and cache(redis)
### Opening the bash for the container we want to access
docker exec -it db bash   or   docker exec -it db bash 
### Then for mysql we use the command below then enter the password
mysql -h db -u root -p
### And for the redis use this command below
redis-cli -h redis -p 6379

## And for the AccountService.json file you should create it then 
