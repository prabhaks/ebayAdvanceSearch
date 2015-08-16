
<!DOCTYPE html>
<html>
    <head>
        <TITLE>Prabhakar's Homework6</TITLE>
<!--
<BODY>
This is for USC disclaimer
</BODY>
-->
    </head>
    <?php
   
        echo '<script type="text/javascript">';
        echo 'function validateForm() {
            var x = document.getElementById("kw").value;
            if (x == null || x == "") {
                alert("Please enter value for Key Words");
                document.getElementById("kw").focus();
                return false;
            }
            var min = document.getElementById("min").value;
            if (min !=null && min!=""){
                 if (isNaN(parseFloat(min)) || parseFloat(min)<0.0){
                     alert("Invalid Price Range. \'from $\' value should be integer greater than or equal to 0");
                     document.getElementById("min").focus();
                     return false;
                 }
            }
            var max = document.getElementById("max").value;
            if (max !=null && max!=""){
                 if (isNaN(parseFloat(max)) || parseFloat(max)<0.0){
                     alert("Invalid Price Range. \'to $\' value should be integer greater than or equal to 0");
                     document.getElementById("max").focus();
                     return false;
                 }
            }
            if (min !=null && min!="" && max!=null && max!=""){
                if (parseFloat(max)<parseFloat(min)){
                     alert("Invalid Price Range. \'to $\' value can not be less than \'from $\' value");
                    return false;
                }
            }
            x = document.getElementById("maxhandletime").value;
            if (x !=null && x!=""){
                 if (isNaN(parseInt(x))){
                     alert("Invalid Max handling time. Please enter number of days(integer) greater than or equal to 1");
                     document.getElementById("maxhandletime").focus();
                     return false;
                 }
                else if (parseInt(x)<=0){
                    alert("Invalid Max handling time. Number of days can not be less than 1");
                    document.getElementById("maxhandletime").focus();
                     return false;
                }
            }
            }';
