<?
	$adminpass = "q35153999";
	
	session_start();
	error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
	$sv_connection = mysql_connect("localhost", "root", "");
	$db_connection = mysql_select_db ("thaumasolver3000");

	if ($_POST["action"] == "additem".$adminpass) {
	
		if ($_POST["name"] == "") {
			echo "Missing Name.<br>";
		} else
		if ($_POST["pic"] == ""){ 
			echo "Missing Picture.<br>";
		} else {
	
			$insert = "INSERT INTO `items` (`id` ,`name` ,`pic`) VALUES (NULL , '". mysql_real_escape_string($_POST["name"])."', '".$_POST["pic"]."')";
			if (mysql_query($insert)){
				echo "Item added. (".$_POST["name"].")";
			} else {
				echo "Error inserting record: ".mysql_error();
			} 
		}
	}

	if ($_POST["action"] == "addaspect".$adminpass) {
		if ($_POST["name"] == "") {
			echo "Missing Name.<br>";
		} else
		if ($_POST["pic"] == ""){ 
			echo "Missing Picture.<br>";
		} else
		if (($_POST["item1"] == 0 || $_POST["item1a"] == "") || ($_POST["item2"] > 0 && $_POST["item2a"] == "") || ($_POST["item3"] > 0 && $_POST["item3a"] == "") || ($_POST["item4"] > 0 && $_POST["item4a"] == "") || ($_POST["item5"] > 0 && $_POST["item5a"] == "") || ($_POST["item6"] > 0 && $_POST["item6a"] == "") || ($_POST["item7"] > 0 && $_POST["item7a"] == "") || ($_POST["item8"] > 0 && $_POST["item8a"] == "")) {
			echo "Missing Value.<br>";
		} else {
			$insert = "INSERT INTO `aspects` (`id` ,`name` ,`pic`, `item1`, `item1a`, `item2`, `item2a`, `item3`, `item3a`, `item4`, `item4a`, `item5`, `item5a`, `item6`, `item6a`, `item7`, `item7a`, `item8`, `item8a`) VALUES (NULL , '". mysql_real_escape_string($_POST["name"])."', '".$_POST["pic"]."', '".$_POST["item1"]."', '".$_POST["item1a"]."', '".$_POST["item2"]."', '".$_POST["item2a"]."', '".$_POST["item3"]."', '".$_POST["item3a"]."', '".$_POST["item4"]."', '".$_POST["item4a"]."', '".$_POST["item5"]."', '".$_POST["item5a"]."', '".$_POST["item6"]."', '".$_POST["item6a"]."', '".$_POST["item7"]."', '".$_POST["item7a"]."', '".$_POST["item8"]."', '".$_POST["item8a"]."')";
			if (mysql_query($insert)){
				echo "Aspect added. (".$_POST["name"].")";
			} else {
				echo "Error inserting record: ".mysql_error();
			} 
		}
	}

	if ($_POST["action"] == "addrecipe".$adminpass) {
		if ($_POST["name"] == "") {
			echo "Missing Name.<br>";
		} else
		if (($_POST["aspect1"] == 0 || $_POST["aspect2"] == 0)) {
			echo "Missing Aspects.<br>";
		} else {
			$insert = "INSERT INTO `recipes` (`id`, `name`, `tier`, `aspect1`, `aspect2`, `aspect3`, `aspect4`, `aspect5`, `aspect6`, `aspect7`, `aspect8`) VALUES (NULL , '". mysql_real_escape_string($_POST["name"])."', '".$_POST["tier"]."', '".$_POST["aspect1"]."', '".$_POST["aspect2"]."', '".$_POST["aspect3"]."', '".$_POST["aspect4"]."', '".$_POST["aspect5"]."', '".$_POST["aspect6"]."', '".$_POST["aspect7"]."', '".$_POST["aspect8"]."')";
			if (mysql_query($insert)){
				echo "Recipe added. (".$_POST["name"].")";
			} else {
				echo "Error inserting record: ".mysql_error();
			} 
		}
	}

	
	$itemlist = array();
	$aspectlist = array();
	$recipelist = array();

	$select = "SELECT * FROM `aspects` order by name";
	$result = mysql_query($select);
	while ($row = mysql_fetch_assoc($result)) {
		$aspectlist[$row["id"]] = $row;
	}		

	$select = "SELECT * FROM `items` order by name";
	$result = mysql_query($select);
	while ($row = mysql_fetch_assoc($result)) {
		$itemlist[$row["id"]] = $row;
		$itemlist[$row["id"]]["aspects"] = getAspects($row["id"]);
	}		

	$select = "SELECT * FROM `recipes`";
	$result = mysql_query($select);
	while ($row = mysql_fetch_assoc($result)) {
		$recipelist[$row["id"]] = $row;
	}		

	
	function getAspects($id){
		global $aspectlist;
		$aspects = "";
		$i = 0;
		foreach ($aspectlist as $key => $value) {
			if ($value["item1"] == $id || $value["item2"] == $id || $value["item3"] == $id || $value["item4"] == $id || $value["item5"] == $id || $value["item6"] == $id || $value["item7"] == $id || $value["item8"] == $id) {
				if ($i > 0) $aspects .= " ";
				$aspects .= "<img src='icons_aspects/".$value["pic"]."' width='32' height='32' />";
				//$aspects .= $value["name"];
				$i++;
			}
		}
		return $aspects;
	}
		
	function additems(){
		global $itemlist;
		echo '<option value="0">None</option>';
		foreach ($itemlist as $key => $value) {
			echo '<option value="'.$key.'">'.$value["name"].'</option>';
		}
	}

	function addaspects(){
		global $aspectlist;
		echo '<option value="0">None</option>';
		foreach ($aspectlist as $key => $value) {
			echo '<option value="'.$key.'">'.$value["name"].'</option>';
		}
	}
	
	
