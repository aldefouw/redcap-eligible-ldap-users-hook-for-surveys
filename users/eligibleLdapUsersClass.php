<?php
class eligibleLdapUsersClass {

    private $ldap_url;
    private $ldap_port;
    private $ldap_connection;
    private $ldap_script_path;
    private $ldap_json_string;

    function __construct() {

        Authentication::setDSNs();

        $this->setLdapPort($GLOBALS['ldapdsn']['port']);
        $this->setLdapUrl($GLOBALS['ldapdsn']['url']);
        $this->setBaseDn($GLOBALS['ldapdsn']['basedn']);

        $this->setLdapConnection();
        $this->setLdapScriptPath();
        $this->setLdapUsers();
    }

    function setLdapScriptPath(){
        $this->ldap_script_path = dirname(__FILE__).'/ldap_users.php';
    }

    function setLdapUrl($ldap_url){
        $this->ldap_url = $ldap_url;
    }

    function setLdapPort($ldap_port){
        $this->ldap_port = $ldap_port;
    }

    function getLdapPort(){
        return $this->ldap_port;
    }

    function getLdapUrl(){
        return $this->ldap_url;
    }

    function setBaseDn($base_dn){
        $this->base_dn = $base_dn;
    }

    function getBaseDn(){
        return $this->base_dn;
    }

    function getLdapScriptPath(){
        return $this->ldap_script_path;
    }

    function setLdapConnection(){
        $this->ldap_connection = ldap_connect($this->getLdapUrl(), $this->getLdapPort());
    }

    function getLdapConnection(){
        return $this->ldap_connection;
    }

    function setLdapUsers(){
        $search = ldap_search($this->getLdapConnection(),
                              $this->getBaseDn(),
                              "(&(objectClass=Person)(uid=*)(sn=*)(givenname=*))",
                              array("uid", "sn", "givenname", "mail"),
                              0);
        if ($search) {
            $data = ldap_get_entries($this->getLdapConnection(), $search);

            $users = array();

            foreach($data as $key => $value){
                $id = $value['uid'][0];

                if(strlen($id)) {
                    $users[$id]['first_name'] = $value['givenname'][0];
                    $users[$id]['last_name'] = $value['sn'][0];
                    $users[$id]['email'] = $value['mail'][0];
                }
            }

            $this->ldap_json_string = json_encode($users);
        }
    }

    function getLdapUsers(){
        return $this->ldap_json_string;
    }

    function getHTMLOutput(){
        if( is_file( $this->getLdapScriptPath() ) ) {

            //Output buffer will fetch the output of this PHP file
            ob_start();
            require($this->getLdapScriptPath());
            $output = ob_get_contents();
            ob_end_clean();

            return $output;
        }
    }

}