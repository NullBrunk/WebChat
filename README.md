# WebChat
A basic online chat created using PHP and MySQL

# Demo :

## Regular user :


https://user-images.githubusercontent.com/106782577/179040352-59c8d8a3-f018-4797-8ac8-f5d2f5cc1472.mp4

## Admin user :



https://user-images.githubusercontent.com/106782577/179040410-6d645783-906b-46e7-926d-51ea233b80e3.mp4



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
