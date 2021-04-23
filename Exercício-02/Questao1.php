<?php
$aluno = 1;
$escolha = "programacao de aplicacoes web";

Matricula_Aluno($aluno, $escolha);

function Matricula_Aluno($id_aluno, $disciplina){

	$index = -1;
	$result = array();
	//estabelece connexao com servido mysql.
	@$connect =  mysqli_connect('localhost','root','','prog2');
	if (mysqli_connect_errno()){
		die("Error:".mysqli_connect_error($connect));
	}
	//obtem obtem o id_turma das turmas onde sao ministradas a disciplina informada no ano atual.
	$result[0] = mysqli_query($connect, "select turma.id_turma from turma where turma.id_disciplina in (select disciplina.id_disciplina from disciplina where disciplina.titulo = '$disciplina') and turma.ano in (select extract(year from curdate()))");
	if ($result[0] != null){
		$row = mysqli_fetch_row($result[0]);		
		while($row != null){
			//obtem o numero_de_vagas das turmas que ministram a disciplina informada no ano  atual.
			$result[1] = mysqli_query($connect, "select turma.numero_de_vagas from turma where turma.id_turma = $row[0]");
			if($result[1]!= null){
				$row1 = mysqli_fetch_row($result[1]);
			}else{
				die("Error: ".mysqli_error($connect));
			}
			//obtem as turmas que possuem vagas.
			$result[2] = mysqli_query($connect, "select count(matricula.id_turma) from matricula where matricula.id_turma = $row[0]");
			if($result[2]!= null){				
				$num = mysqli_fetch_row($result[2]);
				if($num[0] < $row1[0]){
					$index++;
					break;
				}
			}else{
				die("Nao ha turmas para esta disciplina!");
			}
			$row = mysqli_fetch_row($result[0]);
		}
	}else{
		die("A disciplina nao existe!");
	}
	if($index >-1){
		//obtem o ultimo id_matricula se existir.
		$result[3] = mysqli_query($connect, "select max(matricula.id_matricula) from matricula");
		if($result[3]!= null){
			$id_matricula = mysqli_fetch_row($result[3]);
			$id_matricula[0]++;
		}else{
			$id_matricula[0] = 1;
		}
		//matricula o aluno em uma turma que possui vagas para a disciplina informada se existir.
		$register = mysqli_query($connect, "insert into matricula(id_matricula, id_turma, id_aluno) values($id_matricula[0], $row[0], $id_aluno)");
		if($register){
			echo "Matricula realizada com sucesso na disciplina $disciplina!";
		}else{
			die("Error: ".mysqli_error($connect));
		}
	}else{
		//senao exitir vagas para a disciplina informada o aluno vai para a lista_espera.
		//obtem o ultimo id_espera se exitir.
		$result[4] = mysqli_query($connect, "select max(id_espera) from lista_espera");
		if($result[4]!= null){
			$id_espera = mysqli_fetch_row($result[4]);
			$id_espera[0]++;
		}else{
			$id_espera[0] = 1;
		}
		//obtem o id_disciplina da disciplina informada.
		$result[5] = mysqli_query($connect, "select disciplina.id_disciplina from disciplina where disciplina.titulo = '$disciplina'");
		if($result[5]!= null){
			$id_disciplina = mysqli_fetch_row($result[5]);
		}else{
			$id_disciplina[0] = 1;
		}
		//insere o aluno na lista de espera.
		$not_register = mysqli_query($connect, "insert into lista_espera(id_espera, id_disciplina, id_aluno) values($id_espera[0], $id_disciplina[0], $id_aluno)");
		if($not_register){
			echo "A disciplina $disciplina nao possui mais vagas!";
		}else{
			die("Error: ".mysqli_error($connect));
		}
	}
	mysqli_close($connect);
}
?>