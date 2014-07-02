<?php
if(!$totalGraders){
	$graderRatio = 0;
	$graderBar = 'danger';
}else{
	$graderRatio = $currentGraders/$totalGraders;
	if($graderRatio > .66){
		$graderBar = 'success';
	}elseif($graderRatio > .33){
		$graderBar = 'warning';
	}else{
		$graderBar = 'danger';
	}
}

if(!$totalLabTAs){
	$labTAsRatio = 0;
	$labTAsBar = 'danger';
}else{
	$labTAsRatio = $currentLabTAs/$totalLabTAs;
	if($labTAsRatio > .66){
		$labTAsBar = 'success';
	}elseif($labTAsRatio > .33){
		$labTAsBar = 'warning';
	}else{
		$labTAsBar = 'danger';
	}
}								

if(!$totalWorkshopLeaders){
	$workShopLeaderRatio = 0;
	$workshopLeaderBar = 'danger';
}else{
	$workShopLeaderRatio = $currentWorkshopLeaders/$totalWorkshopLeaders;
	if($workShopLeaderRatio > .66){
		$workshopLeaderBar = 'success';
	}elseif($workShopLeaderRatio > .33){
		$workshopLeaderBar = 'warning';
	}else{
		$workshopLeaderBar = 'danger';
	}
}

