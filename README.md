# SoulDkp

SoulDkp is web-application built to make it easier to handle DKP-like systems in gaming-communities.

SoulDkp is built on top of [Laravel](https://laravel.com/ "Laravel framework"). Any original work on the framework is not my own work.

The code written for SoulDkp is licensed under GPL-3.0. Similiar and related code can be found at [JelaDKP](https://github.com/sawyl/jelaDKP "JelaDKP Git repository") repository. Read background section for further information.

These codes can be found under following folders:

```
/app/Http/Controllers/*
/database/backup/*
/public/* #!!all but /assets/external!!
/resource/lang/*
/resource/views/*
/routes/web.php
```
*Note: \* means the whole folder is SoulDkp code*

## Background

SoulDkp is part of master thesis project. Two separate web-applications were created for the thesis.

This project (SoulDkp) was created first. The project was done with minimal time invested before getting started, and not using any design patterns as help.

The second project [JelaDKP](https://github.com/sawyl/jelaDKP "JelaDKP Git repository") was the second created project.
JelaDKP was done with proper preparations. Two design patterns were considered when creating that project:
    
	1. MVC-model
	2. Template method model


## Install instructions.
    1. Set up your web environment
	    1.1. Fetch the application files into the folder you want to use as your public html folder.
		1.2. Install the application like regular Laravel application https://laravel.com/docs/5.5/installation. EXCLUDING database section.
	2. Database install:
	    2.1. Create MySQL-database named 'soulDKP' and create user with permissions to the table.
		2.2. Restore the /database/backup/soulDKP.sql as your newly created table.
		2.3. Rename/create the users you want for login into users-table. Please note that you need to bcrypt the password. Default user login is
		    * Username: Test
			* Password: password
	3. Enjoy.