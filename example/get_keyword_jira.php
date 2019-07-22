<?php
    require '../src/vendor/autoload.php';
    require_once 'config/keyword_conf_server.php';
    require_once 'config/pg_conn.php';
    require_once 'config/keyword_fun.php';

    use chobie\Jira\Api;
    use chobie\Jira\Api\Authentication\Basic;
    use chobie\Jira\Issues\Walker;

    $dbh = get_pg_connect();
    $api = new Api(
        JIRA_SITE_URL,
        new Basic(JIRA_USER_EMAIL, JIRA_API_TOKEN)
    );
    $walker = new Walker($api);
    $walker->push(
        'project = "Content" ORDER BY created DESC'
    );
    $num_count = 0;
    $rowsToInsert = array();
    // record log
    $file = LOG_PATH . "jira-" . date("Ymd").".log";
    $ct = date("Y-m-d H:i:s", time());
    error_log("[".$ct."]  Jira API Data start insert postgresql \r\n", 3, $file);

    foreach ( $walker as $issue ) {
        $refObj  = new ReflectionObject($issue);
        $refProp1 = $refObj->getProperty('fields');
        $refProp1->setAccessible(TRUE);
        $keyword = $refProp1->getValue($issue)['Summary'];
        $vote_url = $refProp1->getValue($issue)['Votes']['self'];
        $con_id = substr( rtrim( ltrim($vote_url, JIRA_SITE_URL . 'rest/api/2/issue/'), '/votes' ), 4 );
        $res['summary'] = strtolower(trim($keyword));
        $res['issue_url'] = JIRA_SITE_URL . 'browse/CON-' . $con_id;
        $rowsToInsert[] = $res;   
        unset($res);
        // Because the JIRA API returns 50 pieces of data at a time, 50 pieces are batched into the database at a time
        if ( count($rowsToInsert) == 50 ) {
            // Remove duplicate summary
            $rowsToInsert = second_array_unique_bykey($rowsToInsert, 'summary');
            $exec_count = jira_upload_pdoMultiInsertOnIgnore(DATABASE_TABLE_NAME, $rowsToInsert);
            error_log("insert keyword : $exec_count \r\n", 3, $file);
            error_log("$num_count \r\n", 3, $file);
            unset($rowsToInsert);
        }
        $num_count++;
    }

    // Remove duplicate summary
    $rowsToInsert = second_array_unique_bykey($rowsToInsert, 'summary');
    if ( count($rowsToInsert) > 0 ) {
        $exec_count = jira_upload_pdoMultiInsertOnIgnore(DATABASE_TABLE_NAME, $rowsToInsert);
        error_log("Last insert keyword :  $exec_count \r\n", 3, $file);
    }
    unset($rowsToInsert);
    error_log("Jira API Num count:  $num_count \r\n", 3, $file);
    