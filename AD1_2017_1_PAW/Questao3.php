<?php

$Letras = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
(string)$saida = "";
$texto = "meu nome é daniel";
function cifraTexto($texto){
	global $Letras, $saida;	
	for($i=0; $i<strlen($texto); $i++){
		strtolower($texto[$i]);
		if(in_array($texto[$i],$Letras)){
			$saida .= str_rot13($texto[$i]);
		}else{
			$saida .= $texto[$i];							
		}
	}
	return $saida;
}
echo(strtoupper(cifraTexto($texto)));
?>