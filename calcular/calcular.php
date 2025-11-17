<?php
session_start();

/* =====================
   CONFIG PADRÃO
   ===================== */
if (!isset($_SESSION['materias'])) {
    $_SESSION['materias'] = [];
}
if (!isset($_SESSION['notas'])) {
    $_SESSION['notas'] = [];
}
// nota máxima e média de aprovação (pode ser 10 / 7, 100 / 60, etc)
if (!isset($_SESSION['nota_maxima'])) {
    $_SESSION['nota_maxima'] = 10;
}
if (!isset($_SESSION['media_aprovacao'])) {
    $_SESSION['media_aprovacao'] = 6;
}

/* =====================
   TRATAMENTO POST
   ===================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* 0) SEMPRE salvar o que já está digitado na tela
          (independente de qual botão apertou) */
    foreach ($_POST as $key => $value) {
        // Matéria
        if (strpos($key, 'materia_') === 0) {
            $linha = (int) substr($key, 8); // depois de "materia_"
            $_SESSION['materias'][$linha] = $value;
        }

        // Nota: nota_linha_avaliacao
        if (strpos($key, 'nota_') === 0) {
            $parte = substr($key, 5); // tira "nota_"
            list($linha, $avaliacao) = explode('_', $parte);
            $linha     = (int)$linha;
            $avaliacao = (int)$avaliacao;

            if (!isset($_SESSION['notas'][$linha])) {
                $_SESSION['notas'][$linha] = [1 => null, 2 => null, 3 => null, 4 => null];
            }

            $value = trim($value);
            $_SESSION['notas'][$linha][$avaliacao] = $value === '' ? null : (float)$value;
        }
    }

    /* 1) Configurações de nota máxima e média de aprovação */
    if (isset($_POST['salvar_config'])) {
        $notaMax = isset($_POST['nota_maxima']) ? (float)$_POST['nota_maxima'] : 10;
        $mediaAp = isset($_POST['media_aprovacao']) ? (float)$_POST['media_aprovacao'] : 6;

        if ($notaMax <= 0) $notaMax = 10;
        if ($mediaAp <= 0) $mediaAp = 6;

        $_SESSION['nota_maxima']     = $notaMax;
        $_SESSION['media_aprovacao'] = $mediaAp;
    }

    /* 2) Adicionar / remover linhas */
    if (isset($_POST['adicionar_linha'])) {
        $_SESSION['materias'][] = '';
        $_SESSION['notas'][]    = [1 => null, 2 => null, 3 => null, 4 => null];
    }

    if (isset($_POST['remover_linha']) && count($_SESSION['materias']) > 0) {
        array_pop($_SESSION['materias']);
        array_pop($_SESSION['notas']);
    }

    /* 3) Limpar linha específica */
    if (isset($_POST['limpar_linha']) && isset($_POST['linha_index'])) {
        $idx = (int)$_POST['linha_index'];
        if (isset($_SESSION['materias'][$idx])) {
            $_SESSION['materias'][$idx] = '';
            $_SESSION['notas'][$idx]    = [1 => null, 2 => null, 3 => null, 4 => null];
        }
    }

    /* 4) Limpar tudo */
    if (isset($_POST['limpar_tudo'])) {
        $_SESSION['materias'] = [];
        $_SESSION['notas']    = [];
    }

    /* 5) Se tiver botão salvar_edicoes, beleza, mas agora é opcional
          porque já salvamos tudo lá no bloco 0 */
    // pode até remover esse if se quiser
}


/* =====================
   FUNÇÕES AUXILIARES
   ===================== */
$notaMaxima     = $_SESSION['nota_maxima'];
$mediaAprovacao = $_SESSION['media_aprovacao'];

// Calcula média e status de uma matéria
function calcularMediaEStatus(array $notas, float $mediaAprovacao) {
    $validas = [];
    foreach ($notas as $n) {
        if ($n !== null && $n !== '') {
            $validas[] = (float)$n;
        }
    }

    if (count($validas) === 0) {
        return ['media' => 0, 'status' => '-', 'precisa' => null];
    }

    $soma  = array_sum($validas);
    $media = $soma / count($validas);

    // status básico
    if ($media >= $mediaAprovacao) {
        $status = 'Aprovado';
    } elseif ($media >= $mediaAprovacao * 0.5) {
        $status = 'Recuperação';
    } else {
        $status = 'Reprovado';
    }

    return ['media' => $media, 'status' => $status, 'precisa' => null];
}

