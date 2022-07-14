# WebChat
A basic online chat created using PHP and MySQL

![image](https://user-images.githubusercontent.com/106782577/179045064-e4882368-52da-4f77-b7e3-7fccd95d5ff5.png)


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

- index.php -> the index   
- login.php -> page to login to an account, same page for admins and users    
- signup.php -> The page to create a basic user account with low privs   
- user.php -> the page of basic users    
- panel.php -> when the 'isadmin' column is set to '1' in the DB, the user is not redirected to user.php but to panel.php with the session cookie isadmin on 1   


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

**Create an admin user**

```sql
INSERT INTO `users`(username,password,isadmin) VALUES ('adminusername','adminpassword',1);
```
