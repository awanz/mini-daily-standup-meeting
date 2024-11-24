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

## Structure Folder
mini-daily-standup-meeting/
├── assets/
│   ├── bootstrap-5.3.3-dist/
│   └── jquery/
├── database/
│   ├── sql/
│	└── schema.dbml
├── .env
├── .gitignore
├── .htaccess
├── next.config.js
├── middleware.php
└── README.md


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

```Table Views
select `u`.`id` AS `id`,`u`.`fullname` AS `fullname`,`u`.`email` AS `email`,coalesce(sum(case when month(`a`.`date_activity`) = month(curdate()) then 1 else 0 end),0) AS `total_daily` from (`kawp4581_daily`.`users` `u` left join `kawp4581_daily`.`dailys` `a` on(`u`.`id` = `a`.`user_id`)) group by `u`.`id`,`u`.`fullname`,`u`.`email`
```

## Data Seed (DML)

```
INSERT INTO `users` (`id`, `token`, `fullname`, `email`) VALUES
	(1, '2W4n', 'awan', 'awan@awancoder.com');
```
