# WebChat

![image](https://user-images.githubusercontent.com/106782577/179762868-ce09f7c7-5acb-416b-80de-16bf0e919a58.png)

# Personnal notes : 

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