echo 'function resetAll(){location.href="hw6.php";}';
echo '</script>';

    ?>
    <?php
       // $nameErr = $emailErr = $genderErr = $websiteErr = "";
        $keyword = $maxPrice = $minPrice = $new = $used = $good = $verygood = $acceptable = $results = "";
        $buyitnow = $auction = $classified = $returnao = $freeshiponly = $expeditedship = $maxHandleTime = "";
        $sortorder = "BestMatch"; $pagination = "5";
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            error_reporting(E_ALL);
            $keyword = $_POST["keyword"];
            $safekeyword = urlencode($keyword);  // Make the query URL-friendly
            $sortorder = $_POST["sortOrder"];
            $pagination = $_POST["paginationInput"];            
            // API request variables
            $endpoint = 'http://svcs.ebay.com/services/search/FindingService/v1';  // URL to call
            $version = '1.0.0';  // API version supported by your application
            $appid = 'Universi-7149-4cdf-b8f3-ec269cf38e0c';  // Replace with your own AppID
            $responsedata = 'XML';
            // define variables and set to empty values

            // Construct the findItemsByKeywords HTTP GET call 
            $apicall = "$endpoint?";
            $apicall .= "siteid=0";
            $apicall .= "&SECURITY-APPNAME=$appid";
            $apicall .= "&OPERATION-NAME=findItemsAdvanced";
            $apicall .= "&SERVICE-VERSION=$version";
            $apicall .= "&RESPONSE-DATA-FORMAT=$responsedata";
            $apicall .= "&keywords=$safekeyword";
            $apicall .= "&paginationInput.entriesPerPage=$pagination";
            $apicall .= "&sortOrder=$sortorder";
            
            $i = '0';
            if (empty($_POST["New"])) {
                $new = "";
            } else {
                $new = $_POST["New"];
            }
            if (empty($_POST["Used"])) {
                $used = "";
            } else {
                $used = $_POST["Used"];
            }
            if (empty($_POST["Good"])) {
                $good = "";
            } else {
                $good = $_POST["Good"];
            }
            if (empty($_POST["VeryGood"])) {
                $verygood = "";
            } else {
                $verygood = $_POST["VeryGood"];
            }
            if (empty($_POST["Acceptable"])) {
                $acceptable = "";
            } else {
                $acceptable = $_POST["Acceptable"];
            }
            if (!empty($new) || !empty($used) || !empty($verygood) || !empty($good) || !empty($acceptable)){
                $j = '0';
                $itemFilter = "&itemFilter[$i].name=Condition";
                if (!empty($new)){
                    $itemFilter .= "&itemFilter[$i].value[$j]=$new";
                    $j++;
                }
                if (!empty($used)){
                    $itemFilter .= "&itemFilter[$i].value[$j]=$used";
                    $j++;
                }
                if (!empty($verygood)){
                    $itemFilter .= "&itemFilter[$i].value[$j]=$verygood";
                    $j++;
                }
                if (!empty($good)){
                    $itemFilter .= "&itemFilter[$i].value[$j]=$good";
                    $j++;
                }
                if (!empty($acceptable)){
                    $itemFilter .= "&itemFilter[$i].value[$j]=$acceptable";
                    $j++;
                }
                $apicall .= "$itemFilter";
                $i++;
            }
            
            if (empty($_POST["BuyItNow"])) {
                $buyitnow = "";
            } else {
                $buyitnow = $_POST["BuyItNow"];
            }
            if (empty($_POST["Auction"])) {
                $auction = "";
            } else {
                $auction = $_POST["Auction"];
            }
            if (empty($_POST["Classified"])) {
                $classified = "";
            } else {
                $classified = $_POST["Classified"];
            }
            if (!empty($buyitnow) || !empty($auction) || !empty($classified)){
                 $j = '0';
                 $itemFilter = "&itemFilter[$i].name=ListingType";
                 if (!empty($buyitnow)){
                    $itemFilter .= "&itemFilter[$i].value[$j]=$buyitnow";
                    $j++;
                 }
                if (!empty($auction)){
                    $itemFilter .= "&itemFilter[$i].value[$j]=$auction";
                    $j++;
                 }
                if (!empty($classified)){
                    $itemFilter .= "&itemFilter[$i].value[$j]=$classified";
                    $j++;
                 }
                $apicall .= "$itemFilter";
                $i++;
            }
            
            if (empty($_POST["ReturnsAcceptedOnly"])) {
                $returnao = "";
            } else {
                $returnao = $_POST["ReturnsAcceptedOnly"];
                $itemFilter = "&itemFilter[$i].name=ReturnsAcceptedOnly";
                 $itemFilter .= "&itemFilter[$i].value=true";
                 $apicall .= "$itemFilter";
                 $i++;
            }
            
            if (empty($_POST["FreeShippingOnly"])) {
                $freeshiponly = "";
            } else {
                $freeshiponly = $_POST["FreeShippingOnly"];
                $itemFilter = "&itemFilter[$i].name=FreeShippingOnly";
                 $itemFilter .= "&itemFilter[$i].value=true";
                 $apicall .= "$itemFilter";
                 $i++;
            }
            if (empty($_POST["ExpeditedShippingType"])) {
                $expeditedship = "";
            } else {
                $expeditedship = $_POST["ExpeditedShippingType"];
                $itemFilter = "&itemFilter[$i].name=ExpeditedShippingType";
                 $itemFilter .= "&itemFilter[$i].value=Expedited";
                 $apicall .= "$itemFilter";
                 $i++;
            }
           
            if (empty($_POST["maxPrice"])) {
                $maxPrice = "";
            } else {
                $maxPrice = $_POST["maxPrice"];
                 $itemFilter = "&itemFilter[$i].name=MaxPrice";
                 $itemFilter .= "&itemFilter[$i].value=$maxPrice";
                 $apicall .= "$itemFilter";
                 $i++;
            }
            
            if (empty($_POST["minPrice"])) {
                $minPrice = "";
            } else {
                $minPrice = $_POST["minPrice"];
                 $itemFilter = "&itemFilter[$i].name=MinPrice";
                 $itemFilter .= "&itemFilter[$i].value=$minPrice";
                 $apicall .= "$itemFilter";
                 $i++;
            }
            if (empty($_POST["MaxHandlingTime"])) {
                $maxHandleTime = "";
            } else {
                $maxHandleTime = $_POST["MaxHandlingTime"];
                $itemFilter = "&itemFilter[$i].name=MaxHandlingTime";
                 $itemFilter .= "&itemFilter[$i].value=$maxHandleTime";
                 $apicall .= "$itemFilter";
                 $i++;
            }   
            //http://svcs.ebay.com/services/search/FindingService/v1?siteid=0&SECURITY-APPNAME=Universi-7149-4cdf-b8f3-ec269cf38e0c&OPERATION-NAME=findItemsAdvanced&SERVICE-VERSION=1.0.0&RESPONSE-DATA-FORMAT=XML&keywords=harry%20potter&paginationInput.entriesPerPage=5&sortOrder=CurrentPriceLowest&itemFilter[0].name=ListingType&itemFilter[0].value[0]=FixedPrice&itemFilter[0].value[1]=Auction
            // http://svcs.ebay.com/services/search/FindingService/v1?siteid=0&SECURITY-APPNAME=Universi-7149-4cdf-b8f3-ec269cf38e0c&OPERATION-NAME=findItemsAdvanced&SERVICE-VERSION=1.0.0&RESPONSE-DATA-FORMAT=XML&keywords=harry+potter&paginationInput.entriesPerPage=5&sortOrder=BestMatch&itemFilter[0].name=ExpeditedShippingType&itemFilter[0].value=true
            
            // Load the call and capture the document returned by eBay API
            //echo "<script>alert(\"$apicall\")</script>";
            $resp = simplexml_load_file($apicall);

            // Check to see if the request was successful, else print an error
            if ($resp->ack == "Success") {
              $results = '';
             if ($resp->paginationOutput->totalEntries != '0') {
                 $num_res = $resp->paginationOutput->totalEntries;
                 $results .= "<tr><td style=\"text-align:center\"><h3>$num_res Results for <i>$keyword</i></h3></td></tr>";
              // If the response was loaded, parse it and build links  
              foreach($resp->searchResult->item as $item) {
                $pic = $item->galleryURL;
                $link = $item->viewItemURL;
                $title = $item->title;
                $cond = $item->condition->conditionDisplayName;
                $buyformat = "Buy It Now";
                if ($item->listingInfo->listingType == "Auction"){
                    $buyformat = "Auction";
                }
                else if ($item->listingInfo->listingType == "Classified"){
                    $buyformat = "Classified Ad";
                }
                $free_ship = "Shipping Not Free";
                if ($item->shippingInfo->shippingServiceCost == '0.0'){
                    $free_ship = "FREE Shipping";
                }
                
                $ship_time = $item->shippingInfo->handlingTime;
                $location = $item->location;
                $price = $item->sellingStatus->convertedCurrentPrice;
                $temp = '';
                $temp = "<tr><td style=\"border:1px solid #000;\"><div style=\"width:800\"><div style=\"float:left\"><img src=\"$pic\" height=190 width=180></div> <div style=\"margin-left:190px;width:610px\"><div style=\"margin-top:5px;width:600px\"><a href=\"$link\">$title</a></div>";
                  if ($item->topRatedListing == 'true'){
                      $temp .= "<div style=\"float:left;margin-top:15px;width:150px\"><strong>Condition:</strong> $cond</div>";
                      $temp .= "<div><img src=\"http://cs-server.usc.edu:45678/hw/hw6/itemTopRated.jpg\" height=55 width=65></div>";
                  }
                  else{
                      $temp .= "<div style=\"width:200px;height:40px\"><strong>Condition:</strong> $cond</div>";
                  }
                  $temp .= "<div style=\"height:30px;font-weight:bold\">$buyformat</div>";
                  if ($item->returnsAccepted == 'true'){
                      $temp .= "<div>Seller accept return</div>";
                  }
        $temp .= "<div style=\"height:42px;\"><div style=\"float:left\">$free_ship -- &nbsp;</div>";
                  
                 if ($item->shippingInfo->expeditedShipping == 'true'){
                     $temp .= "<div style=\"float:left\">Expedited Shipping available -- &nbsp;</div>";
                 }
                  $price_ship = "<strong>Price: $" . $price;
                  if ($item->shippingInfo->shippingServiceCost > '0.0'){
                      $ship_cost = $item->shippingInfo->shippingServiceCost;
                      $price_ship .= " (+ $" . $ship_cost . " for shipping)"; 
                    
                    }
                  $price_ship .= "</strong> &nbsp;<i>From $location</i>" ;
                  $temp .= "<div style=\"float:left;\">Handled for shipping in $ship_time day(s)</div><br></div><div style=\"float:left;width:650px;\">$price_ship</div></div></div></td></tr>";
                // For each SearchResultItem node, build a link and append it to $results
                  $results .= "$temp";
              }
             }
            }
            // If the response does not indicate 'Success,' print an error
            else {
              $results  = "<h3>Oops! Something went wrong. Please try searching this query after sometime</h3>";
            }
        }
    ?>
    
