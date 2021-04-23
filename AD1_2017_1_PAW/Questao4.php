<?php

$nomesOriginais = array("O Poderoso Chefão","Minions", "Rei Leão", "Tropa de elite", "Matrix");
$nomesReformulados = array("O Poderoso Melão","Milhons", "Rei Melão","Horta de elite", "Maçatirix");
$Letras = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','y','x','w','z');
$primNome = " ";
$segNome = " ";

class similaridade{
	var $nomeFilme = " ";
	var $nomesSimilares = array();
	var $pontuacao = array();
	
	function DefinirNomeFilme($nomeF){
		$this->nomeFilme = $nomeF;
	}
	
	function ObterNomeFilme(){
		return @$this->nomeFilme;
	}
	
	function DefinirNomesSimilares($nomeDS,$posDS){
		$this->nomesSimilares[$posDS] = $nomeDS;
	}
	
	function ObterNomesSimilares($posOS){
		return @$this->nomesSimilares[$posOS];
	}
	
	function DefinirPontuacao($valor,$posDP){
		$this->pontuacao[$posDP] = $valor;
	}
	
	function ObterPontuacao($posOP){
		return @$this->pontuacao[$posOP];
	}
}

$soma = 0;
$saida = array();

function ComparacaoFilmes(){	
	global 
		$nomesOriginais, $nomesReformulados, 
		$primNome, $segNome, $soma, $saida, $Letras;
	for ($i=0; $i<sizeof($nomesOriginais); $i++){
		$saida[$i] = new similaridade();
		$primNome = $nomesOriginais[$i];		
		for ($j=0; $j<sizeof($nomesReformulados); $j++){
			$segNome = $nomesReformulados[$j];		
			for ($k=0; $k<strlen($primNome); $k++){
				if($k>strlen($segNome)){
					break;
				}
				if((@$primNome[$k] == @$segNome[$k]) and
				(($primNome[$k] <> " ") and
                ($segNome[$k] <> " "))){
					$soma = $soma+10;					
				}else{
					if (((@$primNome[$k] == strtolower(@$segNome[$k])) or (@$primNome[$k] == strtoupper(@$segNome[$k]))) and
					(($primNome[$k] <> " ") and ($segNome[$k] <> " "))){						
						$soma = $soma+5;
					}
				}
				if(@$primNome[$k] <> @$segNome[$k]){					
					if(in_array(strtolower(@$primNome[$k]),$Letras) and
					in_array(strtolower(@$segNome[$k]),$Letras)){
						$soma = $soma+2;
					}else{
						$soma = $soma+0;
					}					   
				}//for k
			}
			$saida[$i]->DefinirNomeFilme($primNome);
			$saida[$i]->DefinirNomesSimilares($segNome, $j);
			$saida[$i]->DefinirPontuacao($soma, $j);
			$soma = 0;
		}//for j
	}//for i 
	for ($i=0; $i<sizeof($saida); $i++){
		echo "[$i - ", $saida[$i]->ObterNomeFilme(),"]\n";
		for($j=0; $saida[$i]->ObterPontuacao($j) != null; $j++){			
			echo $saida[$i]->ObterNomesSimilares($j)," "; 
			echo $saida[$i]->ObterPontuacao($j),"\n"; 
		}		
	}
}
echo (ComparacaoFilmes());
?>