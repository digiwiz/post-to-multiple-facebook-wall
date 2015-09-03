<?php
    include_once "fbaccess.php";
    $limit = 5000;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Publish to Multiple wall or timeline using Facebook batch request</title>

<link rel="stylesheet" href="css/anytime.5.0.5.css" />

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

<script src="js/anytime.5.0.5.js"></script>

<style type="text/css">
	html,body { margin:0; padding:0; font-family:tahoma,verdana,arial,sans-serif; text-align:center;}
	#top-bar { position:fixed; top:0; left:0; z-index:999; width:100%; height:65px; }
	#topbar-inner { height:90px; background:#fecb01; text-align:center; }
	#topbar-inner a { color:#FFFFFF; font-size:20px; text-decoration:none; vertical-align:bottom; }
	.input { border:1px solid #006; background:#ffc; width:300px; font-size:small; font-family:courier; }
	img {border: none;}
	p { text-align:left; }
	
	a:link {
		text-decoration: none;
	}

	a:visited {
		text-decoration: none;
	}

	a:hover {
		text-decoration: underline;
	}

	a:active {
		text-decoration: underline;
	}
	
	.store_number {
		font-family: Arial;
		color: #dd0505;
		font-size: 12px;
		padding: 5px 10px 5px 10px;
		text-decoration: none;
	}
	
	.store_name {
		font-family: Arial;
		color: #0505dd;
		font-size: 12px;
		padding: 5px 10px 5px 10px;
		text-decoration: none;
	}
	
	.page_likes {
		background: #3b5998;
		background-image: -webkit-linear-gradient(top, #3b5998, #2980b9);
		background-image: -moz-linear-gradient(top, #3b5998, #2980b9);
		background-image: -ms-linear-gradient(top, #3b5998, #2980b9);
		background-image: -o-linear-gradient(top, #3b5998, #2980b9);
		background-image: linear-gradient(to bottom, #3b5998, #2980b9);
		-webkit-border-radius: 6;
		-moz-border-radius: 6;
		border-radius: 6px;
		font-family: Arial;
		color: #ffffff;
		font-size: 12px;
		padding: 5px 10px 5px 10px;
		text-decoration: none;
	}
	.page_notifications {
		background: #ff0000;
		background-image: -webkit-linear-gradient(top, #ff0000, #b80000);
		background-image: -moz-linear-gradient(top, #ff0000, #b80000);
		background-image: -ms-linear-gradient(top, #ff0000, #b80000);
		background-image: -o-linear-gradient(top, #ff0000, #b80000);
		background-image: linear-gradient(to bottom, #ff0000, #b80000);
		-webkit-border-radius: 6;
		-moz-border-radius: 6;
		border-radius: 6px;
		font-family: Arial;
		color: #ffffff;
		font-size: 12px;
		padding: 5px 10px 5px 10px;
		text-decoration: none;
	}
	.page_messages {
		background: #ff8400;
		background-image: -webkit-linear-gradient(top, #ff8400, #b05e06);
		background-image: -moz-linear-gradient(top, #ff8400, #b05e06);
		background-image: -ms-linear-gradient(top, #ff8400, #b05e06);
		background-image: -o-linear-gradient(top, #ff8400, #b05e06);
		background-image: linear-gradient(to bottom, #ff8400, #b05e06);
		-webkit-border-radius: 6;
		-moz-border-radius: 6;
		border-radius: 6px;
		font-family: Arial;
		color: #ffffff;
		font-size: 12px;
		padding: 5px 10px 5px 10px;
		text-decoration: none;
	}
	
	
	
	
	
</style>
<script language='JavaScript'>
	function checkedAll () {
		var argv = checkedAll.arguments;
		checked = document.getElementById('myform').elements[argv[0]-1].checked;
		for (var i = argv[0]; i < document.getElementById('myform').elements.length && i < argv[1]; i++) {
			document.getElementById('myform').elements[i].checked = checked;
		}
	}
</script>





</head>
<body style="padding-top:70px;" >
<div id="top-bar"> 
	<div id="topbar-inner">
	<center><table style="width:950px;" >
		<tr>
			<td><a href="index.php" ><img src="images/logo_en.jpg" /></a></td>
			<td><a href="http://25labs.com/tutorial-post-to-multiple-facebook-wall-or-timeline-in-one-go-using-graph-api-batch-request/" >Click here to read the tutorial on 25labs.com</a></td>
			<td><?php if ($user) echo '<a href="'.$logoutUrl.'">Logout</a>'; else echo '<a href="'.$loginUrl.'">Login</a>'; ?></td></tr>
		</table></center>
	</div>
</div>
<h2>Post to Multiple Walls / Timelines (Pages, Groups or Friends)</h2>
</br>
</br>
</br>
</br>

<?php if(!$user) { ?><div style="padding-top:150px;" ><a href="<?=$loginUrl?>"><img src="images/f-connect.png" alt="Connect to your Facebook Account"/></a><br/>This website will <b>NOT</b> post anything to your wall or like any page automatically.</div><?php } else {?>



<form id="myform" action="" method="post">
<center><table>
	<tr><td>Message</td><td><textarea class="input" name="message" ></textarea></td>
		<td rowspan="7"><input type="image" name="submit" src="images/submitbutton.jpg" ></td></tr>
	<tr><td>Link</td><td><input class="input" type="text" name="link" value="" /></td></tr>
	<tr><td>Picture</td><td><input class="input" type="text" name="picture" value="" /></td></tr>
	<tr><td>Name</td><td><input class="input" type="text" name="name" value="" /></td></tr>
	<tr><td>Caption</td><td><input class="input" type="text" name="caption" value="" /></td></tr>

	<tr><td>Scheduled</td><td><input type="text" id="dateTimeField" name="scheduled" ></td></tr>
       <script>AnyTime.picker( "dateTimeField");</script>

	
	
	<tr><td>Description</td><td><textarea class="input" name="description" rows="8" ></textarea></td></tr>
	
</table>

<?php
	if(isset($flag) && $flag==1) { echo "<div style='border:2px solid red;width:600px;background:#f99' >Please select atleast one Page, Group, or Friend</div>"; $flag=0; }
	elseif(isset($flag) && $flag==2) { echo "<div style='border:2px solid red;width:600px;background:#f99' >Please enter a message, Link, or Picture</div>"; $flag=0; }
	elseif(isset($multiPostResponse)) echo "<div style='border:2px solid green;width:600px;background:#cfc' >Successfully posted to the selected walls</div>"; ?>
</br></br>

<table>

<?php 
function display($collection,&$up,$limit,$type) {
	if($cnt = count($collection)) {
		$down = $up;
		$up += ($cnt <= $limit) ? $cnt : $limit;
		?>
		<tr><th colspan="2">
		<?php if($type == 'pages') echo "Pages:";
		elseif($type == 'groups') echo "Groups:";
		elseif($type == 'ON') echo "Ontario:";
		elseif($type == 'QC') echo "Quebec:";
		elseif($type == 'AB') echo "Alberta:";
		elseif($type == 'BC') echo "British Columbia:";
		elseif($type == 'SK') echo "Saskatchewan:";
		elseif($type == 'MB') echo "Manitoba:";
		elseif($type == 'NB') echo "New Brunswik:";
		elseif($type == 'NS') echo "Nova Scotia:";
		elseif($type == 'PE') echo "PEI:";
		else echo "Friends:"; ?>

		</th><td><input type='checkbox' name='checkall' onclick='checkedAll(<?php echo $down.','.$up++; ?>);'>Select All</td></tr>
		<tr><td><br/></td></tr>
		<?php $i=1;
		
		foreach($collection as $page) {

//			$name = preg_replace('/Tigre Géant /', '', preg_replace('/Giant Tiger /', '', $page['name'], 1), 1);
			$name = substr( ( preg_replace('/Tigre Géant /', '', preg_replace('/Giant Tiger /', '', $page['name'], 1), 1) ) , 0, 20);
//			$storeNumber = substr( ( preg_replace('/Tigre Géant /', '', preg_replace('/Giant Tiger /', '', $page['description'], 1), 1) ) , 0, 4);
			$storeNumber = substr( ( preg_replace('/Tigre Géant /', '', preg_replace('/Giant Tiger /', '', $page['description'], 1), 1) ) , 0, 20);
			$province = $page['location']['state'];
			$link = $page['link'];
			$id = $page['id'];

			if(!($i+2)%3) echo "<tr>";

			echo "<td width='250'><input type='checkbox' name='id_$id' value='$id' />";
//			echo "<a href='" . $link . "'>". $storeNumber. " " .$name. " " . " " . $page['location']['city'] . " [" .$page['likes']. "]" . "</a>" . "</td>";
			echo "<a href='" . $link . "'><span class='store_number'>". $storeNumber. " " ."</span><span class='store_name'>" .$name. "</span>";
			if($page['likes'] > 0)
				echo "<span class='page_likes'>" .$page['likes']. "</span>"; 
			if($page['unread_message_count'] > 0)					
				echo "<span class='page_messages'>" .$page['unread_message_count']. "</span>";
			if($page['unread_notif_count'] > 0)
				echo "<span class='page_notifications'>" .$page['unread_notif_count']. "</span>". "</a>" . "</td>";

			if(!($i%4)) echo "</tr>";
			if($i++ == $limit) break;	
		
		}
	}?>
	<tr><td><br/><br/></td></tr>
	<?php
}

$up=8; // use to add fields to form seems to be an offset for the java script


// Sort Array by Store Number in $page['description'] which we are using long description in Facebook page
foreach($pages['data'] as $page) {
	$storeNumbers[] = intval(    substr((preg_replace('/Tigre Géant #/', '', preg_replace('/Giant Tiger #/', '', $page['description'], 1), 1)) ,0 ,3)  );
}
array_multisort($storeNumbers,SORT_ASC,$pages['data']);

foreach($pages['data'] as $page) {
	if($page['location']['state'] == 'ON' and  $page['id'] != "96000832822" and $page['id'] != "187361710529" and intval(    substr((preg_replace('/Tigre Géant #/', '', preg_replace('/Giant Tiger #/', '', $page['description'], 1), 1)) ,0 ,3)  ) <100 ) $pagesON[] = $page;
	if($page['location']['state'] == 'ON' and  $page['id'] != "96000832822" and $page['id'] != "187361710529" and intval(    substr((preg_replace('/Tigre Géant #/', '', preg_replace('/Giant Tiger #/', '', $page['description'], 1), 1)) ,0 ,3)  ) >99 ) $pagesONB[] = $page;
	if($page['location']['state'] == 'QC' and  $page['id'] != "187361710529" ) $pagesQC[] = $page;
	if($page['location']['state'] == 'AB') $pagesAB[] = $page;
	if($page['location']['state'] == 'BC') $pagesBC[] = $page;
	if($page['location']['state'] == 'SK') $pagesSK[] = $page;
	if($page['location']['state'] == 'MB') $pagesMB[] = $page;
	if($page['location']['state'] == 'NB') $pagesNB[] = $page;
	if($page['location']['state'] == 'NS') $pagesNS[] = $page;
	if($page['location']['state'] == 'PE') $pagesPE[] = $page;
}

display($pagesON,$up,$limit,'ON A');
display($pagesONB,$up,$limit,'ON B');
display($pagesQC,$up,$limit,'QC');
display($pagesAB,$up,$limit,'AB');
display($pagesBC,$up,$limit,'BC');
display($pagesSK,$up,$limit,'SK');
display($pagesMB,$up,$limit,'MB');
display($pagesNB,$up,$limit,'NB');
display($pagesNS,$up,$limit,'NS');
display($pagesPE,$up,$limit,'PE');
//display($pages['data'],$up,$limit,'pages');
//display($groups['data'],$up,$limit,'groups');
//display($friends_list['data'],$up,$limit,'friends');
?>

</table></center>
</form>
<br/><br/><br/>
<?php } ?>
</body>
</html>