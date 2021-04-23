<?php

$k = 0;
$j = 0;
$i = 0;
$soma = 9;
$pontuacao = 0;
$desloc = -1;
$horizontal = array();
$vertical = array();
$temp = array();
$pos = 0;
$tabuleiro = array(	array(1, 3, 6, 7, 2, 5),
					array(4, 4, 6, 2, 2, 4),
					array(2, 3, 5, 1, 1, 5),
					array(5, 3, 5, 1, 5, 7),
					array(6, 2, 4, 2, 1, 3),
					array(3, 2, 3, 1, 7, 4));

function acheSequencia($tabuleiro, $soma){
	global $k, $j, $i, $pontuacao, $desloc, $horizontal, $vertical, $temp, $pos;
	//horizontal
	for($i=0; $i<sizeof($tabuleiro); $i++){
		while ($j< sizeof($tabuleiro)){
			$temp[$k] = $tabuleiro[$i][$j];
			$pontuacao+=$temp[$k];
			if($pontuacao == $soma){
				if (!in_array($temp, $horizontal)){
					$horizontal[$pos] = $temp;
					$pos+=1;
				}				
				$pontuacao = 0;
				$temp = array();
				$k = -1;
			}else{
				if($pontuacao>$soma){
					$j = $desloc;
					$desloc+=1;
					$pontuacao = 0;
					$temp = array();
					$k = -1;
				}
			}
			$j+=1;
			$k+=1;
		}
		$j=0;
		$desloc = -1;
		if($k>$soma){
			$k =0;
		}
	}
	echo "horizontal: ";
	for($i=0; $i<sizeof($horizontal);$i++){
		for($j=0; $j<sizeof($horizontal[$i]); $j++){
			echo $horizontal[$i][$j]," ";
		}
		echo "-";
	}
	//Vertical
	$i = 0;
	$temp = array();
	$desloc = -1;
	$k = 0;
	$pontuacao = 0;
	$pos =0;
	for($j=0; $j<sizeof($tabuleiro); $j++){
		while ($i< sizeof($tabuleiro)){
			$temp[$k] = $tabuleiro[$i][$j];
			$pontuacao+=$temp[$k];
			if($pontuacao == $soma){
				if (!in_array($temp, $vertical)){
					$vertical[$pos] = $temp;
					$pos+=1;
				}				
				$pontuacao = 0;
				$temp = array();
				$k = -1;
			}else{
				if($pontuacao>$soma){
					$i = $desloc;
					$desloc+=1;
					$pontuacao = 0;
					$temp = array();
					$k = -1;
				}
			}
			$i+=1;
			$k+=1;
		}
		$i=0;
		$desloc = -1;
		if($k>$soma){
			$k =0;
		}
	}
	echo "\n", "vertical: ";
	for($i=0; $i<sizeof($vertical);$i++){
		for($j=0; $j<sizeof($vertical[$i]); $j++){
			echo $vertical[$i][$j]," ";
		}
		echo "-";
	}	
}
echo(acheSequencia($tabuleiro, $soma));
?>