# Jira-API-PHP
Getting all Jira data through PHP

# By doing the following, you can get all the Jira data and store it in the PostgreSQL database

# Install and Config

## get jira php api client
run command
create `src` directory and `cd src` and run command
`composer require chobie/jira-api-restclient ^2.0@dev `

## Get Jira API Token
You need to create API Token at the following link address
[https://id.atlassian.com/manage-profile/security]

Also you can click on the link below to get a detailed tutorial.
[https://www.ryanzoe.top/php/jira-rest-api-php-get-data/#getToken]

## Create PostgreSQL Database
First, create database 
`create database jira_data;`
Second, run the sql script in jira_data 
`psql -U PG_USER jira_data -f /Path/example/database/jira.sql`

## run example/get_keyword_jira.php to get jira data and insert into databases

## You can contact me directly if you have any questions.
[https://www.ryanzoe.top/contact-me/]
