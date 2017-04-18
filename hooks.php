<?php
       
    /* 

    ANY OTHER FILES YOU NEED TO REQUIRE UP HERE
    
    */

    require dirname(__FILE__) . '/hooks/users/eligibleLdapUsersClass.php';

    function redcap_survey_page($project_id, $record, $instrument, $event_id, $group_id, $survey_hash, $response_id){

        // Perform certain actions for specific projects (using "switch" or "if" statements).
        switch ($project_id) {

            //case 0:  #### PUT YOUR PROJECT ID AFTER THE CASE STATEMENT

                $eligibleUsers = new eligibleLdapUsersClass();
                echo $eligibleUsers->getHTMLOutput();

            //break;

        }
    }

?>