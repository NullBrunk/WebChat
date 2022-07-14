# WebChat

![image](https://user-images.githubusercontent.com/106782577/179045660-c9d4dea8-d606-4086-8c22-aab2a0abbc97.png)
the index.php

# Users demo
## Regular user :

https://user-images.githubusercontent.com/106782577/179044953-0e60e1b9-4add-46f8-990d-40bfacd9d232.mp4


## Admin user :

https://user-images.githubusercontent.com/106782577/179044206-fa848320-a9e4-4232-9b51-76fd5e279758.mp4




# Privs :

### Regular user :

- **Delete his own message**  
- **Post message**      
- **Can bee banned**      

### Admin :

- **Delete all the message**   
- **Post message**   
- **Ban users (supress them from the database)**   
- **Can see the content of the DB**    
- **Can give admin privs to users**   


# Pages :

- index.php -> Simple index page.    
- login.php -> This is the page to login to any account of the DB.       
- signup.php -> The page to create a basic user account with low privileges.             
- user.php -> The page of the basics users.      
- panel.php -> When the 'isadmin' column is set to '1' in the DB, the user is not redirected to user.php but to panel.php with the session cookie isadmin equal to 1.   


## Notes

There is 2 tables to create :
```sql
CREATE TABLE `users`
(
    `id` SMALLINT AUTO_INCREMENT,  
    `username` VARCHAR(65) NOT NULL,  
    `password` VARCHAR(65),
    `isadmin` BOOLEAN NOT NULL DEFAULT 0,  

    UNIQUE(`username`),
    PRIMARY KEY(`id`)
);
```

and

```sql
CREATE TABLE `forum`
(
    `id` SMALLINT AUTO_INCREMENT,
    `author` VARCHAR(65) NOT NULL,
    `text` TEXT NOT NULL,
  
    PRIMARY KEY(`id`)
);
```

**You can then create the first admin user with this :**

```sql
INSERT INTO `users`(username,password,isadmin) VALUES ('adminusername','adminpassword',1);
```
