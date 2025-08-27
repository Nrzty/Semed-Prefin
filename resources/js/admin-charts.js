import Chart from 'chart.js/auto';
import ChartDataLabels from 'chartjs-plugin-datalabels';

Chart.register(ChartDataLabels);

document.addEventListener('DOMContentLoaded', function () {

    function formatCurrency(value) {
        return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value);
    }

    const pizzaCanvas = document.getElementById('graficoPizzaVerbas');
    if (pizzaCanvas) {
        try {
            const dadosPizza = JSON.parse(pizzaCanvas.dataset.dados).map(Number);
            const totalVerbas = dadosPizza.reduce((a, b) => a + b, 0);

            new Chart(pizzaCanvas, {
                type: 'doughnut',
                data: {
                    labels: ['Custeio', 'Capital'],
                    datasets: [{
                        data: dadosPizza,
                        backgroundColor: ['rgba(59, 130, 246, 0.8)', 'rgba(239, 68, 68, 0.8)'],
                        borderColor: ['#FFFFFF'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: {
                            callbacks: {
                                label: (context) => `${context.label}: ${formatCurrency(context.parsed)}`
                            }
                        },
                        datalabels: {
                            color: '#fff',
                            font: {
                                weight: 'bold',
                                size: 12,
                            },
                            formatter: (value, context) => {
                                if (totalVerbas === 0) return '0%';
                                // Agora o cálculo da percentagem funcionará corretamente.
                                const percentage = (value / totalVerbas * 100).toFixed(1) + '%';
                                return percentage;
                            }
                        }
                    }
                },
            });
        } catch (e) {
            console.error("Erro ao processar dados para o gráfico de pizza:", e, pizzaCanvas.dataset.dados);
        }
    }

    const barrasCanvas = document.getElementById('graficoBarrasProgresso');
    if (barrasCanvas) {
        try {
            const dadosProgresso = JSON.parse(barrasCanvas.dataset.dados);
            new Chart(barrasCanvas, {
                type: 'bar',
                data: {
                    labels: ['1ª Parcela', '2ª Parcela', '3ª Parcela', '4ª Parcela'],
                    datasets: [
                        {
                            label: 'Finalizado',
                            data: dadosProgresso.finalizado,
                            backgroundColor: 'rgba(16, 185, 129, 0.8)'
                        },
                        {
                            label: 'Aberto',
                            data: dadosProgresso.aberto,
                            backgroundColor: 'rgba(245, 158, 11, 0.8)'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { stacked: true, grid: { display: false } },
                        y: { stacked: true, beginAtZero: true }
                    },
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: {
                            callbacks: {
                                label: (context) => `${context.dataset.label}: ${context.parsed.y} repasses`
                            }
                        },
                        datalabels: {
                            color: '#fff',
                            font: {
                                weight: 'bold'
                            },
                            formatter: (value) => {
                                return value > 0 ? value : '';
                            }
                        }
                    }
                }
            });
        } catch (e) {
            console.error("Erro ao processar dados para o gráfico de barras:", e, barrasCanvas.dataset.dados);
        }
    }
});