?>
<html>
<head>
<title>Thaumasolver 3000</title>

    <link rel="stylesheet" type="text/css" href="css/tooltipster.css" />
    <link rel="stylesheet" type="text/css" href="css/tables.css" />
	
    <script type="text/javascript" src="js/jquery-2.0.0.min.js"></script>
    <script type="text/javascript" src="js/jquery.tooltipster.min.js"></script>
	
	<script>
	
		var aspectpictures = new Array();
		<?
		foreach ($aspectlist as $key => $value) {
			echo "aspectpictures[".$key."] = 'icons_aspects/".$value["pic"]."';\n\t\t";
		}
		?>
		
		function initTooltips(){
            $('.tooltip').tooltipster({
			   animation: 'grow',
			   delay: 20,
			   theme: '.tooltipster-default',
			   touchDevices: true,
			   position: 'top-left',
			   offsetY: -10,
			   trigger: 'hover'
			});
		}
		
        $(document).ready(function() {
			initTooltips();
			//$( "div:nth-child(3)" ).hide();
        });
		
		function updateIngredients(){
			$.post("ajax.php", {action:"getResults", v1:$("#a1").val(), v2:$("#a2").val(), v3:$("#a3").val(), v4:$("#a4").val(), v5:$("#a5").val(), v6:$("#a6").val(), v7:$("#a7").val(), v8:$("#a8").val()}, function(data){
				if(data.length > 0) {
					document.getElementById("results").innerHTML = data;
					initTooltips();
				}
			});
		}
		
		function aspectChanged(sel,ai){
			doc = document.getElementById('aspect'+ai);
			doc.style.backgroundImage = "url("+aspectpictures[sel.value]+")";
			updateIngredients();
		}
		
		function setOption(element, value) {
			var selectElement = document.getElementById(element)
			var options = selectElement.options;
			for (var i = 0, optionsLength = options.length; i < optionsLength; i++) {
				if (options[i].value == value) {
					selectElement.selectedIndex = i;
					return true;
				}
			}
			return false;
		}
		
		function setMagic(v1,v2,v3,v4,v5,v6,v7,v8){
			//alert("getstriggered "+v1+"-"+v2+"-"+v3+"-"+v4+"-"+v5+"-"+v6+"-"+v7+"-"+v8);
			setOption("a1",v1);
			document.getElementById("aspect1").style.backgroundImage = "url("+aspectpictures[v1]+")";
			setOption("a2",v2);
			document.getElementById("aspect2").style.backgroundImage = "url("+aspectpictures[v2]+")";
			setOption("a3",v3);
			document.getElementById("aspect3").style.backgroundImage = "url("+aspectpictures[v3]+")";
			setOption("a4",v4);
			document.getElementById("aspect4").style.backgroundImage = "url("+aspectpictures[v4]+")";
			setOption("a5",v5);
			document.getElementById("aspect5").style.backgroundImage = "url("+aspectpictures[v5]+")";
			setOption("a6",v6);
			document.getElementById("aspect6").style.backgroundImage = "url("+aspectpictures[v6]+")";
			setOption("a7",v7);
			document.getElementById("aspect7").style.backgroundImage = "url("+aspectpictures[v7]+")";
			setOption("a8",v8);
			document.getElementById("aspect8").style.backgroundImage = "url("+aspectpictures[v8]+")";
			updateIngredients();
			document.body.scrollTop = document.documentElement.scrollTop = 0;
		}
		
    </script>
	