<body style="margin: 0 auto;padding: 2em 2em 4em;max-width: 800px;">
<div style="border:1px solid #000;height:550px;width:800px">

    <div><div style="float:left;width:400px;text-align:right;margin-top:15px">
    <img src="http://cs-server.usc.edu:45678/hw/hw6/ebay.jpg" height=80px width=90px/>
</div>
<div style="font-weight:bold;float:left;font-size:25px;margin-top:35px;margin-left:5px">Shopping   
        </div></div>
<div style="border:1px solid #000;height:440px;margin:100px 10px auto 10px">
    <div style="margin:10px 10px 10px 10px">
        
    <form name="form1" id="form1" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" onsubmit=" return validateForm();">
        
    <div style="float:left;width:10em;font-weight:bold">Key Words*:</div> <div><input id="kw" type="text" name="keyword" value="<?php echo $keyword;?>" style="width:590px;"></div>
    <hr>
        
    <div style="float:left;width:10em;font-weight:bold">Price Range:</div> <div>from $ <input id="min" type="text" name="minPrice" value="<?php echo $minPrice;?>" style="width:100px;"> to $  <input id="max" type="text" name="maxPrice" value="<?php echo $maxPrice;?>" style="width:100px;"></div><hr>
        
    <div style="float:left;width:10em;font-weight:bold">Condition:</div> <div><input type="checkbox" name="New" value="1000" <?php if (!empty($new)) echo "checked";?>>New</input> &nbsp;&nbsp; <input type="checkbox" name="Used" value="3000" <?php if (!empty($used)) echo "checked";?>>Used</input> &nbsp;&nbsp; <input type="checkbox" name="VeryGood" value="4000" <?php if (!empty($verygood)) echo "checked";?>>Very Good</input> &nbsp;&nbsp; <input type="checkbox" name="Good" value="5000" <?php if (!empty($good)) echo "checked";?>>Good</input> &nbsp;&nbsp; <input type="checkbox" name="Acceptable" value="6000" <?php if (!empty($acceptable)) echo "checked";?>>Acceptable</input></div>
        <hr>
    
        <div style="float:left;width:10em;font-weight:bold">Buying formats:</div> <div><input type="checkbox" name="BuyItNow" value="FixedPrice" <?php if (!empty($buyitnow)) echo "checked";?>>Buy It Now</input> &nbsp;&nbsp; <input type="checkbox" name="Auction" value="Auction" <?php if (!empty($auction)) echo "checked";?>>Auction</input> &nbsp;&nbsp; <input type="checkbox" name="Classified" value="Classified" <?php if (!empty($classified)) echo "checked";?>>Classified Ads</input></div>
    <hr>

        <div style="float:left;width:10em;font-weight:bold">Seller:</div> <div><input type="checkbox" name="ReturnsAcceptedOnly" value="ReturnsAcceptedOnly" <?php if (!empty($returnao)) echo "checked";?>>Return Accepted</input></div>
    <hr>

        <div style="float:left;width:10em;font-weight:bold">Shipping:</div> <div><input type="checkbox" name="FreeShippingOnly" value="FreeShippingOnly" <?php if (!empty($freeshiponly)) echo "checked";?>>Free Shipping</input></div>
 <div style="text-align:right;width:365px"><input type="checkbox" name="ExpeditedShippingType" value="ExpeditedShippingType" <?php if (!empty($expeditedship)) echo "checked";?>>Expedited shipping available</input></div>
 <div style="text-align:right;width:440px">Max handling time (days): <input type="text" id="maxhandletime" name="MaxHandlingTime" value="<?php echo $maxHandleTime;?>" style="width:100px;"></input></div>
    <hr>

