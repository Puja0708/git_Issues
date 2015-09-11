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
      <div class="col-sm-10">
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
    echo "<table border = '1' width ='80%' align='center' class='table table-striped'><tr><td>Total number of open issues:</td><td>".$total_open_issues."</td></tr></table>";


   $time_last24hr = date('Y-m-d\TH:i:s.Z\Z', strtotime('-1 day', time()));
    $url = "https://api.github.com/repos/".$input_url_array[3]."/".$input_url_array[4]."/issues?since=".$time_last24hr;     
    $result = callGitHubAPI($url);
    $issues_last24hr = count($result);
    echo "<table border = '1' width ='80%' align='center' class='table table-striped'><tr><td>Number of open issues that were opened in the last 24 hours:</td><td>".$issues_last24hr."</td></tr></table>";


    $time_7daysago = date('Y-m-d\TH:i:s.Z\Z', strtotime('-7 day', time()));
    $url = "https://api.github.com/repos/".$input_url_array[3]."/".$input_url_array[4]."/issues?since=".$time_7daysago;
    $result = callGitHubAPI($url);
    $issues_last7days = count($result);
    echo "<table border = '1' width ='80%' align='center' class='table table-striped'><tr><td>Number of open issues that were opened more than 24 hours ago but less than 7 days ago:</td><td>".($issues_last7days-$issues_last24hr)."</td></tr></table>";


    echo "<table border = '1' width ='80%' align='center' class='table table-striped'><tr><td>Number of open issues that were opened more than 7 days ago:</td><td>".($total_open_issues-$issues_last7days)."</td></tr></table>";
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





