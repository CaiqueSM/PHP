<?php

$ano = "2017";
$periodo = "1";

AchaMaiorMedia($ano, $periodo);

function AchaMaiorMedia($ano, $periodo){
	
	$index = 0;
	
	@$connect = mysqli_connect('localhost','root','','prog2');
	if(mysqli_connect_errno()){
		die("Error: ".mysqli_connect_error($connect)."\n");
	}
	$result[0] = mysqli_query($connect, "select matricula.id_aluno from matricula where matricula.id_aluno in (select id_aluno from aluno where CR>8) and matricula.nota_final in (select MAX(nota_final) from matricula) and matricula.id_turma in (select id_turma from turma where turma.ano = $ano and turma.periodo = $periodo)");
	
	if ($result[0] != null){				
		$row = mysqli_fetch_row($result[0]);
		while ($row[0]!= null){
			$result[1] = mysqli_query($connect, "select matricula.id_matricula from matricula where matricula.id_aluno = $row[0]");
			$id_matricula = mysqli_fetch_row($result[1]);
			$matriculas[$index] = $id_matricula[0];			
			$index++;
			$row = mysqli_fetch_row($result[0]);
		}
		rsort($matriculas);		
		$result[2] = mysqli_query($connect, "select matricula.id_aluno from matricula where matricula.id_matricula = $matriculas[0]");
		$row = mysqli_fetch_row($result[2]);
		echo("O aluno $row[0] possui a maior media!\n");			
	}else{
		echo("Nao foi possivel determinar a maior media do semestre, pois nao foram encontradas turmas no periodo e no ano informados!\n");
	}	
	
	mysqli_close($connect);
}

?>