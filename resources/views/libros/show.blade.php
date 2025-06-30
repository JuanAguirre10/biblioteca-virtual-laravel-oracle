<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $libro->titulo }} - Biblioteca Virtual</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .book-cover {
            max-width: 200px;
            height: 280px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .book-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .info-card {
            background: rgba(255,255,255,0.95);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }
        .stat-card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark book-info">
        <div class="container">
            <a class="navbar-brand" href="{{ route('libros.index') }}">
                <i class="fas fa-book"></i> Biblioteca Virtual
            </a>
            <div class="navbar-nav">
                <a class="nav-link" href="{{ route('libros.index') }}">
                    <i class="fas fa-book"></i> Libros
                </a>
                <a class="nav-link" href="{{ route('autores.index') }}">
        <i class="fas fa-user-edit"></i> Autores
    </a>
    <a class="nav-link" href="{{ route('categorias.index') }}">
        <i class="fas fa-tags"></i> Categorías
    </a>
                <a class="nav-link" href="{{ route('prestamos.index') }}">
                    <i class="fas fa-handshake"></i> Préstamos
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="info-card p-4">
                    <div class="row">
                        <!-- Imagen del libro -->
                        <div class="col-md-3 text-center mb-4">
                            @php
                                // Crear nombre de imagen basado en el título
                                $imageName = strtolower(str_replace([' ', 'ñ', 'á', 'é', 'í', 'ó', 'ú'], ['', 'n', 'a', 'e', 'i', 'o', 'u'], $libro->titulo)) . '.jpg';
                                $imagePath = 'images/libros/' . $imageName;
                            @endphp
                            
                            @if(file_exists(public_path($imagePath)))
                                <img src="{{ asset($imagePath) }}" alt="{{ $libro->titulo }}" class="book-cover mb-3">
                            @else
                                <div class="book-cover d-flex align-items-center justify-content-center bg-secondary text-white mb-3">
                                    <div class="text-center">
                                        <i class="fas fa-book fa-3x mb-2"></i>
                                        <br><small>Sin Imagen</small>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="d-grid gap-2">
                                <a href="{{ route('libros.edit', $libro->id_libro) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="{{ route('libros.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                            </div>
                        </div>
                        
                        <!-- Información del libro -->
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h1 class="text-primary mb-2">{{ $libro->titulo }}</h1>
                                <h5 class="text-muted mb-3">{{ $libro->autor_completo }}</h5>
                                <span class="badge bg-primary fs-6 mb-3">{{ $libro->nombre_categoria }}</span>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong><i class="fas fa-barcode text-primary"></i> ISBN:</strong>
                                        <br>{{ $libro->isbn ?? 'No registrado' }}
                                    </div>
                                    <div class="mb-3">
                                        <strong><i class="fas fa-building text-primary"></i> Editorial:</strong>
                                        <br>{{ $libro->editorial ?? 'No registrada' }}
                                    </div>
                                    <div class="mb-3">
                                        <strong><i class="fas fa-calendar text-primary"></i> Año:</strong>
                                        <br>{{ $libro->anio_publicacion ?? 'No registrado' }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong><i class="fas fa-language text-primary"></i> Idioma:</strong>
                                        <br>{{ $libro->idioma }}
                                    </div>
                                    <div class="mb-3">
                                        <strong><i class="fas fa-file-alt text-primary"></i> Páginas:</strong>
                                        <br>{{ $libro->numero_paginas ?? 'No registrado' }}
                                    </div>
                                    <div class="mb-3">
                                        <strong><i class="fas fa-map-marker-alt text-primary"></i> Ubicación:</strong>
                                        <br>{{ $libro->ubicacion ?? 'No registrada' }}
                                    </div>
                                </div>
                            </div>
                            
                            @if($libro->descripcion)
                                <div class="mt-4">
                                    <h6><i class="fas fa-align-left text-primary"></i> Descripción:</h6>
                                    <p class="text-muted">{{ $libro->descripcion }}</p>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Estado del stock -->
                        <div class="col-md-3">
                            <h5 class="text-center mb-4"><i class="fas fa-chart-pie text-primary"></i> Estado del Stock</h5>
                            
                            <div class="stat-card bg-info text-white p-3 mb-3 text-center">
                                <i class="fas fa-boxes fa-2x mb-2"></i>
                                <h3 class="mb-0">{{ $libro->stock_total }}</h3>
                                <small>Stock Total</small>
                            </div>
                            
                            <div class="stat-card bg-success text-white p-3 mb-3 text-center">
                                <i class="fas fa-check-circle fa-2x mb-2"></i>
                                <h3 class="mb-0">{{ $libro->stock_disponible }}</h3>
                                <small>Disponibles</small>
                            </div>
                            
                            <div class="stat-card bg-warning text-white p-3 mb-3 text-center">
                                <i class="fas fa-handshake fa-2x mb-2"></i>
                                <h3 class="mb-0">{{ $libro->libros_prestados }}</h3>
                                <small>Prestados</small>
                            </div>
                            
                            @if($libro->stock_disponible > 0)
                                <div class="d-grid">
                                    <a href="{{ route('prestamos.create') }}?libro={{ $libro->id_libro }}" class="btn btn-success">
                                        <i class="fas fa-plus"></i> Prestar Libro
                                    </a>
                                </div>
                            @else
                                <div class="alert alert-warning text-center">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <br><small>Sin stock disponible</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>