<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multilingual Completeness Analytics & Coverage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background: #f5f7fb; font-family: Arial, sans-serif; }
        .dashboard-container { max-width: 1200px; margin: 40px auto; }
        .dashboard-header { background: linear-gradient(135deg, #4f46e5, #7c3aed); color: white; border-radius: 18px; padding: 30px; margin-bottom: 25px; }
        .card { border: none; border-radius: 16px; box-shadow: 0 5px 20px rgba(0,0,0,0.06); padding: 25px; }
        .stat-number { font-size: 32px; font-weight: 700; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Header -->
        <div class="dashboard-header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="h2 mb-1">📊 Multilingual Completeness Analytics</h1>
                <p class="mb-0 opacity-75">Language coverage heatmap & missing translation inspection dashboard.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ url('/') }}" class="btn btn-light">← All Posts</a>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-light">📊 Dashboard</a>
                <a href="{{ route('translator.index') }}" class="btn btn-outline-light">🌐 Translator</a>
                <a href="{{ route('analytics.index') }}" class="btn btn-light fw-bold">📈 Analytics</a>
                <a href="{{ route('bulk.manage') }}" class="btn btn-outline-light">📂 Bulk Manage</a>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="stat-number text-primary">{{ $analytics['summary']['total_posts'] }}</div>
                    <div class="text-muted fw-bold">Total Posts</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="stat-number text-success">{{ $analytics['summary']['en_percentage'] }}%</div>
                    <div class="text-muted fw-bold">English Coverage</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="stat-number text-warning">{{ $analytics['summary']['hi_percentage'] }}%</div>
                    <div class="text-muted fw-bold">Hindi Coverage</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="stat-number text-info">{{ $analytics['summary']['full_percentage'] }}%</div>
                    <div class="text-muted fw-bold">Fully Translated</div>
                </div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card">
                    <h4 class="mb-3">🍩 Language Coverage Heatmap</h4>
                    <div style="height: 260px;">
                        <canvas id="coverageChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <h4 class="mb-3">📊 Translation Status Breakdown</h4>
                    <div style="height: 260px;">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Missing Translations Action Table -->
        <div class="card">
            <h4 class="mb-3 d-flex justify-content-between align-items-center">
                <span>⚠️ Missing Translations Queue</span>
                <span class="badge bg-warning text-dark">{{ count($analytics['missing_posts']) }} Pending</span>
            </h4>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Author</th>
                            <th>English Status</th>
                            <th>Hindi Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($analytics['missing_posts'] as $post)
                            <tr>
                                <td>#{{ $post['id'] }}</td>
                                <td><b>{{ $post['author'] }}</b></td>
                                <td>
                                    @if($post['has_en'])
                                        <span class="badge bg-success">✓ English Present</span>
                                    @else
                                        <span class="badge bg-danger">✗ English Missing</span>
                                    @endif
                                </td>
                                <td>
                                    @if($post['has_hi'])
                                        <span class="badge bg-success">✓ Hindi Present</span>
                                    @else
                                        <span class="badge bg-danger">✗ Hindi Missing</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('translations.edit', $post['id']) }}" class="btn btn-sm btn-primary fw-bold">📝 Fix Translation</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">🎉 All posts are 100% translated across English and Hindi!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Coverage Chart
            const coverageCtx = document.getElementById('coverageChart').getContext('2d');
            new Chart(coverageCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($analytics['chart_locales']) !!},
                    datasets: [{
                        data: {!! json_encode($analytics['chart_coverage']) !!},
                        backgroundColor: ['#10b981', '#f59e0b'],
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });

            // Status Chart
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($analytics['chart_status_labels']) !!},
                    datasets: [{
                        label: 'Post Count',
                        data: {!! json_encode($analytics['chart_status_data']) !!},
                        backgroundColor: ['#3b82f6', '#f59e0b', '#ef4444'],
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
