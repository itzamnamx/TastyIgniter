<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$conn = new mysqli("localhost", "root", "ZlHF6A7KEGWN", "lozcar");

	$till=10;
	
	if(isset($_GET["till"]) && !empty($_GET["till"]) ){
		$till = $_GET["till"];
		$till = $conn->real_escape_string($till);
	}

	//$query="SELECT p_id,p_name,p_description,p_image_id,p_price FROM products where p_id<=".$till." and p_available=1 ";
        $query="SELECT category_id AS id, guid AS guid, name AS title, description AS description, url AS url, featured AS featured, icon AS icon FROM sqfnjcd9v_categories WHERE status=1 ";

	
	if( isset($_GET["sort"]) && !empty($_GET["sort"]) ){
		
		$s = $_GET["sort"];
		if($s=="n"){	$query.="order by menu_name";}
		else if($s=="plh"){	$query.="order by menu_price";}
		else if($s=="phl"){	$query.="order by menu_price desc";}
	}

	
	

$result = $conn->query($query);
$outp = "";
while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($outp != "") {$outp .= ",";}
    $outp .= '{"id":"'  . $rs["id"] . '",';
    $outp .= '"guid":"'   . $rs["guid"]        . '",';
	$outp .= '"title":"'   . $rs["title"]        . '",';
	$outp .= '"description":"'   . $rs["description"]        . '",';
        $outp .= '"featured":"'   . $rs["featured"]        . '",';
        $outp .= '"icon":"'   . $rs["icon"]        . '",';        
	$outp .= '"url":"'. $rs["url"]     . '"}';
        
}


// Adding has more
$result=$conn->query("SELECT count(*) as total from sqfnjcd9v_categories");
$data=$result->fetch_array(MYSQLI_ASSOC);
$total = $data['total'];

        //TODO FAKE ID

        $id=1;			
				
	$outp ='{"id":'.$id.',"total":'.$total.',"result":['.$outp.']}';

	
$conn->close();

echo($outp);
?> 