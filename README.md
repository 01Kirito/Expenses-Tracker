# Setup project using the docker 
### Get firebase auth service file to the private directory 
### Rename .env.example file to .env by the below command 
ren .env.example .env   
### Then configure, cause you use docker you donot need to configure all of it you just need to put the file name of the variable GOOGLE_APPLICATION_CREDENTIALS
### Then run command 
docker-compose up -d
### Now you can test endpoints by importing the environments and api collections from the postman and use it 