</head>
<body>


<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div align="center">
	<table class="CSSTableGenerator" border="1" cellspacing="10" cellpadding="10" >
	
	<tr>
	<td style="text-align:center;" colspan="2">
		<image src="cooltext1256548316.png"/>
	</td>
	</tr>

	<tr><td colspan="2">
	<div id="results">
	</div>
	</td></tr>
	
	<tr><td colspan="2">
	<table class="aspects">
	<tr>
	<td><select id="a1" onkeyup="aspectChanged(this,1)" onchange="aspectChanged(this,1)" class="aselect"><? addaspects(); ?></select><div id="aspect1" class="aspectpic"></div></td>
	<td><select id="a2" onkeyup="aspectChanged(this,2)" onchange="aspectChanged(this,2)" class="aselect"><? addaspects(); ?></select><div id="aspect2" class="aspectpic"></div></td>
	<td><select id="a3" onkeyup="aspectChanged(this,3)" onchange="aspectChanged(this,3)" class="aselect"><? addaspects(); ?></select><div id="aspect3" class="aspectpic"></div></td>
	<td><select id="a4" onkeyup="aspectChanged(this,4)" onchange="aspectChanged(this,4)" class="aselect"><? addaspects(); ?></select><div id="aspect4" class="aspectpic"></div></td>
	<td><select id="a5" onkeyup="aspectChanged(this,5)" onchange="aspectChanged(this,5)" class="aselect"><? addaspects(); ?></select><div id="aspect5" class="aspectpic"></div></td>
	<td><select id="a6" onkeyup="aspectChanged(this,6)" onchange="aspectChanged(this,6)" class="aselect"><? addaspects(); ?></select><div id="aspect6" class="aspectpic"></div></td>
	<td><select id="a7" onkeyup="aspectChanged(this,7)" onchange="aspectChanged(this,7)" class="aselect"><? addaspects(); ?></select><div id="aspect7" class="aspectpic"></div></td>
	<td><select id="a8" onkeyup="aspectChanged(this,8)" onchange="aspectChanged(this,8)" class="aselect"><? addaspects(); ?></select><div id="aspect8" class="aspectpic"></div></td>
	</tr>
	</table>

	</td></tr>
	<tr><td colspan="2">
	Recipes (click to activate) <br>
	<table><tr><td>
<?
//ACTIVATE BY: <span style="font-size:12pt; cursor:pointer;" onclick="display(1)">RECIPE LIST</span> - <span style="font-size:12pt; cursor:pointer;" onclick="display(2)">THAUMONOMICON</span>

	$i = 0;
	$lasttier = 0;
	foreach ($recipelist as $key => $value) {
		if ($value["tier"] == 1) $tt = "Tier 1";
		if ($value["tier"] == 2) $tt = "Tier 2";
		if ($value["tier"] == 3) $tt = "Tier 3";
		if ($value["tier"] == 4) $tt = "Knowledge Fragment Research";
		if ($value["tier"] == 5) $tt = "Thaumic Bees";
		if ($value["tier"] == 6) $tt = "Thaumic Tinkerer";
		if ($value["tier"] != $lasttier) {
			if ($lasttier > 0) echo "<br>";
			echo "<big>".$tt."</big><br>";
			$lasttier = $value["tier"];
		}
		echo '<span style="cursor:pointer;" onclick="setMagic('.$value["aspect1"].','.$value["aspect2"].','.$value["aspect3"].','.$value["aspect4"].','.$value["aspect5"].','.$value["aspect6"].','.$value["aspect7"].','.$value["aspect8"].')" style="color:#FFF;" href="">'.$value["name"].'</span><br>';
		if ($i == 24) {
			$i = 0;
			echo "</td><td>";
		}
		$i++;
	}