<div style="float:left;width:10em;font-weight:bold">Sort by:</div> <div><select name="sortOrder" style="width:200px"><option value="BestMatch" <?php if (isset($sortorder) && $sortorder == "BestMatch") echo "selected";?>>Best Match</option><option value="CurrentPriceHighest" <?php if (isset($sortorder) && $sortorder == "CurrentPriceHighest") echo "selected";?>>Price: highest first
</option><option value="CurrentPriceLowest" <?php if (isset($sortorder) && $sortorder == "CurrentPriceLowest") echo "selected";?>>Price: lowest first</option><option value="PricePlusShippingHighest" <?php if (isset($sortorder) && $sortorder == "PricePlusShippingHighest") echo "selected";?>>Price + Shipping: highest first</option><option value="PricePlusShippingLowest" <?php if (isset($sortorder) && $sortorder == "PricePlusShippingLowest") echo "selected";?>>Price + Shipping: lowest first</option></select></div>
    <hr>

        <div style="float:left;width:10em;font-weight:bold">Results Per Page:</div> <div><select name="paginationInput" style="width:50px"><option value="5" <?php if (isset($pagination) && $pagination == "5") echo "selected";?>>5</option><option value="10" <?php if (isset($pagination) && $pagination == "10") echo "selected";?>>10</option><option value="15" <?php if (isset($pagination) && $pagination == "15") echo "selected";?>>15</option><option value="20" <?php if (isset($pagination) && $pagination == "20") echo "selected";?>>20</option></select></div>

        <div style="margin-top:10px;text-align:right;width:750px">
        <input type="reset" value="clear" onclick="resetAll();"/>
        <input type="submit" name="submit" value="search"/>
        </div>
        </form>
    </div>
    </div>
<br>
<table id="res">
<tr>
  <td>
     <?php if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if (!empty($results)){
            echo $results;
            echo '<script>location.href="hw6.php#res"</script>';
        
        } else {echo "<div style=\"width:800px;text-align:center\"><h3>No results found</h3></div>";}
      }?>
  </td>
</tr>
</table>
    
</div>
    </body>
 </html>
