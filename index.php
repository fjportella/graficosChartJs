<?php
// Função para gerar cores dinâmicas (HSL)
function gerarCoresDinamicas($quantidade) {
    $cores = [];
    for ($i = 0; $i < $quantidade; $i++) {
        // Varia o hue (0-360) para cores distintas
        $hue = ($i * 360 / $quantidade) % 360;
        $cor = "hsl($hue, 70%, 60%)"; // Saturação 70%, luminosidade 60%
        $cores[] = $cor;
    }
    return $cores;
}

// Array de cores predefinidas (mínimo 12, reutilizável)
$cores_predefinidas = [
    'rgba(255, 99, 132, 0.6)',  // Vermelho
    'rgba(54, 162, 235, 0.6)',  // Azul
    'rgba(75, 192, 75, 0.6)',   // Verde
    'rgba(255, 205, 86, 0.6)',  // Amarelo
    'rgba(153, 102, 255, 0.6)', // Roxo
    'rgba(255, 159, 64, 0.6)',  // Laranja
    'rgba(201, 203, 207, 0.6)', // Cinza
    'rgba(255, 99, 255, 0.6)',  // Rosa
    'rgba(50, 205, 50, 0.6)',   // Verde lima
    'rgba(255, 69, 0, 0.6)',    // Vermelho escuro
    'rgba(0, 191, 255, 0.6)',   // Azul claro
    'rgba(139, 69, 19, 0.6)',   // Marrom
    'rgba(148, 0, 211, 0.6)',   // Roxo escuro
    'rgba(127, 255, 212, 0.6)'  // Turquesa
];

// Array associativo 1: Lucro e Resultado
$dados_lucro_resultado = [
    ['mes' => 'Janeiro', 'lucro' => 1200, 'resultado' => 2200],
    ['mes' => 'Fevereiro', 'lucro' => 1900, 'resultado' => 3900],
    ['mes' => 'Março', 'lucro' => 300, 'resultado' => 4300]
];

// Array associativo 2: Custo
$dados_custo = [
    ['mes' => 'Janeiro', 'custo' => 800],
    ['mes' => 'Fevereiro', 'custo' => 1500],
    ['mes' => 'Março', 'custo' => 1200]
];

// Preparar dados para o Chart.js usando foreach
$meses = [];
$lucros = [];
$resultados = [];
$custos = [];

// Array 1: Lucro e Resultado
foreach ($dados_lucro_resultado as $dado) {
    $meses[] = $dado['mes'];
    $lucros[] = $dado['lucro'];
    $resultados[] = $dado['resultado'];
}

// Array 2: Custo
foreach ($dados_custo as $dado) {
    $custos[] = $dado['custo'];
}

// Gerar cores dinâmicas para o número de meses
$cores_dinamicas = gerarCoresDinamicas(count($meses));