?>
	</td></tr></table>

	</td></tr>
	
	<tr><td colspan="2" valign="top">
	Thaumonomicon (drag to pan, click to activate)
	<div style="height:10px">&nbsp;</div>

		<div id="flashContent">
			<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="800" height="400" id="thaumamap" align="middle">
				<param name="movie" value="thaumamap.swf" />
				<param name="quality" value="high" />
				<param name="bgcolor" value="#000066" />
				<param name="play" value="true" />
				<param name="loop" value="true" />
				<param name="wmode" value="window" />
				<param name="scale" value="showall" />
				<param name="menu" value="true" />
				<param name="devicefont" value="false" />
				<param name="salign" value="" />
				<param name="allowScriptAccess" value="sameDomain" />
				<!--[if !IE]>-->
				<object type="application/x-shockwave-flash" data="thaumamap.swf" width="800" height="400">
					<param name="movie" value="thaumamap.swf" />
					<param name="quality" value="high" />
					<param name="bgcolor" value="#000066" />
					<param name="play" value="true" />
					<param name="loop" value="true" />
					<param name="wmode" value="window" />
					<param name="scale" value="showall" />
					<param name="menu" value="true" />
					<param name="devicefont" value="false" />
					<param name="salign" value="" />
					<param name="allowScriptAccess" value="sameDomain" />
				<!--<![endif]-->
					<a href="http://www.adobe.com/go/getflash">
						<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" />
					</a>
				<!--[if !IE]>-->
				</object>
				<!--<![endif]-->
			</object>
		</div>

	
<?

if ($_GET["admin"] == $adminpass) {

?>
	<br><br>
	<form class="CSSTableGenerator" method="post">
	<table>
	<tr>
		<th colspan="2">ADD ITEM</th>
	</tr><tr>
		<td>Name:</td><td><input type="text" name="name"></td>
	</tr><tr>
		<td>Picture:</td><td><input type="text" name="pic"></td>
	</tr><tr>
		<td><input type="submit" value="Add"></td><td><input type="hidden" name="action" value="additem<?=$adminpass;?>"></td>
	</tr></table>
	</form>
	<br>
	<form class="CSSTableGenerator" method="post">
	<table>
	<tr>
		<th colspan="3">ADD ASPECT</th>
	</tr><tr>
		<td>Name:</td><td colspan="2"><input type="text" name="name"></td>
	</tr><tr>
		<td>Picture:</td><td colspan="2"><input type="text" name="pic"></td>
	</tr><tr>
		<td>Item 1:</td><td><select name="item1"><? additems(); ?></select></td><td><input type="text" name="item1a"></td>
	</tr><tr>
		<td>Item 2:</td><td><select name="item2"><? additems(); ?></select></td><td><input type="text" name="item2a"></td>
	</tr><tr>
		<td>Item 3:</td><td><select name="item3"><? additems(); ?></select></td><td><input type="text" name="item3a"></td>
	</tr><tr>
		<td>Item 4:</td><td><select name="item4"><? additems(); ?></select></td><td><input type="text" name="item4a"></td>
	</tr><tr>
		<td>Item 5:</td><td><select name="item5"><? additems(); ?></select></td><td><input type="text" name="item5a"></td>
	</tr><tr>
		<td>Item 6:</td><td><select name="item6"><? additems(); ?></select></td><td><input type="text" name="item6a"></td>
	</tr><tr>
		<td>Item 7:</td><td><select name="item7"><? additems(); ?></select></td><td><input type="text" name="item7a"></td>
	</tr><tr>
		<td>Item 8:</td><td><select name="item8"><? additems(); ?></select></td><td><input type="text" name="item8a"></td>
	</tr><tr>
		<td><input type="submit" value="Add"></td><td colspan="2"><input type="hidden" name="action" value="addaspect<?=$adminpass;?>"></td>
	</tr></table>
	</form>
	<br>
	<form class="CSSTableGenerator" method="post">
	<table>
	<tr>
		<th colspan="2">ADD RECIPE</th>
	</tr><tr>
		<td>Name:</td><td><input size="46" type="text" name="name"></td>
	</tr><tr>
		<td>Tier:</td><td><select name="tier"><option value="1">Tier 1</option><option value="2">Tier 2</option><option value="3">Tier 3</option><option value="4">Knowledge Fragment Research</option><option value="5">Thaumic Bees</option><option value="6">Thaumic Tinkerer</option></select></td>
	</tr><tr>
		<td>Aspect 1:</td><td><select name="aspect1"><? addaspects(); ?></select></td>
	</tr><tr>
		<td>Aspect 2:</td><td><select name="aspect2"><? addaspects(); ?></select></td>
	</tr><tr>
		<td>Aspect 3:</td><td><select name="aspect3"><? addaspects(); ?></select></td>
	</tr><tr>
		<td>Aspect 4:</td><td><select name="aspect4"><? addaspects(); ?></select></td>
	</tr><tr>
		<td>Aspect 5:</td><td><select name="aspect5"><? addaspects(); ?></select></td>
	</tr><tr>
		<td>Aspect 6:</td><td><select name="aspect6"><? addaspects(); ?></select></td>
	</tr><tr>
		<td>Aspect 7:</td><td><select name="aspect7"><? addaspects(); ?></select></td>
	</tr><tr>
		<td>Aspect 8:</td><td><select name="aspect8"><? addaspects(); ?></select></td>
	</tr><tr>
		<td><input type="submit" value="Add"></td><td><input type="hidden" name="action" value="addrecipe<?=$adminpass;?>"></td>
	</tr></table>
	</form>

	
<?
}
?>

