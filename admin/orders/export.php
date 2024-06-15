
<?php
include_once 'dbconn.php'; 
function filterData(&$str){
    $str = preg_replace("/\t/","\\t",$str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str,'"')) $str = '"' . str_replace('"','""',$str) . '"';
}

$fileName = "order-date_" . date('Y-m-d') . ".xls";

$fields = array('ID', 'CLIENT ID', 'DELIVERY ADDRESS', 'AMOUNT PAID','PAYMENT METHOD', 'STATUS', 'PAID', 'DATE CREATED','DATE UPDATED');

$excelData = implode("\t",array_values($fields)) . "\n";

$query = $db->query("SELECT * FROM orders ORDER BY id ASC");
if($query->num_rows > 0){
    while($row = $query->fetch_assoc()){
        $status = ($row['status'] ==1)?'Active':'Inactive';
        $lineData = array($row['id'],$row['client_id'],$row['delivery_address'],$row['payment_method'],$row['amount'],$row['status'],$row['paid'],$row['date_created'],$row['date_updated']);
        array_walk($lineData, 'filterData');
        $excelData .= implode("\t", array_values($lineData)) . "\n";
    }
}else{
    $excelData .= 'No records found' . "\n";
}

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$fileName\"");

echo $excelData;

exit;
?>