// Converter arrays para JSON
$meses_json = json_encode($meses);
$lucros_json = json_encode($lucros);
$resultados_json = json_encode($resultados);
$custos_json = json_encode($custos);
$cores_dinamicas_json = json_encode($cores_dinamicas);
$cores_predefinidas_json = json_encode(array_slice($cores_predefinidas, 0, count($meses)));
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gráficos Financeiros com Chart.js e PHP</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        canvas { max-width: 600px; margin: 20px 0; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <!-- Gráfico 1: Lucro e Resultado (barras) -->
    <h2>Desempenho Mensal de Investimentos</h2>
    <canvas id="graficoLucroResultado"></canvas>

    <!-- Gráfico 2: Custo (barras) -->
    <h2>Custo Mensal de Investimentos</h2>
    <canvas id="graficoCusto"></canvas>

    <!-- Gráfico 3: Custo (pizza, cores dinâmicas) -->
    <h2>Distribuição de Custos por Mês (Cores Dinâmicas)</h2>
    <canvas id="graficoCustoPizzaDinamico"></canvas>

    <!-- Gráfico 4: Custo e Resultado (linhas) -->
    <h2>Evolução de Custos e Resultados</h2>
    <canvas id="graficoCustoResultadoLinhas"></canvas>

    <!-- Gráfico 5: Custo (pizza, cores predefinidas) -->
    <h2>Distribuição de Custos por Mês (Cores Predefinidas)</h2>
    <canvas id="graficoCustoPizzaPredefinido"></canvas>

    <!-- Inclui o chart.umd.js -->
    <script src="js/chart.umd.js"></script>

    <!-- Código JavaScript para os gráficos -->
    <script>
        // Dados do PHP
        const meses = <?php echo $meses_json; ?>;
        const lucros = <?php echo $lucros_json; ?>;
        const resultados = <?php echo $resultados_json; ?>;
        const custos = <?php echo $custos_json; ?>;
        const coresDinamicas = <?php echo $cores_dinamicas_json; ?>;
        const coresPredefinidas = <?php echo $cores_predefinidas_json; ?>;

        // Gráfico 1: Lucro e Resultado (barras)
        const ctx1 = document.getElementById('graficoLucroResultado').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: meses,
                datasets: [
                    {
                        label: 'Lucro (R$)',
                        data: lucros,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Resultado (R$)',
                        data: resultados,
                        backgroundColor: 'rgba(0, 128, 0, 0.2)',
                        borderColor: 'rgba(0, 128, 0, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, title: { display: true, text: 'Valor (R$)' } },
                    x: { title: { display: true, text: 'Mês' } }
                },
                plugins: {
                    legend: { display: true, position: 'top' },
                    title: { display: true, text: 'Desempenho Mensal de Investimentos' }
                },
                /* barPercentage: 0.4,
                categoryPercentage: 0.8 */
            }
        });

        // Gráfico 2: Custo (barras)
        const ctx2 = document.getElementById('graficoCusto').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: meses,
                datasets: [
                    {
                        label: 'Custo (R$)',
                        data: custos,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, title: { display: true, text: 'Custo (R$)' } },
                    x: { title: { display: true, text: 'Mês' } }
                },
                plugins: {
                    legend: { display: true, position: 'top' },
                    title: { display: true, text: 'Custo Mensal de Investimentos' }
                }
            }
        });

        // Gráfico 3: Custo (pizza, cores dinâmicas)
        const ctx3 = document.getElementById('graficoCustoPizzaDinamico').getContext('2d');
        new Chart(ctx3, {
            type: 'pie',
            data: {
                labels: meses,
                datasets: [
                    {
                        label: 'Custo (R$)',
                        data: custos,
                        backgroundColor: coresDinamicas,
                        borderColor: coresDinamicas.map(cor => cor.replace('60%)', '50%)')),
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true, position: 'top' },
                    title: { display: true, text: 'Distribuição de Custos por Mês (Cores Dinâmicas)' }
                }
            }
        });

        // Gráfico 4: Custo e Resultado (linhas)
        const ctx4 = document.getElementById('graficoCustoResultadoLinhas').getContext('2d');
        new Chart(ctx4, {
            type: 'line',
            data: {
                labels: meses,
                datasets: [
                    {
                        label: 'Custo (R$)',
                        data: custos,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 2,
                        fill: false,
                        tension: 0.4
                    },
                    {
                        label: 'Resultado (R$)',
                        data: resultados,
                        backgroundColor: 'rgba(0, 128, 0, 0.2)',
                        borderColor: 'rgba(0, 128, 0, 1)',
                        borderWidth: 2,
                        fill: false,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, title: { display: true, text: 'Valor (R$)' } },
                    x: { title: { display: true, text: 'Mês' } }
                },
                plugins: {
                    legend: { display: true, position: 'top' },
                    title: { display: true, text: 'Evolução de Custos e Resultados' }
                }
            }
        });

        // Gráfico 5: Custo (pizza, cores predefinidas)
        const ctx5 = document.getElementById('graficoCustoPizzaPredefinido').getContext('2d');
        new Chart(ctx5, {
            type: 'pie',
            data: {
                labels: meses,
                datasets: [
                    {
                        label: 'Custo (R$)',
                        data: custos,
                        backgroundColor: coresPredefinidas,
                        borderColor: coresPredefinidas.map(cor => cor.replace('0.6', '1')),
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true, position: 'top' },
                    title: { display: true, text: 'Distribuição de Custos por Mês (Cores Predefinidas)' }
                }
            }
        });
    </script>
</body>
</html>