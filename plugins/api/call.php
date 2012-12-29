<?php

//No HTML-output, please!
ob_end_clean();

//Getting PHPlist globals for this plugin
$plugin = $GLOBALS["plugins"][$_GET["pi"]];

include 'includes/response.php';
include 'includes/pdo.php';

include 'includes/common.php';

include 'includes/actions.php';
include 'includes/lists.php';
include 'includes/users.php';
include 'includes/templates.php';
include 'includes/messages.php';

include 'doc/doc.php';


//Check if this is called outside PHPlist auth, this should never occur!
if ( empty( $plugin->coderoot ) ){
    PHPlist_API_Response::outputErrorMessage( 'Not authorized! Please login with [login] and [password] as admin first!' );
}

//If other than POST then assume documentation report
if ( strcmp( $_SERVER['REQUEST_METHOD'], "POST")  ){

    $doc = new PHPlist_API_Doc();
    $doc->addClass( 'PHPlist_API_Actions' );
    $doc->addClass( 'PHPlist_API_Lists' );
    $doc->addClass( 'PHPlist_API_Users' );
    $doc->addClass( 'PHPlist_API_Templates' );
    $doc->addClass( 'PHPlist_API_Messages' );
    $doc->output();

}

//Check if command is empty!
$cmd = $_REQUEST['cmd'];
if ( empty($cmd) ){
    PHPlist_API_Response::outputMessage('OK! For action, please provide Post Param Key [cmd] !');
}

//Now bind the commands with static functions
if ( is_callable( array( 'PHPlist_API_Lists',       $cmd ) ) ) PHPlist_API_Lists::$cmd();
if ( is_callable( array( 'PHPlist_API_Actions',     $cmd ) ) ) PHPlist_API_Actions::$cmd();
if ( is_callable( array( 'PHPlist_API_Users',       $cmd ) ) ) PHPlist_API_Users::$cmd();
if ( is_callable( array( 'PHPlist_API_Templates',   $cmd ) ) ) PHPlist_API_Templates::$cmd();
if ( is_callable( array( 'PHPlist_API_Messages',    $cmd ) ) ) PHPlist_API_Messages::$cmd();

//If no command found, return error message!
PHPlist_API_Response::outputErrorMessage( 'No function for provided [cmd] found!' );

?>