<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>GitHub Issues</title>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

</head>
<body>

<div class="container">
  <h2>Github Issue tracker</h2>
  <a href = "index.html"> Go Back to Home Page </a> <br/><br/>
  <form class="form-horizontal" role="form" action="" method="POST">
    <div class="form-group">
      <label class="control-label col-sm-2" for="email">Github Repo : </label>
      <div class="col-sm-10">''
        <input type="text" class="form-control" id="url" name="url" placeholder="Try another url" pattern="https://github.com/[A-Za-z0-9._]*/[A-Za-z0-9._]*/issues"> 
      </div>
    </div>
    <div class="form-group">        
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-success" name="Submit">Submit</button>
      </div>
    </div>
  </form>
</div>
</body>
</html>


<?php

if(isset($_POST['Submit']))
{
    
    $given_url = $_POST['url'];
    $url_arr =  explode('/',$given_url);

    echo "the data for the repository : <b><u>".$given_url."</u></b> are <br/>:" ;

    //setting up the default url as "https://github.com/Shippable/support/issues"
    if($given_url == NULL){
        $given_url = "https://github.com/Shippable/support/issues";
    }

    //if the given link is the link to the github repository and not issues, as given in the sample url
    if(sizeof($url_arr)==4){ // as less than 4 would be an invalid link to a github repository
        $url_arr[4] = "issues" ;
    }


    $url = "https://api.github.com/repos/".$url_arr[3]."/".$url_arr[4];
    $final_res = callGitHubAPI($url);
    $total_open_issues = $final_res["open_issues_count"];
    echo "<table border = '1' width ='80%' align='center' class='table table-striped'><tr><td>Total number of open issues:</td><td>".$total_open_issues."</td></tr></table>";


   $time_last_24 = date('Y-m-d\TH:i:s.Z\Z', strtotime('-1 day', time()));
    $url = "https://api.github.com/repos/".$url_arr[3]."/".$url_arr[4]."/issues?since=".$$time_last_24;     
    $final_res = callGitHubAPI($url);
    $issues_last_24 = count($final_res);
    echo "<table border = '1' width ='80%' align='center' class='table table-striped'><tr><td>Number of open issues that were opened in the last 24 hours:</td><td>".$issues_last_24."</td></tr></table>";


    $time_7 = date('Y-m-d\TH:i:s.Z\Z', strtotime('-7 day', time()));
    $url = "https://api.github.com/repos/".$url_arr[3]."/".$url_arr[4]."/issues?since=".$time_7;
    $final_res = callGitHubAPI($url);
    $issues_7_days = count($final_res);
    echo "<table border = '1' width ='80%' align='center' class='table table-striped'><tr><td>Number of open issues that were opened more than 24 hours ago but less than 7 days ago:</td><td>".($issues_7_days-$issues_last_24)."</td></tr></table>";


    echo "<table border = '1' width ='80%' align='center' class='table table-striped'><tr><td>Number of open issues that were opened more than 7 days ago:</td><td>".($total_open_issues-$issues_7_days)."</td></tr></table>";
}       

function callGitHubAPI($url)
{
    $str = curl_init();
    curl_setopt($str, CURLOPT_URL,$url);
    curl_setopt($str, CURLOPT_USERAGENT, "userName");
    curl_setopt($str, CURLOPT_HTTPHEADER, array( 'Accept: application/json'));
    curl_setopt($str, CURLOPT_RETURNTRANSFER, true);
    $res=curl_exec($str);
    curl_close($str);
    $final_res=json_decode($res,true);
    return $final_res;
}

?>





