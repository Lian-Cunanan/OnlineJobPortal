# The Offical Member of the Group are Lian Cunanan And Cantara

# Online Job Portal

Welcome to the Online Job Portal! This guide will help you set up the project on your local machine.

## Prerequisites

Before you start, ensure you have the following software installed on your computer:

1. **XAMPP** - A free and open-source cross-platform web server solution stack package.
   - Download and install from [Apache Friends](https://www.apachefriends.org/index.html).
   
2. **Node.js** - A JavaScript runtime built on Chrome's V8 JavaScript engine.
   - Download and install from [Node.js](https://nodejs.org/).

3. **PHP** - A popular general-purpose scripting language that is especially suited to web development.
   - PHP is included in the XAMPP package.

## Setup Instructions

### Step 1: Install XAMPP

1. Download and install XAMPP from [Apache Friends](https://www.apachefriends.org/index.html).
2. During installation, ensure you select Apache and MySQL components.

### Step 2: Install Node.js

1. Download and install Node.js from [Node.js](https://nodejs.org/).

### Step 3: Configure the Database

1. Open **XAMPP Control Panel** and start both **Apache** and **MySQL** services.
2. Open your web browser and go to [phpMyAdmin](http://localhost/phpmyadmin).
3. Create a new database named `job_portal`.
4. Import the provided SQL file into the `job_portal` database:
   1. Click on the `job_portal` database.
   2. Go to the `Import` tab.
   3. Choose the SQL file named `job_portal.sql` and click `Go`.

### Step 4: Run the Website

1. Place the Online Job Portal project files in the `htdocs` directory of your XAMPP installation. This is typically found at `C:\xampp\htdocs`.
2. Open your web browser.
3. Navigate to `http://localhost/OnlineJobportal/`.

## Summary

By following these steps, you should have the Online Job Portal up and running on your local machine. If you encounter any issues, ensure all the prerequisites are installed correctly and that the XAMPP services are running.

---

If you have any questions or need further assistance, please refer to the Bscpe 2-A.
