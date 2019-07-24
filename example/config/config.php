<?php
    header( 'Content-Type:text/html;charset=utf-8');
    date_default_timezone_set('PRC');
    // postgresql database
    define('PG_HOST', '127.0.0.1');
    define('PG_USER', 'jira_user');
    define('PG_PASSWD', '***');
    define('PG_NAME', 'jira_data');
    define('PG_PORT', 5432);
    define('LOG_PATH', '/home/jira_log/');

    define('DATABASE_TABLE_NAME', 'jira');
    define('JIRA_SITE_URL', 'https://your-site.atlassian.net/');
    define('JIRA_USER_EMAIL', '******');
    define('JIRA_API_TOKEN', '******');