</td></tr>
<tr>
<td valign="top" width="60%">
	ASPECT LIST (hover to see item count)<br><br>
<?

	foreach ($aspectlist as $key => $value) {
		echo '<table><tr><td width="64px"><image class="tooltip" title="'.$value["name"].'" src="icons_aspects/'.$value["pic"].'"/></td>';
		echo '<td><span style="font-size:14pt; padding-left:8px; line-height:32px;">'.$value["name"].'</span><br>';
		if ($value["item1"] > 0) echo '<image class="tooltip" title="'.$itemlist[$value["item1"]]["name"].' ('.$value["item1a"].')" style="float:left;" src="items/'.$itemlist[$value["item1"]]["pic"].'"/>';
		if ($value["item2"] > 0) echo '<image class="tooltip" title="'.$itemlist[$value["item2"]]["name"].' ('.$value["item2a"].')" style="float:left;" src="items/'.$itemlist[$value["item2"]]["pic"].'"/>';
		if ($value["item3"] > 0) echo '<image class="tooltip" title="'.$itemlist[$value["item3"]]["name"].' ('.$value["item3a"].')" style="float:left;" src="items/'.$itemlist[$value["item3"]]["pic"].'"/>';
		if ($value["item4"] > 0) echo '<image class="tooltip" title="'.$itemlist[$value["item4"]]["name"].' ('.$value["item4a"].')" style="float:left;" src="items/'.$itemlist[$value["item4"]]["pic"].'"/>';
		if ($value["item5"] > 0) echo '<image class="tooltip" title="'.$itemlist[$value["item5"]]["name"].' ('.$value["item5a"].')" style="float:left;" src="items/'.$itemlist[$value["item5"]]["pic"].'"/>';
		if ($value["item6"] > 0) echo '<image class="tooltip" title="'.$itemlist[$value["item6"]]["name"].' ('.$value["item6a"].')" style="float:left;" src="items/'.$itemlist[$value["item6"]]["pic"].'"/>';
		if ($value["item7"] > 0) echo '<image class="tooltip" title="'.$itemlist[$value["item7"]]["name"].' ('.$value["item7a"].')" style="float:left;" src="items/'.$itemlist[$value["item7"]]["pic"].'"/>';
		if ($value["item8"] > 0) echo '<image class="tooltip" title="'.$itemlist[$value["item8"]]["name"].' ('.$value["item8a"].')" style="float:left;" src="items/'.$itemlist[$value["item8"]]["pic"].'"/>';
		echo '</td></tr></table><hr>';
	}
?>	
</td>
<td valign="top">
	ITEM LIST (hover to see aspects)<br><br>
<?
	foreach ($itemlist as $key => $value) {
		echo '<div class="tooltip" title="'.$value["aspects"].'"><image style="float:left;" src="items/'.$value["pic"].'"/><span style="padding-left:8px; line-height:32px;">'.$value["name"].'</span></div>';
	}
?>	
</td>


</tr></table>

<br><br><br>

<div class="fb-comments" data-href="http://thaumasolver.renderfreak.com/" data-colorscheme="light" data-numposts="100" data-width="840"></div>

</div>



</body>
</html>