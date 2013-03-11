<?php

/**
 * Retrieves the relavant variable from the given section from the configuration file (.ini)
 * 
 * 
 * @param type $section section The section under which the parameter exists
 * @param type $variable The variable name
 * @return type
 */
function parseIniBySectionAndVariableName($section,$variable)
{
	$ini=parse_ini_file("config.ini",true);
	return $ini[$section][$variable];

}

/**
 * Executes the FQL query and returns the answer
 * 
 * @param type $query The FQL query
 * @param type $facebook The App object
 * @return type The result of the query
 */


function executeQuery($query,$facebook)
{
    $result = $facebook->api(array('method' => 'fql.query', 'query' => $query));
    return $result;    
}

/**
 * Creates the counter array for type. Index 'typename' contains the type name and index 'count' initialized to 0
 *  
 * 
 * @param type $key_array The array containing key=>typename association
 * @return array The counter array for type
 */
function createTypeArray($key_array)
{$type_array=new ArrayObject();
    
    foreach ($key_array as $type_no => $type_name)
{
    $type_array[$type_no]['typename']=$type_name;
    $type_array[$type_no]['count']=0;
}

return $type_array;
}

/**
 * Constructs facebook app object
 * 
 * @param type $appId The appId of the app provided by facebook
 * @param type $secret The secret of the app provided by facebook
 * @return type Facebook The facebook app object
 */

function createFacebookObject($appId,$secret)
{
    $facebook = new Facebook(array(
            'appId' => $appId,
            'secret' => $secret,
        ));
    
    return $facebook;
}


/**
 * Gets the facebook user logged in the current session
 * 
 * @param type $facebook The App object
 * @return type User object for the current session, null if no one is logged in
 */
function getFacebookUser($facebook)
{
    $user=$facebook->getuser();
    return $user;    
}


/**
 * Gets login Url to facebook for the app.
 * 
 * @param type $facebook The App object
 * @return type The login URL
 */
function getLoginUrl($facebook)
{
    $login_url = $facebook->getLoginUrl(array("scope" => "read_stream, user_location", 'redirect_uri' => parseIniBySectionAndVariableName("App Details","domain")));
    return $login_url;
}

/**
 * Gets logout Url to facebook for the app.
 * 
 * @param type $facebook The App object
 * @return type The login URL
 */


function getLogoutUrl($facebook)
{
     $params = array( 'next' => parseIniBySectionAndVariableName("App Details","domain")."?logged_out" );
     $logout_url = $facebook->getLogoutUrl( $params);
     return $logout_url ;
}


/**
 * Gets user information such as name, current location and URL of the pic
 * 
 * @param type $facebook The App object
 * @return Array An array containg user information
 */
function getUserInfoObject($facebook)
{
    $user_info = executeQuery( 'SELECT name, current_location, pic FROM user where uid= me()',$facebook);
    return $user_info;
}

/**
 * Gets Post data for last 90 days.
 * 
 * @param type $facebook The app object
 * @return type Posts info data
 */
function getPostInfo($facebook){
    //Here permalinks is defined or not is kept as a condition since as observed from the post data any link on the user page has a link associated to it. The stream data generically also contains posts relating to users activity on others wall
    $post_info = executeQuery('SELECT post_id,permalink, type, likes, comments FROM stream WHERE source_id=me()AND permalink ORDER BY type DESC LIMIT 1000000',$facebook);
    return $post_info;
    
}

/**
 * Consolidates information required by the front end into one array
 * 
 * @param type $ref_type The counter array of types
 * @param type $type_index The type index array from the ini file
 * @param type $likes Total number of likes
 * @param type $comments Total comments
 * @param type $user_info User info array
 * @param type $posts Total number of posts
 * @param type $logout_url The logout URL
 * @return type An array containing relevant informatoin
 */
function getFinalObjectLogout($ref_type, $type_index, $likes, $comments, $user_info, $posts, $logout_url)
{
      foreach ($type_index as $type_no=>$k)
{
    $finObj[$ref_type[$type_no]['typename']]=$ref_type[$type_no]['count'];
}
    $finObj['likes']=$likes;
    $finObj['comments']=$comments;
    $finObj['posts']=$posts;
    $finObj['name']=$user_info[0]['name'];
    $finObj['location']=$user_info[0]['current_location']['city'].",".$user_info[0]['current_location']['state'];
    $finObj['image']=  $user_info[0]['pic'] ;
    $finObj['logoutUrl']= $logout_url;
    
    return $finObj;
}




?>
