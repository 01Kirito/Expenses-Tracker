# Setup project using the docker 
### 1-Go to the project folder by the command below
cd Expenses-Tracker
### 2-Get firebase auth service JSON file(used to authenticate with firebase) then put the content into the authService.json file in the private directory 
you can follow the steps in this [link](https://help.emarsys.com/hc/en-us/articles/360004905238-Android-integration-Mobile-Engage-Firebase-authentication-for-Push-messages#:~:text=4.-,Service%20Account%20JSON%20file,be%20downloaded%20to%20your%20computer.) to create the file with a project on Firebase 
### 3-Rename .env.example file to .env by the below command 
ren .env.example .env   
### 4-Then configure, cause you use docker you don't need to configure all of it, you just set jwt key that explained in the below how to create the key
### 5-Then run command 
#### but be careful before running it to check if the myStartupScript.sh is lf(Line Feed), if doesn't make it to lf in editors.
docker-compose -p project up
### Now you can test endpoints by importing the environments and api collections from the postman and use it 

<be>
  
## Also if you want to access the containers we can use this command for database(mysql) and cache(redis)
### Opening the bash for the container we want to access
docker exec -it db bash   or   docker exec -it db bash 
### Then for mysql we use the command below then enter the password
mysql -h db -u root -p
### And for the redis use this command below
redis-cli -h redis -p 6379
<be>

## Packages I used for the project 
### 1- vlucas/phpdotenv            //  for using the .env file
### 2- rbdwllr/reallysimplejwt     //  for auth and make jwt
### 3- predis/predis               //  for cache with redis
<be>

## JWT Secret Key generating
This JWT library [(reallysimplejwt)](https://github.com/RobDWaller/ReallySimpleJWT?tab=readme-ov-file#secret-strength) imposes strict secret security via the EncodeHS256Strong class. The secret provided must be at least 12 characters in length; contain numbers; upper and lowercase letters; and one of the following special characters *&!@%^#$.
