# TicketSphere
An event ticketing system built with PHP

## Table of Contents
- [Features](#features)
- [Technologies Used](#technologies-used)
- [Setup and Installation](#setup-and-installation)
- [Contributing](#contributing)
- [Licensing](#licensing)

## Features
- User authentication
- Admin pages for managing events, users, and tickets
- Events with opening and closing ticket sale dates
- Clean UI built with bootstrap
- Ability to scan tickets and prevent multiple uses
- Option to add more than one admission per ticket
- User registration

## Technologies Used
- PHP
- Bootstrap
- MySQL

## Setup and Installation
1. Clone the repository:
```bash
git clone https://github.com/LiamSpatola/TicketSphere
cd TicketSphere
```

2. Run the following commands in your MySQL database:
```sql
CREATE DATABASE `ticketsphere`;
```

```sql
USE `ticketsphere`;
```

```sql
CREATE TABLE `events` (
	`eventID` INT(10) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(250) NOT NULL COLLATE 'utf16_general_ci',
	`description` LONGTEXT NOT NULL COLLATE 'utf16_general_ci',
	`venue` VARCHAR(250) NOT NULL COLLATE 'utf16_general_ci',
	`date` DATETIME NOT NULL,
	`ticketSaleStartDate` DATETIME NOT NULL,
	`ticketSaleEndDate` DATETIME NOT NULL,
	`numberOfTicketsRemaining` INT(10) NOT NULL,
	`admissionsPerTicket` INT(10) NOT NULL,
	PRIMARY KEY (`eventID`) USING BTREE,
	UNIQUE INDEX `eventName` (`name`) USING BTREE
)
COLLATE='utf16_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1;
```

```sql
CREATE TABLE `users` (
	`userID` INT(10) NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(250) NOT NULL COLLATE 'utf16_general_ci',
	`password` VARCHAR(250) NOT NULL COLLATE 'utf16_general_ci',
	`firstName` VARCHAR(100) NOT NULL COLLATE 'utf16_general_ci',
	`lastName` VARCHAR(100) NOT NULL COLLATE 'utf16_general_ci',
	`email` VARCHAR(100) NOT NULL COLLATE 'utf16_general_ci',
	`isAdmin` TINYINT(3) NOT NULL DEFAULT '0',
	PRIMARY KEY (`userID`) USING BTREE,
	UNIQUE INDEX `username` (`username`) USING BTREE
)
COLLATE='utf16_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1;
```

```sql
CREATE TABLE `tickets` (
	`ticketID` INT(10) NOT NULL AUTO_INCREMENT,
	`eventID` INT(10) NOT NULL,
	`userID` INT(10) NOT NULL,
	`purchaseDate` DATETIME NOT NULL,
	`admissionsLeft` INT(10) NOT NULL,
	PRIMARY KEY (`ticketID`) USING BTREE,
	INDEX `FK__events` (`eventID`) USING BTREE,
	INDEX `FK__users` (`userID`) USING BTREE,
	CONSTRAINT `FK__events` FOREIGN KEY (`eventID`) REFERENCES `events` (`eventID`) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `FK__users` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON UPDATE CASCADE ON DELETE CASCADE
)
COLLATE='utf16_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1;
```

3. Create a new user in your MySQL database:
```sql
CREATE USER 'ticketsphere'@'%' IDENTIFIED BY 'password';
GRANT USAGE ON *.* TO 'ticketsphere'@'%';
GRANT SELECT, INSERT, UPDATE, DELETE  ON `ticketsphere`.* TO 'ticketsphere'@'%';
```
**Ensure you change the password to something more secure. If you have used a different database name, you will need to change the above query.**

4. Create a `settings.php` file in the `utils` directory:
```bash
cd utils
touch settings.php # On windows, use 'type nul > settings.php'
```

5. Add the following code to `settings.php`:
```php
<?php
    if (!class_exists("Settings")) {
        class Settings {
            // Database credentials
            const DB_SERVER = "localhost";
            const DB_USER = "ticketsphere";
            const DB_PASSWORD = "password";
            const DB_NAME = "ticketsphere";

            // Timeout duration
            const TIMEOUT_DURATION = 1800;

            // Timezone
            const TIMEZONE = "Australia/Sydney";
        }
    }
?>
```
**Change the values to whatever matches your situation.**

| Setting | What It Does |
| ------- | ------------ |
| DB_SERVER | The IP address of your MySQL server |
| DB_USER | The username for your MySQL user (the one setup in step 3) |
| DB_PASSWORD | The password for your MySQL user (the one setup in step 3) |
| DB_NAME | The database name |
| TIMEOUT_DURATION | The amount of time, in seconds, that the user is allowed to be inactive for before being logged out. |
| TIMEZONE | The timezone for the application. |

6. Start your web server with PHP and create an admin user (for the website) by going to [localhost/register.php](localhost/register.php) and filling in the form. Then run the following SQL command to make the user an admin:
```sql
UPDATE users
SET isAdmin = 1
WHERE username = 'admin';
```
**Make sure you replace the username with the username you set up.**

7. Navigate to [localhost/login.php](localhost/login.php). Login using the user created in step 6. You can now use the website and create events. Register as a regular user to purchase tickets by heading to [localhost/register.php](localhost/register.php).

## Contributing
Please feel free to contribute to this repository.

## Licensing
TicketSphere is licensed under the GNU GPLv3. The full license text, as well as an explanation of what you can and can't do under this license, is available in the `LICENSE` file or [here](https://choosealicense.com/licenses/gpl-3.0/).
