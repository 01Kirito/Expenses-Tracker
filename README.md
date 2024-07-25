# Setup project using the docker 
### 1-Get firebase auth service file to the private directory then go to the project folder by the command below
cd Expenses-Tracker
### 2-Rename .env.example file to .env by the below command 
ren .env.example .env   
### 3-Then configure, cause you use docker you donot need to configure all of it you just need to put the file name of the variable GOOGLE_APPLICATION_CREDENTIALS
### 4-Then run command 
#### but becareful before running it check if the myStartupScript.sh is lf, if n't make it to lf by vs code in the button you can change it easily
docker-compose up
### Now you can test endpoints by importing the environments and api collections from the postman and use it 
