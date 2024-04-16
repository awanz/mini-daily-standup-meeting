# Daily Standup Meeting

## Feature

```
1. Login
2. Logout
3. Lapor Daily
4. History
5. User
6. Send Email
```


## Database Schema (DDL)

```Users
CREATE TABLE `users` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`token` VARCHAR(50) NOT NULL COLLATE 'latin1_swedish_ci',
	`fullname` TEXT NOT NULL COLLATE 'latin1_swedish_ci',
	`email` TEXT NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	PRIMARY KEY (`id`) USING BTREE,
	UNIQUE INDEX `token` (`token`) USING BTREE
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1
;
```


```Dailys
CREATE TABLE `dailys` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`user_id` INT(10) NOT NULL,
	`date_activity` DATE NOT NULL,
	`yesterday` TEXT NOT NULL COLLATE 'latin1_swedish_ci',
	`today` TEXT NOT NULL COLLATE 'latin1_swedish_ci',
	`problem` TEXT NOT NULL COLLATE 'latin1_swedish_ci',
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`) USING BTREE,
	UNIQUE INDEX `user_id_date_activity` (`user_id`, `date_activity`) USING BTREE
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=2
;
```

## Data Seed (DML)

```
INSERT INTO `users` (`id`, `token`, `fullname`, `email`) VALUES
	(1, '2W4n', 'awan', 'awan@awancoder.com');
```