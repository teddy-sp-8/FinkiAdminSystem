@extends('layouts.app')

@section('content')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .finki-analytics-wrapper {
            font-family: 'DM Sans', system-ui, -apple-system, sans-serif;
            margin: 24px 0;
            width: 100%;
        }

        .finki-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .finki-stat-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            transition: all 0.25s ease;
            min-height: 110px;
        }

        .finki-stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        }

        .finki-stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .finki-card-total::before {
            background: #64748b;
        }

        .finki-card-pending::before {
            background: #f59e0b;
        }

        .finki-card-approved::before {
            background: #10b981;
        }

        .finki-card-today::before {
            background: #3b82f6;
        }

        .finki-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .finki-stat-label {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }

        .finki-stat-icon {
            font-size: 20px;
            background: #f8fafc;
            padding: 6px;
            border-radius: 8px;
        }

        .finki-stat-value {
            font-size: 36px;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .charts-container {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 25px;
            margin-top: 25px;
        }

        @media (max-width: 768px) {
            .charts-container {
                grid-template-columns: 1fr;
            }
        }

        .chart-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
        }

        .chart-title {
            font-size: 14px;
            font-weight: 700;
            color: #1e293b;
            margin-top: 0;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>

    <div class="finki-analytics-wrapper">
        <div class="finki-grid">
            <div class="finki-stat-card finki-card-total">
                <div class="finki-card-header">
                    <span class="finki-stat-label">Вкупно поднесени</span>
                    <span class="finki-stat-icon">📁</span>
                </div>
                <div class="finki-stat-value">{{ $totalRequests }}</div>
            </div>

            <div class="finki-stat-card finki-card-pending">
                <div class="finki-card-header">
                    <span class="finki-stat-label">Во процес</span>
                    <span class="finki-stat-icon">⏳</span>
                </div>
                <div class="finki-stat-value" style="color: #b45309;">{{ $pendingRequests }}</div>
            </div>

            <div class="finki-stat-card finki-card-approved">
                <div class="finki-card-header">
                    <span class="finki-stat-label">Одобрени</span>
                    <span class="finki-stat-icon">✅</span>
                </div>
                <div class="finki-stat-value" style="color: #047857;">{{ $approvedRequests }}</div>
            </div>

            <div class="finki-stat-card finki-card-today">
                <div class="finki-card-header">
                    <span class="finki-stat-label">Пристигнато денес</span>
                    <span class="finki-stat-icon">⚡</span>
                </div>
                <div class="finki-stat-value" style="color: #1d4ed8;">{{ $todayRequests }}</div>
            </div>


            <div class="finki-stat-card" style="border-left: 5px solid #10b981;">
                <div class="finki-card-header">
                    <span class="finki-stat-label">Вкупен приход </span>
                </div>
                <div>
                    <div class="finki-stat-value" style="color: #047857; font-size: 32px;">
                        {{ number_format($totalRevenue) }}
                    </div>
                    <div style="font-size: 13px; color: #64748b; margin-top: 4px;">
                        {{ number_format($totalRevenueMonthly) }} ден. овој месец
                    </div>
                </div>
            </div>
        </div>

        <div class="charts-container">

            <div class="chart-card">
                <h3 class="chart-title">📊 Статус на сите барања</h3>
                <div style="position: relative; max-height: 280px; display: flex; justify-content: center;">
                    <canvas id="statusPieChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <h3 class="chart-title">📈 Дневна активност</h3>
                <div style="position: relative; height: 280px;">
                    <canvas id="activityBarChart"></canvas>
                </div>
            </div>


        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const pendingCount = {{ $pendingRequests }};
            const approvedCount = {{ $approvedRequests }};
            const otherCount = {{ $totalRequests }} - (pendingCount + approvedCount);

            const ctxPie = document.getElementById('statusPieChart').getContext('2d');
            new Chart(ctxPie, {
                type: 'pie',
                data: {
                    labels: ['Чекаат преглед', 'Одобрени', 'Останато/Одбиено'],
                    datasets: [{
                        data: [pendingCount, approvedCount, otherCount < 0 ? 0 : otherCount],
                        backgroundColor: [
                            '#f59e0b',
                            '#10b981',
                            '#cbd5e1'
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {font: {family: 'DM Sans', size: 12}, padding: 15}
                        }
                    }
                }
            });

            const ctxBar = document.getElementById('activityBarChart').getContext('2d');
            new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: ['Пристигнати денес', 'Вкупно во системот'],
                    datasets: [{
                        label: 'Број на студентски барања',
                        data: [{{ $todayRequests }}, {{ $totalRequests }}],
                        backgroundColor: ['#3b82f6', '#64748b'],
                        borderRadius: 8,
                        barThickness: 40
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {display: false}
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {stepSize: 1, font: {family: 'DM Sans'}},
                            grid: {color: '#f1f5f9'}
                        },
                        x: {
                            grid: {display: false},
                            ticks: {font: {family: 'DM Sans', weight: '600'}}
                        }
                    }
                }
            });
        });
    </script>
@endsection
