<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto-Translation & AI Language Translator Studio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f5f7fb; font-family: Arial, sans-serif; }
        .dashboard-container { max-width: 1200px; margin: 40px auto; }
        .dashboard-header { background: linear-gradient(135deg, #4f46e5, #7c3aed); color: white; border-radius: 18px; padding: 30px; margin-bottom: 25px; }
        .card { border: none; border-radius: 16px; box-shadow: 0 5px 20px rgba(0,0,0,0.06); padding: 25px; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Header -->
        <div class="dashboard-header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="h2 mb-1">🌐 Auto-Translation Studio</h1>
                <p class="mb-0 opacity-75">1-Click synchronized multilingual content translator playground.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ url('/') }}" class="btn btn-light">← All Posts</a>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-light">📊 Dashboard</a>
                <a href="{{ route('translator.index') }}" class="btn btn-light fw-bold">🌐 Translator</a>
                <a href="{{ route('analytics.index') }}" class="btn btn-outline-light">📈 Analytics</a>
                <a href="{{ route('bulk.manage') }}" class="btn btn-outline-light">📂 Bulk Manage</a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <h4 class="mb-3">🔤 English Source Text</h4>
                    <form action="{{ route('translator.auto') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Enter Text / Title / Content:</label>
                            <textarea name="text" class="form-control" rows="6" placeholder="Type English content here (e.g. Welcome to Laravel 12 Multilingual Framework)..." required>{{ session('original_text') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Target Language:</label>
                            <select name="target" class="form-select">
                                <option value="hi" selected>Hindi (हिन्दी)</option>
                                <option value="en">English (Original)</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold">⚡ 1-Click Auto Translate</button>
                    </form>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100">
                    <h4 class="mb-3">🇮🇳 Translated Result</h4>
                    @if(session('translated_text'))
                        <div class="p-3 bg-light border rounded mb-3" style="min-height: 180px;">
                            <p class="fs-5 text-dark font-monospace mb-0">{{ session('translated_text') }}</p>
                        </div>
                        <div class="alert alert-success py-2">
                            ✅ Translation generated successfully! Copy and paste directly into post form.
                        </div>
                    @else
                        <div class="p-4 bg-light border rounded text-center text-muted mb-3" style="min-height: 180px; display: flex; align-items: center; justify-content: center;">
                            <div>
                                <span class="fs-1">🌐</span>
                                <p class="mb-0 mt-2">Enter text on the left and click <b>Auto Translate</b> to preview instant translation results.</p>
                            </div>
                        </div>
                    @endif

                    <div class="mt-auto">
                        <h6 class="fw-bold mb-2">💡 Sample Text Shortcuts:</h6>
                        <div class="d-flex gap-2 flex-wrap">
                            <button class="btn btn-sm btn-outline-secondary" onclick="document.getElementsByName('text')[0].value='Welcome to Laravel 12 Multilingual Post Framework'">Welcome Guide</button>
                            <button class="btn btn-sm btn-outline-secondary" onclick="document.getElementsByName('text')[0].value='Technology update and business news tutorial'">Tech News</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
