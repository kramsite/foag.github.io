<?php
session_start();

// Inicializar a variável de matérias e notas se não existir
if (!isset($_SESSION['materias'])) {
    $_SESSION['materias'] = [];
}

if (!isset($_SESSION['notas'])) {
    $_SESSION['notas'] = [];
}

// Adicionar ou remover matérias e notas
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['adicionar_linha'])) {
        // Adicionar uma nova linha para o horário
        $_SESSION['materias'][] = '';  // Adiciona uma nova matéria vazia
        $_SESSION['notas'][] = [null, null, null, null];  // 4 bimestres (inicializados como null)
    }
    
    if (isset($_POST['remover_linha']) && count($_SESSION['materias']) > 0) {
        // Remover a última linha de matéria e notas
        array_pop($_SESSION['materias']);
        array_pop($_SESSION['notas']);
    }
    
    if (isset($_POST['salvar_edicoes'])) {
        // Salvar as edições feitas pelo usuário
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'materia_') === 0) {
                // Atribuir as matérias
                $linha = substr($key, 8);  // Extrai o índice da linha
                $_SESSION['materias'][$linha] = $value;
            }
            if (strpos($key, 'nota_') === 0) {
                // Atribuir as notas
                list($linha, $bimestre) = explode('_', substr($key, 5)); // Extrai linha e bimestre
                $_SESSION['notas'][$linha][$bimestre] = $value;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notas e Médias</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
        }

        .container {
            width: 90%;
            margin: 0 auto;
        }

        header {
            background-color: #4E93F5;
            padding: 20px;
            text-align: center;
        }

        header h1 {
            color: white;
        }

        .table-container {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
        }

        .table-container th, .table-container td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .table-container th {
            background-color: #4E93F5;
            color: white;
        }

        .table-container input {
            width: 100%;
            padding: 5px;
            margin: 4px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .buttons {
            margin-top: 20px;
            text-align: center;
        }

        .buttons button {
            margin: 5px;
            padding: 10px;
            background-color: #4E93F5;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .buttons button:hover {
            background-color: #357ad6;
        }

        .resultados {
            margin-top: 30px;
        }

        .resultados table {
            width: 100%;
            border-collapse: collapse;
        }

        .resultados th, .resultados td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .resultados th {
            background-color: #4E93F5;
            color: white;
        }

        .resultados td {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Notas e Cálculo de Médias</h1>
        </header>
        
        <form method="POST">
            <table class="table-container">
                <thead>
                    <tr>
                        <th>Matéria</th>
                        <th>1º Bimestre</th>
                        <th>2º Bimestre</th>
                        <th>3º Bimestre</th>
                        <th>4º Bimestre</th>
                        <th>Média</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Exibir as matérias e notas armazenadas na sessão
                    $max_linhas = count($_SESSION['materias']); // Quantidade de matérias

                    for ($i = 0; $i < $max_linhas; $i++) {
                        echo '<tr>';
                        echo '<td><input type="text" name="materia_' . $i . '" value="' . htmlspecialchars((string)$_SESSION['materias'][$i]) . '" placeholder="Matéria"></td>';

                        // Exibir os campos para as notas (4 bimestres por matéria)
                        for ($b = 1; $b <= 4; $b++) {
                            $nota = isset($_SESSION['notas'][$i][$b]) ? (string)$_SESSION['notas'][$i][$b] : '';
                            echo '<td><input type="number" name="nota_' . $i . '_' . $b . '" value="' . htmlspecialchars($nota) . '" placeholder="Nota ' . $b . '"></td>';
                        }

                        // Calcular a média
                        $notas = $_SESSION['notas'][$i] ?? [];
                        $media = count($notas) > 0 ? array_sum($notas) / count($notas) : 0;
                        echo '<td>' . number_format($media, 2) . '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>

            <div class="buttons">
                <button type="submit" name="adicionar_linha">Adicionar Linha</button>
                <button type="submit" name="remover_linha">Remover Linha</button>
                <button type="submit" name="salvar_edicoes">Salvar Edições</button>
            </div>
        </form>

        <div class="resultados">
            <h2>Resultados e Médias</h2>
            <table>
                <thead>
                    <tr>
                        <th>Matéria</th>
                        <th>1º Bimestre</th>
                        <th>2º Bimestre</th>
                        <th>3º Bimestre</th>
                        <th>4º Bimestre</th>
                        <th>Média</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($_SESSION['materias'] as $index => $materia) {
                        $notas = $_SESSION['notas'][$index] ?? [];
                        $media = count($notas) > 0 ? array_sum($notas) / count($notas) : 0;
                        echo "<tr>
                                <td>" . htmlspecialchars($materia) . "</td>
                                <td>" . (isset($notas[1]) ? $notas[1] : '') . "</td>
                                <td>" . (isset($notas[2]) ? $notas[2] : '') . "</td>
                                <td>" . (isset($notas[3]) ? $notas[3] : '') . "</td>
                                <td>" . (isset($notas[4]) ? $notas[4] : '') . "</td>
                                <td>" . number_format($media, 2) . "</td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
