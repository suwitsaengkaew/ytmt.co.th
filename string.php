<?php
	$data = '2017-03-012017-03-10rp06027';
        $stdate = substr($data, 0, 10) . ' 00:00:00';
        $eddate = substr($data, 10, 10) . ' 00:00:00';
        $empid = substr($data, 20);
//	$stdate = substr($str, 0 ,10);
//	$eddate = substr($str, 10, 10);
//	$empid = substr($str, 20);
//echo $stdate;
echo $eddate;
//	echo $empid;
	//echo $str;

?>
