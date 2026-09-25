<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk JSON/CSV Multi-Language Import & Export Studio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f5f7fb; font-family: Arial, sans-serif; }
        .dashboard-container { max-width: 1200px; margin: 40px auto; }
        .dashboard-header { background: linear-gradient(135deg, #4f46e5, #7c3aed); color: white; border-radius: 18px; padding: 30px; margin-bottom: 25px; }
        .card { border: none; border-radius: 16px; box-shadow: 0 5px 20px rgba(0,0,0,0.06); padding: 25px; }
        pre { background: #111827; color: #e5e7eb; padding: 15px; border-radius: 8px; font-size: 13px; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Header -->
        <div class="dashboard-header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="h2 mb-1">📂 Bulk Import & Export Studio</h1>
                <p class="mb-0 opacity-75">Import or export 100+ multilingual posts and translations via JSON & CSV.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ url('/') }}" class="btn btn-light">← All Posts</a>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-light">📊 Dashboard</a>
                <a href="{{ route('translator.index') }}" class="btn btn-outline-light">🌐 Translator</a>
                <a href="{{ route('analytics.index') }}" class="btn btn-outline-light">📈 Analytics</a>
                <a href="{{ route('bulk.manage') }}" class="btn btn-light fw-bold">📂 Bulk Manage</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                ✅ {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                ❌ {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4 mb-4">
            <!-- JSON Bulk Importer -->
            <div class="col-md-6">
                <div class="card h-100">
                    <h4 class="mb-3">📥 JSON Multi-Language Importer</h4>
                    <form action="{{ route('bulk.import.json') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Upload JSON File:</label>
                            <input type="file" name="json_file" class="form-control" accept=".json,.txt">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Or Paste JSON Content:</label>
                            <textarea name="json_text" class="form-control" rows="5" placeholder='[{"author":"Tech Editor","title_en":"Laravel 12 Release","content_en":"New features","title_hi":"लारवेल 12 रिलीज़","content_hi":"नई विशेषताएं"}]'></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold">🚀 Import JSON Batch</button>
                    </form>
                </div>
            </div>

            <!-- CSV Bulk Importer -->
            <div class="col-md-6">
                <div class="card h-100">
                    <h4 class="mb-3">📄 CSV Multi-Language Importer</h4>
                    <form action="{{ route('bulk.import.csv') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Upload CSV File:</label>
                            <input type="file" name="csv_file" class="form-control" accept=".csv" required>
                        </div>
                        <p class="text-muted small">CSV Format: <code>Author, Title EN, Content EN, Title HI, Content HI</code></p>
                        <button type="submit" class="btn btn-success w-100 fw-bold">🚀 Import CSV Batch</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Export Package Card -->
        <div class="card">
            <h4 class="mb-3">📤 Export Complete Multilingual Datasets</h4>
            <p class="text-muted">Export all posts and their translations for offline translation, backup, or localization teams.</p>
            
            <div class="d-flex gap-3 flex-wrap">
                <a href="{{ route('bulk.export.json') }}" class="btn btn-dark btn-lg fw-bold">📦 Export Full JSON Package</a>
                <a href="{{ url('/posts/export/csv') }}" class="btn btn-success btn-lg fw-bold">📄 Export Full CSV Package</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
