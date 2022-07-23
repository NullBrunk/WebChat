# WebChat

# Index 
- <a href="">Demo</a>
- <a href="">Permissions</a>
- <a href="">Installation (fedora36)</a>


# Demo :
Index pahe:
![image](https://user-images.githubusercontent.com/106782577/179762868-ce09f7c7-5acb-416b-80de-16bf0e919a58.png) 
Video :      
https://user-images.githubusercontent.com/106782577/180606625-56e67985-1057-41ce-81fb-96386b0a5eb0.mp4


# Permissions 

**Users**
- Can only clear his messages and have not access to the !clear command
- Can ping users and admins
- Can write in the chat
- Can change his password

**Admin**
- Can read the database
- Can ban users (but not admins)
- Can use !clear command and can delete the messages of all users (using the trash icon) 
- Can ping and write in th chat


# Install on fedora36 : 

**First you need to create a lamp stack :**

```bash
sudo dnf install community-mysql-server php-mysql php-fpm httpd
```

**Then secure your mysql installation by typing:**

```
mysql_secure_installation
```

**Git cloning the project in the www dir :**

``` 
cd /var/www/html
sudo git clone https://github.com/NullBrunk/WebChat
sudo mv WebChat/* .
sudo rm -r WebChat
sudo chmod 777 /var/www/html/*
```

## Launch services :


SELinux will probably block HTTPd, please type this :
```
sudo setsebool -P httpd_unified 1
```

Then enable all the services :

```
sudo systemctl enable --now php-fpm httpd mysqld
```

## Configuration of mysql

**First go to /var/www/html and edit the db-config.php, put the password that you set when you type mysql_secure_installation instead of `YOUR_MYSQL_PASSWORD`, and save the file.**     


Connect to mysql using ``mysql -uMYSQL_USERNAME -pMYSQL_PASSWORD`` (mysql username will probably be root). Then lets create the db, the tables, and a root user.

```sql
CREATE DATABASE db;
USE db;
```

And create  the tables :

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
    `author` VARCHAR(65) NOT NULL,
    `text` TEXT NOT NULL,
    `id` SMALLINT AUTO_INCREMENT,
 
    PRIMARY KEY(`id`)
);
```

**You can then create the first admin user with this :**

```sql
INSERT INTO `users`(username,password,isadmin) VALUES ('THE_ADMIN_USERNAME','THE_ADMIN_PASSWORD',1);
```

## All is ok
If you don't got any error, try to go to http://127.0.0.1/ and see if all work.
