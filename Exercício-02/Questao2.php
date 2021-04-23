<?php

$aluno = 10;
$escolha = "PROGRAMACAO DE APLICACOES WEB";

Matricula_Aluno($aluno, $escolha);

function Matricula_Aluno($id_aluno, $disciplina){

	$index = -1;
	$result = array();	

	//estabelece connexao com servido mysql.
	@$connect =  mysqli_connect('localhost','root','','prog2');
	if (mysqli_connect_errno()){
		die("Error:". mysqli_connect_error($connect));
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
				echo("Nao foi possivel obter o numero de vagas da turma $id_turma.\n");
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
				die("Nao ha turmas para esta disciplina!\n");
			}
			$row = mysqli_fetch_row($result[0]);
		}
	}else{
		echo("Nao foi encontradas turmas para a disciplina $id_disciplina.\n 
			Por favor verifique se os dados foram inseridos corretamente!\n");
		die("Error: ".mysqli_error($connect));
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
			echo("Nao foi possivel matricular o aluno $id_aluno.\n
				Por favor verifique se os dados foram inseridos corretamente!\n");
			die("Error: ".mysqli_error($connect));
		}
	}else{
		
		$buffer = mysqli_query($connect, "select distinct matricula.id_turma from turma, matricula where matricula.id_turma in (select turma.id_turma from turma where turma.id_disciplina in (select disciplina.id_disciplina from disciplina where disciplina.titulo = '$disciplina') and turma.ano in (select extract(year from curdate())))");
		
		$row2 = mysqli_fetch_row($buffer);				
		
		while($row2!= null) {
			$result[4] = mysqli_query($connect, "select id_aluno from aluno where id_aluno in (select matricula.id_aluno from matricula where matricula.id_turma = $row2[0]) and CR in (select MIN(CR) from aluno where aluno.id_aluno in (select matricula.id_aluno from matricula where matricula.id_turma = $row2[0]))");
			if($result[4]!= null){
				$id_aluno_mtd = mysqli_fetch_row($result[4]);				
			}else{
				echo("Nao foi possivel obter o id dos alunos para a realocação.\n");
				die("Error:".mysqli_error($connect));
			}													
			$result[5] = mysqli_query($connect, "select aluno.CR from aluno where aluno.id_aluno = $id_aluno_mtd[0]");			
			if($result[5]!=null){
				$CR_aluno_mtd = mysqli_fetch_row($result[5]);				
			}else{
				echo("Nao foi possivel obter o CR dos alunos para a realocação.\n");
				die("Error:".mysqli_error($connect));
			}					
			$result[6] = mysqli_query($connect, "select aluno.CR from aluno where aluno.id_aluno = $id_aluno");				
			if($result[6]!= null){
				$aluno_novo = mysqli_fetch_row($result[6]);
			}else{
				echo("Nao foi possivel obter o CR deste aluno.\n");
				die("Error:".mysqli_error($connect));
			}
			if($CR_aluno_mtd[0] < $aluno_novo[0]){
				if(!@mysqli_query($connect, "delete from matricula where matricula.id_aluno = $id_aluno_mtd[0]")){						
					echo("Nao foi possivel encontrar o aluno $id_aluno_mtd[0] para realocação.\n");
					die("Error:".mysqli_error($connect));
				}
				$result[7] = mysqli_query($connect, "select MAX(matricula.id_matricula) from matricula");
				if($result[7]!= null){
					$id_matricula = mysqli_fetch_row($result[7]);
					$id_matricula[0]++;
				}else{
					$id_matricula[0] = 1;
				}
				$register = mysqli_query($connect, "insert into matricula(id_matricula, id_turma, id_aluno) values($id_matricula[0], $row2[0], $id_aluno)");
				if($register){
					echo "Matricula realizada com sucesso na disciplina $disciplina!\n";
					$perdeu_vaga = true;
					break;
				}else{
					echo("Nao foi possivel matricular o aluno $id_aluno.\n
						Por favor verifique se os dados foram inseridos corretamente!\n");
					die("Error:".mysqli_error($connect));
				}
			}else{
				$perdeu_vaga = False;					
			}//if <				
			$row2 = mysqli_fetch_row($buffer);
		}//while
		$result[8] = mysqli_query($connect, "select MAX(id_espera) from lista_espera");
		$result[9] = mysqli_query($connect, "select disciplina.id_disciplina from disciplina where disciplina.titulo = '$disciplina'");
		if($result[8]!= null){
			$id_espera = mysqli_fetch_row($result[8]);
			$id_espera[0]++; 
		}else{
			$id_espera[0] = 1;
		}
		if($result[9]!= null){
			$id_disciplina = mysqli_fetch_row($result[9]);			
		}else{
			echo("Nao foi possivel encontrar a disciplina $disciplina.\n
				Por favor verifique se os dados foram inseridos corretamente.\n");
			die("Error:".mysqli_error($connect));
		}
		if($perdeu_vaga){
			$not_register = mysqli_query($connect, "insert into lista_espera(id_espera, id_disciplina, id_aluno) values($id_espera[0], $id_disciplina[0], $id_aluno_mtd[0])");
			if($not_register!= null){
				echo "Aluno $id_aluno_mtd[0] adicionado a lista de espera!\n";
			}else{
				echo("Nao foi possivel adicionar o aluno $id_aluno_mtd[0] a lista de espera.\n
					Por favor verifique se os dados foram inseridos corretamente.\n");
				die("Error:".mysqli_error($connect));
			}
		}else{
			$not_register = mysqli_query($connect, "insert into lista_espera(id_espera, id_disciplina, id_aluno) values($id_espera[0], $id_disciplina[0], $id_aluno)");
			if($not_register){
				echo "Nao ha vagas para a disciplina $disciplina.\n";
			}else{
				echo("Nao foi possivel adicionar o aluno $id_aluno na lista de espera.\n
					Por favor verifique se os dados foram inseridos corretamente.\n");
				die("Error:".mysqli_error($connect));
			}
		}
	}
	mysqli_close($connect);
}	
?>