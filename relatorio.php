<?php
include("conexao.php");
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.html");
    exit();
}

function getTurmas($connection) {
    $sql = "SELECT id, nome FROM turmas ORDER BY nome";
    return mysqli_query($connection, $sql);
}

function getProfessores($connection) {
    $sql = "SELECT professores.id, usuarios.nome FROM professores 
            INNER JOIN usuarios ON professores.fk_user = usuarios.id 
            ORDER BY usuarios.nome";
    return mysqli_query($connection, $sql);
}

function getDisciplinas($connection) {
    $sql = "SELECT id, nome FROM disciplinas ORDER BY nome";
    return mysqli_query($connection, $sql);
}

$filtroTurma = isset($_GET['turma']) ? $_GET['turma'] : '';
$filtroOrdem = isset($_GET['ordem']) ? $_GET['ordem'] : '';
$filtroProfessor = isset($_GET['professor']) ? $_GET['professor'] : '';
$filtroDisciplina = isset($_GET['disciplina']) ? $_GET['disciplina'] : '';

$sql = "SELECT 
            alunos.matricula,
            usuarios.nome AS aluno,
            disciplinas.nome AS disciplina,
            turmas.nome AS turma,
            notas.nota,
            notas.dataL,
            notas.id AS nota_id,
            professores.id AS prof_id,
            prof_usuarios.nome AS professor
        FROM notas
        INNER JOIN alunos ON notas.fk_aluno = alunos.id
        INNER JOIN usuarios ON alunos.fk_user = usuarios.id
        INNER JOIN disciplinas ON notas.fk_disc = disciplinas.id
        INNER JOIN turma_alunos ON turma_alunos.fk_aluno = alunos.id
        INNER JOIN turmas ON turma_alunos.fk_turma = turmas.id
        INNER JOIN prof_disc_turma ON prof_disc_turma.fk_disc = disciplinas.id AND prof_disc_turma.fk_turma = turmas.id
        INNER JOIN professores ON prof_disc_turma.fk_prof = professores.id
        INNER JOIN usuarios AS prof_usuarios ON professores.fk_user = prof_usuarios.id
        WHERE 1 = 1";

if (!empty($filtroTurma)) {
    $sql .= " AND turmas.id = " . intval($filtroTurma);
}
if (!empty($filtroProfessor)) {
    $sql .= " AND professores.id = " . intval($filtroProfessor);
}
if (!empty($filtroDisciplina)) {
    $sql .= " AND disciplinas.id = " . intval($filtroDisciplina);
}

switch ($filtroOrdem) {
    case 'nome_asc':
        $sql .= " ORDER BY aluno ASC";
        break;
    case 'nota_desc':
        $sql .= " ORDER BY nota DESC";
        break;
    case 'nota_asc':
        $sql .= " ORDER BY nota ASC";
        break;
    default:
        $sql .= " ORDER BY turma, aluno, disciplina";
}

$consulta = mysqli_query($connection, $sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório Administrativo</title>
    <link rel="stylesheet" href="Style/relatorio.css">
</head>
<body>
    <h1>Relatório de Notas - Administração</h1>

    <form 
        style="
               margin: 2em;
               text-align: center;
               "
    method="GET" action="">
        <label for="turma">Filtrar por Turma:</label>
        <select
            style="
               width: 200px;
              " 
        name="turma" id="turma">
            <option value="">Todas</option>
            <?php
            $turmas = getTurmas($connection);
            while ($t = mysqli_fetch_assoc($turmas)) {
                $selected = ($filtroTurma == $t['id']) ? 'selected' : '';
                echo "<option value='{$t['id']}' $selected>{$t['nome']}</option>";
            }
            ?>
        </select>

        <label for="professor">Filtrar por Professor:</label>
        <select
            style="
               width: 200px;
              " 
        name="professor" id="professor">
            <option value="">Todos</option>
            <?php
            $profs = getProfessores($connection);
            while ($p = mysqli_fetch_assoc($profs)) {
                $selected = ($filtroProfessor == $p['id']) ? 'selected' : '';
                echo "<option value='{$p['id']}' $selected>{$p['nome']}</option>";
            }
            ?>
        </select>

        <label for="disciplina">Filtrar por Disciplina:</label>
        <select
            style="
               width: 200px;
              " 
        name="disciplina" id="disciplina">
            <option value="">Todas</option>
            <?php
            $disciplinas = getDisciplinas($connection);
            while ($d = mysqli_fetch_assoc($disciplinas)) {
                $selected = ($filtroDisciplina == $d['id']) ? 'selected' : '';
                echo "<option value='{$d['id']}' $selected>{$d['nome']}</option>";
            }
            ?>
        </select>

        <label for="ordem">Ordenar por:</label>
        <select
            style="
               width: 200px;
              " 
            name="ordem" id="ordem">
            <option value="">Padrão</option>
            <option value="nome_asc" <?= $filtroOrdem == 'nome_asc' ? 'selected' : '' ?>>Nome (A-Z)</option>
            <option value="nota_desc" <?= $filtroOrdem == 'nota_desc' ? 'selected' : '' ?>>Maiores Notas</option>
            <option value="nota_asc" <?= $filtroOrdem == 'nota_asc' ? 'selected' : '' ?>>Menores Notas</option>
        </select>

        <button type="submit">Filtrar</button>
    </form>
    <br>

    <?php
    if ($consulta && mysqli_num_rows($consulta) > 0) {
        echo "<div
            style='
                    display: flex;
                   justify-content: center;
                   align-items: center;
                   '
        >";
        echo "<table
                style='
                        height: 100%;
                        width: 100%;
                       '
                >";
        echo "<tr>
                <th>Matrícula</th>
                <th>Aluno</th>
                <th>Disciplina</th>
                <th>Turma</th>
                <th>Nota</th>
                <th>Data</th>
                <th>Professor</th>
              </tr>";

        while ($row = mysqli_fetch_assoc($consulta)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['matricula']) . "</td>
                    <td>" . htmlspecialchars($row['aluno']) . "</td>
                    <td>" . htmlspecialchars($row['disciplina']) . "</td>
                    <td>" . htmlspecialchars($row['turma']) . "</td>
                    <td>" . htmlspecialchars($row['nota']) . "</td>
                    <td>" . htmlspecialchars($row['dataL']) . "</td>
                    <td>" . htmlspecialchars($row['professor']) . "</td>
                  </tr>";
        }
        echo "</table>";
        echo "</div>";
    } else {
        echo "<p>Nenhum resultado encontrado.</p>";
    }

    mysqli_close($connection);
    ?>
           <div style="text-align: center;">
           <a style="
           background-color: dodgerblue;
           color: white;
           border: none;
           padding: 10px 20px;
           text-align: center;
           text-decoration: none;
           display: inline-block;
           font-size: 16px;
           margin: 10px 2px;
           cursor: pointer;
           border-radius: 5px;"
           href="administrador.php" class="btn btn-voltar">Voltar</a>
       </div>
</body>
</html>