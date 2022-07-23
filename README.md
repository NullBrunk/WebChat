# WebChat

![image](https://user-images.githubusercontent.com/106782577/179762868-ce09f7c7-5acb-416b-80de-16bf0e919a58.png)

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

# Launch services :

```
sudo systemctl enable --now php-fpm httpd mysqld
```
Open your browser and type http://127.0.0.1/.

## You see something like "permission denied"

SELinux is probably blocking HTTPd, please type this :
```
sudo setsebool -P httpd_unified 1
```
Then, for good practice, restart all the services : **
```
sudo systemctl restart --now php-fpm httpd mysqld
```
And retry opening 127.0.0.1 in your browser.

# Configuration of mysql

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