// Calcula quanto falta na "próxima avaliação" (considerando 4 avaliações)
function calcularQuantoPrecisa(array $notas, float $mediaAlvo, float $notaMaxima) {
    // Próxima = a primeira avaliação vazia (1 a 4)
    $totalAvaliacoes = 4;
    $indiceProxima   = null;
    $somaFeitas      = 0;
    $feitas          = 0;

    for ($i = 1; $i <= $totalAvaliacoes; $i++) {
        $n = $notas[$i] ?? null;
        if ($n !== null && $n !== '') {
            $somaFeitas += (float)$n;
            $feitas++;
        } else if ($indiceProxima === null) {
            $indiceProxima = $i;
        }
    }

    // Se já fez todas ou nenhuma -> não tem "próxima prova" clara
    if ($indiceProxima === null || $feitas === 0) {
        return null;
    }

    // (somaFeitas + x) / totalAvaliacoes = mediaAlvo
    $necessaria = $mediaAlvo * $totalAvaliacoes - $somaFeitas;

    if ($necessaria < 0) $necessaria = 0;

    // Se passa da nota máxima, significa que não dá pra alcançar só com essa prova
    if ($necessaria > $notaMaxima) {
        return 'Impossível';
    }

    return $necessaria;
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>FOAG — Notas e Médias</title>

  <link rel="stylesheet" href="notas.css" />
  <link rel="stylesheet" href="dark_agenda.css">

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

  <script src="../m.escuro/dark-mode.js"></script>
</head>

<body>
  <header class="cabecalho">
    FOAG
    <div class="header-icons">
      <i id="themeToggle" class="fa-solid fa-moon" title="Modo Escuro"></i>
      <i id="icon-perfil" class="fa-regular fa-user" title="Perfil"></i>
      <i id="icon-fogi" class="fa-solid fa-robot" title="Assistente FOAG — FOGi"></i>
      <i id="icon-sair" class="fa-solid fa-right-from-bracket" title="Sair"></i>
    </div>
  </header>

  <div class="container">
    <nav class="menu">
      <a href="../inicio/sla.php">Início</a>
      <a href="../calendario/calendario.php">Calendário</a>
      <a href="../HORARIO/horario.php">Horário</a>
      <a href="../sobre/sobre.html">Sobre Nós</a>
      <a href="../contato/contato.php">Contato</a>
      <a href="#" class="ativo">Notas & Médias</a>
    </nav>

    <main class="main-content">

      <!-- CARD CONFIGURAÇÕES -->
      <section class="card-notas card-config">
        <h2 class="titulo-tabela">Configurações de notas</h2>
        <p class="sub-notas">
          Ajuste a nota máxima e a média mínima para aprovação.  
          Serve tanto pra escola quanto pra faculdade (ex.: 10/7, 100/60…).
        </p>
        <form method="POST" class="config-form">
          <div class="config-field">
            <label for="nota_maxima">Nota máxima</label>
            <input type="number" step="0.01" id="nota_maxima" name="nota_maxima"
                   value="<?php echo htmlspecialchars($notaMaxima); ?>" min="1">
          </div>
          <div class="config-field">
            <label for="media_aprovacao">Média para aprovação</label>
            <input type="number" step="0.01" id="media_aprovacao" name="media_aprovacao"
                   value="<?php echo htmlspecialchars($mediaAprovacao); ?>" min="0">
          </div>
          <button type="submit" name="salvar_config" class="btn-destaque">Salvar configurações</button>
        </form>
      </section>

      <!-- CARD PRINCIPAL DE NOTAS -->
      <section class="card-notas">
        <h2 class="titulo-tabela">Notas e cálculo de médias</h2>
        <p class="sub-notas">
          Preencha apenas as avaliações que já aconteceram.  
          Deixe em branco o que ainda não teve prova/trabalho. A média é calculada só com o que já existe.
        </p>

        <form method="POST">
          <table class="tabela-notas">
            <thead>
              <tr>
                <th>Matéria / Disciplina</th>
                <th>Avaliação 1</th>
                <th>Avaliação 2</th>
                <th>Avaliação 3</th>
                <th>Avaliação 4</th>
                <th>Média</th>
                <th>Situação</th>
                <th>Precisa (próx.)</th>
                <th>Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $materias = $_SESSION['materias'];
              $notasAll = $_SESSION['notas'];

              if (count($materias) === 0) {
                  echo '<tr class="linha-vazia">
                          <td colspan="9">
                            Nenhuma matéria cadastrada ainda. Clique em <strong>Adicionar matéria</strong> para começar.
                          </td>
                        </tr>';
              } else {
                  foreach ($materias as $i => $materia) {
                      $materia = htmlspecialchars((string)$materia);
                      $notas   = $notasAll[$i] ?? [1 => null, 2 => null, 3 => null, 4 => null];

                      $dados = calcularMediaEStatus($notas, $mediaAprovacao);
                      $media = $dados['media'];
                      $status = $dados['status'];

                      // quanto precisa na próxima aval.
                      $precisa = calcularQuantoPrecisa($notas, $mediaAprovacao, $notaMaxima);

                      // classes de status pra colorir
                      $statusClass = '';
                      if ($status === 'Aprovado') $statusClass = 'status-aprovado';
                      elseif ($status === 'Recuperação') $statusClass = 'status-recuperacao';
                      elseif ($status === 'Reprovado') $statusClass = 'status-reprovado';

                      echo '<tr>';
                      echo '<td><input type="text" name="materia_' . $i . '" value="' . $materia . '" placeholder="Ex: Cálculo I"></td>';

                      for ($a = 1; $a <= 4; $a++) {
                          $notaVal = $notas[$a] ?? null;
                          $notaStr = ($notaVal !== null && $notaVal !== '') ? (string)$notaVal : '';
                          echo '<td><input type="number" step="0.01" name="nota_' . $i . '_' . $a . '" value="' . htmlspecialchars($notaStr) . '" placeholder="Ex: 7.5" max="' . htmlspecialchars($notaMaxima) . '"></td>';
                      }

                      echo '<td class="celula-media">' . number_format($media, 2, ',', '.') . '</td>';
                      echo '<td class="celula-status"><span class="badge-status ' . $statusClass . '">' . $status . '</span></td>';

                      echo '<td class="celula-precisa">';
                      if ($precisa === null) {
                          echo '-';
                      } elseif ($precisa === 'Impossível') {
                          echo '<span class="badge-precisa impossivel">Impossível</span>';
                      } else {
                          echo '≈ ' . number_format($precisa, 2, ',', '.');
                      }
                      echo '</td>';

                      // botão limpar linha
                      echo '<td>
                              <button type="submit" name="limpar_linha" value="1" class="btn-linha"
                                      onclick="document.getElementById(\'linha_index\').value=' . $i . ';">
                                Limpar
                              </button>
                            </td>';

                      echo '</tr>';
                  }
              }
              ?>
            </tbody>
          </table>

          <!-- hidden pra saber qual linha limpar -->
          <input type="hidden" id="linha_index" name="linha_index" value="">

          <div class="buttons-notas">
            <button type="submit" name="adicionar_linha">Adicionar matéria</button>
            <button type="submit" name="remover_linha">Remover última</button>
            <button type="submit" name="limpar_tudo">Limpar tudo</button>
            <button type="submit" name="salvar_edicoes" class="btn-destaque">Salvar alterações</button>
          </div>
        </form>
      </section>

      <!-- CARD RESUMO GERAL -->
      <section class="card-notas">
        <h2 class="titulo-tabela">Resumo geral</h2>
        <?php
        $totalMaterias = count($materias);
        $aprovadas = $recuperacao = $reprovadas = 0;
        $somaMedias = 0;
        $contMedias = 0;

        foreach ($materias as $i => $materia) {
            $notas  = $notasAll[$i] ?? [1 => null, 2 => null, 3 => null, 4 => null];
            $dados  = calcularMediaEStatus($notas, $mediaAprovacao);
            $media  = $dados['media'];
            $status = $dados['status'];

            if ($status === 'Aprovado') $aprovadas++;
            if ($status === 'Recuperação') $recuperacao++;
            if ($status === 'Reprovado') $reprovadas++;

            if ($media > 0) {
                $somaMedias += $media;
                $contMedias++;
            }
        }

        $mediaGeral = $contMedias > 0 ? $somaMedias / $contMedias : 0;
        ?>
        <div class="resumo-grid">
          <div class="resumo-card">
            <span class="resumo-label">Matérias cadastradas</span>
            <span class="resumo-valor"><?php echo $totalMaterias; ?></span>
          </div>
          <div class="resumo-card aprovado">
            <span class="resumo-label">Aprovado</span>
            <span class="resumo-valor"><?php echo $aprovadas; ?></span>
          </div>
          <div class="resumo-card recuperacao">
            <span class="resumo-label">Recuperação</span>
            <span class="resumo-valor"><?php echo $recuperacao; ?></span>
          </div>
          <div class="resumo-card reprovado">
            <span class="resumo-label">Reprovado</span>
            <span class="resumo-valor"><?php echo $reprovadas; ?></span>
          </div>
          <div class="resumo-card geral">
            <span class="resumo-label">Média geral</span>
            <span class="resumo-valor"><?php echo number_format($mediaGeral, 2, ',', '.'); ?></span>
          </div>
        </div>
      </section>

    </main>
  </div>

  <footer>&copy; 2025 FOAG. Todos os direitos reservados.</footer>

  <script>
    // só pra garantir que limpar linha envia o índice certo
    // (já preenchi no onclick, mas se quiser usar JS depois tá aqui o gancho)
  </script>
</body>
</html>
