<?php

// Idnclusion of rejlevant libttrary and files
include 'libs\facebook.php';
include 'functions.php';
include 'view.php';

// Get app data from the config file and prepare the facebok object
 
$appId = parseIniBySectionAndVariableName("App Details","appId");
$secret = parseIniBySectionAndVariableName("App Details","secret");
$facebook = createFacebookObject($appId, $secret);






// If the parameter logged out found log out current facebook session
if( isset( $_GET['logged_out'] )  ) {
    $fb_key = 'fbsr_'.$appId;
    setcookie($fb_key, '', time()-3600);
    $facebook->destroySession();
}

//Get current session user of facebook
$user = getFacebookUser($facebook);

//If the user exists continue to App and generate the business card else display the login screen
if ($user) {
    try {


        $logout_url = getLogoutUrl($facebook);
        $user_info = getUserInfoObject($facebook);
        $post_info = getPostInfo($facebook);

        
        // Get data of counts per type, number of likes, comments and total number of posts
         
        
        $type_index = parseIniBySectionAndVariableName("Type Details","type");
        $ref_type = createTypeArray($type_index);
        $posts = count($post_info);

        
        for ($i = 0, $likes = 0, $comments = 0; $i < $posts; ++$i) {
            
            if (array_key_exists($post_info[$i]['type'], $ref_type))
                $ref_type[$post_info[$i]['type']]['count']+=1;

            if (array_key_exists('count', $post_info[$i]['likes'])) {
                $likes+=$post_info[$i]['likes']['count'];
            }

            if (array_key_exists('count', $post_info[$i]['comments'])) {
                $comments+=$post_info[$i]['comments']['count'];
            }
        }

        // Get Final object
         
      $finalObj= getFinalObjectLogout($ref_type, $type_index, $likes, $comments, $user_info, $posts,$logout_url);
        
        //Display the Html generated
   echo prepareLoggedInMain($finalObj,$type_index);
        
    } catch (FacebookApiException $e) {
        echo $e;
        error_log($e);
        $user = null;
    }
} else {

   //Prepare login page
    $login_url = getLoginUrl($facebook);
    echo prepareLoggedOutMain($login_url);
}
?>
  
