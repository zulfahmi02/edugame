<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Game Baru - Dashboard Guru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .navbar {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            padding: 1rem 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            color: white !important;
            font-weight: 700;
            font-size: 1.5rem;
            text-decoration: none;
        }

        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            font-weight: 600;
            margin: 0 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white !important;
        }

        .btn-logout {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid white;
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-logout:hover {
            background: white;
            color: #f5576c;
        }

        .container-main {
            max-width: 900px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: #64748b;
        }

        .form-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            padding: 2.5rem;
        }

        .form-section {
            margin-bottom: 2rem;
        }

        .form-section-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-control,
        .form-select {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #f093fb;
            box-shadow: 0 0 0 3px rgba(240, 147, 251, 0.2);
        }

        .template-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .template-option {
            position: relative;
        }

        .template-option input[type="radio"] {
            position: absolute;
            opacity: 0;
        }

        .template-option label {
            display: block;
            padding: 1.5rem;
            background: #f8fafc;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .template-option input[type="radio"]:checked + label {
            border-color: #f093fb;
            background: linear-gradient(135deg, rgba(240, 147, 251, 0.1) 0%, rgba(245, 87, 108, 0.1) 100%);
        }

        .template-option label:hover {
            border-color: #f093fb;
            transform: translateY(-3px);
        }

        .template-icon {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .template-name {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.3rem;
        }

        .template-type {
            font-size: 0.8rem;
            color: #64748b;
        }

        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 2rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 1.1rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-back {
            color: #64748b;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .btn-back:hover {
            color: #1e293b;
        }

        .alert {
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }

        .empty-templates {
            text-align: center;
            padding: 3rem;
            background: #fef3c7;
            border-radius: 12px;
            color: #92400e;
        }

        @media (max-width: 768px) {
            .template-grid {
                grid-template-columns: 1fr;
            }

            .container-main {
                padding: 0 1rem;
            }

            .form-card {
                padding: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('teacher.dashboard') }}">
                👨‍🏫 Dashboard Guru
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('teacher.dashboard') }}">📊 Statistik</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('teacher.games') }}">🎮 Game Saya</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('teacher.schedules') }}">📅 Jadwal</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <span class="text-white me-3">{{ session('teacher_name') }}</span>
                    <a href="{{ route('teacher.logout') }}" class="btn-logout">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-main">
        <a href="{{ route('teacher.games') }}" class="btn-back">
            ← Kembali ke Game Saya
        </a>

        <div class="page-header">
            <h1 class="page-title">➕ Buat Game Baru</h1>
            <p class="page-subtitle">Pilih template yang disediakan admin dan buat game kuis Anda sendiri</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-card">
            @if($templates->count() > 0)
                <form action="{{ route('teacher.games.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Pilih Template -->
                    <div class="form-section">
                        <h3 class="form-section-title">📋 Pilih Template Game</h3>
                        <div class="template-grid">
                            @foreach($templates as $template)
                                <div class="template-option">
                                    <input type="radio" name="template_id" id="template_{{ $template->id }}" value="{{ $template->id }}" {{ old('template_id') == $template->id ? 'checked' : '' }} required>
                                    <label for="template_{{ $template->id }}">
                                        <div class="template-icon">{{ $template->icon ?? '🎮' }}</div>
                                        <div class="template-name">{{ $template->name }}</div>
                                        <div class="template-type">
                                            {{ \Illuminate\Support\Str::limit($template->description ?? (\App\Models\GameTemplate::getAvailableTypes()[$template->template_type] ?? $template->template_type), 70) }}
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Info Game -->
                    <div class="form-section">
                        <h3 class="form-section-title">🎯 Informasi Game</h3>

                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Game *</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" placeholder="Contoh: Kuis Matematika Kelas 5" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Deskripsi singkat tentang game ini...">{{ old('description') }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Kategori</label>
                                <input type="text" class="form-control" id="category" name="category" value="{{ old('category') }}" placeholder="Contoh: Matematika, IPA, Sejarah" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="class" class="form-label">Pilih Kelas</label>
                                <select class="form-select" id="class" name="class">
                                    <option value="">Semua Kelas</option>
                                    @for($i = 1; $i <= 6; $i++)
                                        <option value="{{ $i }}" {{ old('class') == $i ? 'selected' : '' }}>Kelas {{ $i }}</option>
                                    @endfor
                                </select>
                                <small class="text-muted">Game hanya muncul untuk siswa di kelas ini.</small>
                            </div>
                        </div>
                    </div>

                    <!-- Gambar Game Section -->
                    <div class="form-section">
                        <h3 class="form-section-title">🖼️ Gambar Game (Opsional)</h3>
                        <div class="mb-3">
                            <label for="game_images" class="form-label">Upload Gambar</label>
                            <input type="file" class="form-control" id="game_images" name="game_images[]" 
                                   accept="image/png,image/jpeg,image/jpg,image/webp" multiple>
                            <small class="text-muted">Upload hingga 5 gambar (PNG, JPG, WEBP). Maksimal 2MB per file. Gambar akan membuat game lebih menarik!</small>
                        </div>
                        <div id="image-preview" class="d-flex gap-2 flex-wrap mt-2"></div>
                    </div>

                    <button type="submit" class="btn-submit">
                        🚀 Buat Game & Lanjut Tambah Soal
                    </button>
                </form>
            @else
                <div class="empty-templates">
                    <h3>⚠️ Belum Ada Template</h3>
                    <p>Admin belum membuat template game. Silakan hubungi admin untuk membuat template terlebih dahulu.</p>
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Image preview functionality
        document.getElementById('game_images')?.addEventListener('change', function(e) {
            const preview = document.getElementById('image-preview');
            preview.innerHTML = '';
            
            if (this.files && this.files.length > 0) {
                // Limit to 5 files
                if (this.files.length > 5) {
                    alert('Maksimal 5 gambar yang bisa diupload');
                    this.value = '';
                    return;
                }
                
                [...this.files].forEach((file, index) => {
                    // Check file size (2MB = 2048KB)
                    if (file.size > 2048 * 1024) {
                        alert(`File ${file.name} terlalu besar. Maksimal 2MB per file.`);
                        return;
                    }
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.style.position = 'relative';
                        
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.width = '100px';
                        img.style.height = '100px';
                        img.style.objectFit = 'cover';
                        img.style.borderRadius = '8px';
                        img.style.border = '2px solid #e5e7eb';
                        
                        div.appendChild(img);
                        preview.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                });
            }
        });
    </script>
</body>

</html>
