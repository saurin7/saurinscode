<?php


/**
 * Get the html object for list containing type of post and its count
 * 
 * 
 * @param type $finalObj The final object for output
 * @param type $type_index The type index
 * @return string The HTML string corresponding to the list
 */

function ItemSearch($SearchIndex, $Keywords){
 //Enter your IDs
define("Access_Key_ID", "AKIAIKPTPUVCUQQWRJTA");
define("Associate_tag", "localhostfacb-20");

//Set the values for some of the parameters
$Operation = "ItemSearch";
$Version = "2011-08-01";
$ResponseGroup = "ItemAttributes,Offers";
//User interface provides values
//for $SearchIndex and $Keywords
//Define the request
$request=
 "http://webservices.amazon.com/onca/xml"
 . "?Service=AWSECommerceService"
 . "&AssociateTag=" . Associate_tag
 . "&AWSAccessKeyId=" . Access_Key_ID
 . "&Operation=" . $Operation
 . "&Version=" . $Version
 . "&SearchIndex=" . $SearchIndex
 . "&Keywords=" . $Keywords
 . "&ResponseGroup=" . $ResponseGroup;
//Catch the response in the $response object
$response = file_get_contents($request);
$parsed_xml = simplexml_load_string($response);

return $parsed_xml;
}


function getList($finalObj,$type_index)
{
   
//Set up the operation in the request


$list=null;
 foreach ($type_index as $type_no=>$k)
{
$list.="<li class=\"floatLeft clear subList\" onclick=\"$('.dropdown').slideUp('slow');return false;\">".$k."<span class=\"floatRight indentFromRight\">".$finalObj[$k]."</span></li>";
}
return $list;
}

/**
 * Prepares HTML for business card based on the data retrived
 * 
 * @param type $finalObj The final object for output
 * @param type $type_index The type index
 * @return string HTML of the business card;
 */

function prepareLoggedInMain($finalObj,$type_index)
{

    $html=  ItemSearch("Books", "Harry Potter");
    return $html;
}

/**
 * Prepare login screen html
 * 
 * @param type $login_url The login URL
 * @return string The HTML for login page
 */
function prepareLoggedOutMain($login_url)
{
    $html="<!DOCTYPE html>
<html lang=\"en-US\">
<head>
    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
    <title>Virtual Business Card Login Page</title>
    <link rel=\"stylesheet\" type=\"text/css\" href=\"css/login.css\" />
</head>
<body>
    <div class=\"alignCenter\">
        <h2><i>Welcome!</i></h2>
        <p>Please login to view your virtual business card</p>
        <a href=\"".$login_url."\"><img class=\"imgLogin\" src=\"images/fb_login.jpg\" alt=\"Facebook Login\" /></a>
    </div>
</body>
</html>"
;
    return $html;
}



/*
/**
 * Get the html object for list containing type of post and its count
 * 
 * 
 * @param type $finalObj The final object for output
 * @param type $type_index The type index
 * @return string The HTML string corresponding to the list


function getList($finalObj,$type_index)
{

$list=null;
 foreach ($type_index as $type_no=>$k)
{
$list.="<li class=\"floatLeft clear subList\" onclick=\"$('.dropdown').slideUp('slow');return false;\">".$k."<span class=\"floatRight indentFromRight\">".$finalObj[$k]."</span></li>";
}
return $list;
}

/**
 * Prepares HTML for business card based on the data retrived
 * 
 * @param type $finalObj The final object for output
 * @param type $type_index The type index
 * @return string HTML of the business card;


function prepareLoggedInMain($finalObj,$type_index)
{
$html="<!DOCTYPE html>

<html lang=\"en-US\">
<head>
    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
    <title>Virtual Business Card</title>
    <link rel=\"stylesheet\" type=\"text/css\" href=\"css/default.css\" />
    <script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js\"></script>
    <script type=\"text/javascript\" src=\"js/jquery.easing.1.3.js\"></script>
    <script type=\"text/javascript\" src=\"js/script.js\"></script>
</head>
<body>
    <div class=\"wrapperDiv\">
        <div class=\"alignCenter\">
            <div id=\"userName\" class=\"floatLeft\">".$finalObj['name']."</div>
            <div id=\"fbLogout\" class=\"floatLeft\">
            <a href=\"".$finalObj['logoutUrl']."\">
                    <img class=\"imgLogout\" src=\"images/facebook_logout.png\" alt=\"Facebook Logout\" /></a>
            </div>
        </div>
        <div class=\"clear\"></div>
        <div class=\"alignCenter leftIndent\">
            <p id=\"userLocation\">".$finalObj['location']."</p>
            <div id=\"Details\">
                <div class=\"floatLeft\">
                    <img id=\"profilePicture\" src=\"".$finalObj['image']."\" alt=\"Profile Picture\" />
                </div>
                <div class=\"floatRight otherDetails\">
                    <p class=\"fbLikes floatLeft\" style=\"margin-top: 3px\"><a href=\"#\" class=\"redPosts floatLeft\">Number of Likes</a></p>
                    <p class=\"countofItemsOuter redPosts floatLeft\">".$finalObj['likes']."</p>
                    <p class=\"fbComments floatLeft\" style=\"margin-top: 0px\"><a href=\"#\" class=\"orangePosts floatLeft\">Number of Comments</a></p>
                    <p class=\"countofItemsOuter floatLeft\">".$finalObj['comments']."</p>
                    <ul class=\"fbPostsUL\">
                        <li class=\"fbPosts\"><a href=\"#\" class=\"greenPosts floatLeft\">Number of Posts<br />
                            <span style=\"font-size: 12px\">Click to see count of different post types</span></a><span class=\"countofItemsOuter floatLeft\" style=\"margin-top:20px\">".$finalObj['posts']."</span></li>
                        <li class=\"dropdown clear\">
                            <ul>
                                ".getList($finalObj,$type_index)."
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>";
    return $html;
}

/**
 * Prepare login screen html
 * 
 * @param type $login_url The login URL
 * @return string The HTML for login page
 
function prepareLoggedOutMain($login_url)
{
    $html="<!DOCTYPE html>
<html lang=\"en-US\">
<head>
    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
    <title>Virtual Business Card Login Page</title>
    <link rel=\"stylesheet\" type=\"text/css\" href=\"css/login.css\" />
</head>
<body>
    <div class=\"alignCenter\">
        <h2><i>Welcome!</i></h2>
        <p>Please login to view your virtual business card</p>
        <a href=\"".$login_url."\"><img class=\"imgLogin\" src=\"images/fb_login.jpg\" alt=\"Facebook Login\" /></a>
    </div>
</body>
</html>"
;
    return $html;
}

*/
?>
