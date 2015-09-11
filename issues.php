<?php

if(isset($_POST['Submit']))
{
    
    $given_url = $_POST['url'];
    $input_url_array =  explode('/',$given_url);

    //setting up the default url as "https://github.com/Shippable/support/issues"
    if($given_url == NULL){
        $given_url = "https://github.com/Shippable/support/issues";
    }

    //if the given link is the link to the github repository and not issues, as given in the sample url
    if(sizeof($input_url_array)==4){ // as less than 4 would be an invalid link to a github repository
        $input_url_array[4] = "issues" ;
    }


    $url = "https://api.github.com/repos/".$input_url_array[3]."/".$input_url_array[4];
    $result = callGitHubAPI($url);
    $total_open_issues = $result["open_issues_count"];
    echo "<table border = '1' width =' 100%'><tr><td>Total number of open issues:</td><td>".$total_open_issues."</td></tr></table>";


   $time_last24hr = date('Y-m-d\TH:i:s.Z\Z', strtotime('-1 day', time()));
    $url = "https://api.github.com/repos/".$input_url_array[3]."/".$input_url_array[4]."/issues?since=".$time_last24hr;     
    $result = callGitHubAPI($url);
    $issues_last24hr = count($result);
    echo "<table border = '1' width =' 100%'><tr><td>Number of open issues that were opened in the last 24 hours:</td><td>".$issues_last24hr."</td></tr></table>";


    $time_7daysago = date('Y-m-d\TH:i:s.Z\Z', strtotime('-7 day', time()));
    $url = "https://api.github.com/repos/".$input_url_array[3]."/".$input_url_array[4]."/issues?since=".$time_7daysago;
    $result = callGitHubAPI($url);
    $issues_last7days = count($result);
    echo "<table border = '1' width =' 100%'><tr><td>Number of open issues that were opened more than 24 hours ago but less than 7 days ago:</td><td>".($issues_last7days-$issues_last24hr)."</td></tr></table>";


    echo "<table border = '1' width =' 100%'><tr><td>Number of open issues that were opened more than 7 days ago:</td><td>".($total_open_issues-$issues_last7days)."</td></tr></table>";
}       

function callGitHubAPI($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_USERAGENT, "userName");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Accept: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result=curl_exec($ch);
    curl_close($ch);
    $new_result=json_decode($result,true);
    return $new_result;
}

?>



