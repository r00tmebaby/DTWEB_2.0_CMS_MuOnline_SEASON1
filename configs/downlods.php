<?php
if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {header("Location:../error.php");}else{
include($_SERVER['DOCUMENT_ROOT']."/configs/config.php");
	// Downloads
	$option['downloads'] = array(
	
		array(
			'name' => 'MuBulgaria Client',
			'hosted' => 'DOX.BG',
			'size' => '206',
			'date' => '01/15/2019',
			'link' => 'https://dox.abv.bg/download?id=b7db2d55b7&fbclid=IwAR2fNxQk85Wz9PPo7aVvdH1D4bPjWhmDJdu8sP2I6diQXaW7xD8ME2FS78Q'
		),
		array(
			'name' => 'MuBulgaria Client',
			'hosted' => 'MEGA.nz',
			'size' => '206',
			'date' => '01/15/2019',
			'link' => 'https://mega.nz/?fbclid=IwAR0j1kDH8yrKlkJoPhtgRpoPM0LS6QM9_jEGzc-A78Oa4q0fxBGN2bPyRVU#!r2IQzAZY!tVyWHRZzpM4ssJtRgvn5iYkbuWSbycsuKRmWhU6_VWY'
		),
	//	array(
//			'name' => 'MuDT OLD Client',
//			'hosted' => 'Dox BG',
//			'size' => '206',
//			'date' => '26/07/2010',
//			'link' => '#downloads'
//		),
		
	);
}