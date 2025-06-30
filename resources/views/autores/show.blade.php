<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $autor->apellido }}, {{ $autor->nombre }} - Biblioteca Virtual</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .author-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .info-card {
            background: rgba(255,255,255,0.95);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }
        .author-photo {
            width: 200px;
            height: 250px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }
        .author-placeholder {
            width: 200px;
            height: 250px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark author-header">
        <div class="container">
            <a class="navbar-brand" href="{{ route('libros.index') }}">
                <i class="fas fa-book"></i> Biblioteca Virtual
            </a>
            <div class="navbar-nav">
                <a class="nav-link" href="{{ route('libros.index') }}">
                    <i class="fas fa-book"></i> Libros
                </a>
                <a class="nav-link active" href="{{ route('autores.index') }}">
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
                        <!-- Foto del autor -->
                        <div class="col-md-3 text-center mb-4">
                            @php
                                // Crear nombre de imagen basado en apellido y nombre
                                $imageName = strtolower(str_replace([' ', 'ñ', 'á', 'é', 'í', 'ó', 'ú'], ['-', 'n', 'a', 'e', 'i', 'o', 'u'], $autor->apellido)) . '.jpg';
                                $imagePath = 'images/autores/' . $imageName;
                            @endphp
                            
                            @if(file_exists(public_path($imagePath)))
                                <img src="{{ asset($imagePath) }}" alt="{{ $autor->apellido }}, {{ $autor->nombre }}" class="author-photo mb-3">
                            @else
                                <div class="author-placeholder d-flex align-items-center justify-content-center bg-gradient text-white mb-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                    <div class="text-center">
                                        <i class="fas fa-user fa-4x mb-2"></i>
                                        <br><small>Sin Foto</small>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="d-grid gap-2">
                                <a href="{{ route('autores.edit', $autor->id_autor) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="{{ route('autores.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                            </div>
                        </div>
                        
                        <!-- Información del autor -->
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h1 class="text-primary mb-2">{{ $autor->apellido }}, {{ $autor->nombre }}</h1>
                                @if($autor->nacionalidad)
                                    <h5 class="text-muted mb-3">
                                        <i class="fas fa-flag"></i> {{ $autor->nacionalidad }}
                                    </h5>
                                @endif
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    @if($autor->fecha_nacimiento)
                                        <div class="mb-3">
                                            <strong><i class="fas fa-calendar text-primary"></i> Fecha de Nacimiento:</strong>
                                            <br>{{ date('d/m/Y', strtotime($autor->fecha_nacimiento)) }}
                                        </div>
                                    @endif
                                    
                                    <div class="mb-3">
                                        <strong><i class="fas fa-book text-primary"></i> Libros en Biblioteca:</strong>
                                        <br><span class="badge bg-success fs-6">{{ count($libros) }} libro(s)</span>
                                    </div>
                                </div>
                            </div>
                            
                            @if($autor->biografia)
                                <div class="mt-4">
                                    <h6><i class="fas fa-align-left text-primary"></i> Biografía:</h6>
                                    <p class="text-muted">{{ $autor->biografia }}</p>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Estadísticas -->
                        <div class="col-md-3">
                            <h5 class="text-center mb-4"><i class="fas fa-chart-bar text-primary"></i> Estadísticas</h5>
                            
                            <div class="card bg-primary text-white mb-3 text-center">
                                <div class="card-body">
                                    <i class="fas fa-books fa-2x mb-2"></i>
                                    <h3 class="mb-0">{{ count($libros) }}</h3>
                                    <small>Libros</small>
                                </div>
                            </div>
                            
                            @if(count($libros) > 0)
                                @php
                                    $totalStock = collect($libros)->sum('stock_total');
                                    $stockDisponible = collect($libros)->sum('stock_disponible');
                                @endphp
                                
                                <div class="card bg-success text-white mb-3 text-center">
                                    <div class="card-body">
                                        <i class="fas fa-check-circle fa-2x mb-2"></i>
                                        <h3 class="mb-0">{{ $stockDisponible }}</h3>
                                        <small>Disponibles</small>
                                    </div>
                                </div>
                                
                                <div class="card bg-info text-white mb-3 text-center">
                                    <div class="card-body">
                                        <i class="fas fa-layer-group fa-2x mb-2"></i>
                                        <h3 class="mb-0">{{ $totalStock }}</h3>
                                        <small>Stock Total</small>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                @if(count($libros) > 0)
                    <div class="info-card p-4 mt-4">
                        <h5><i class="fas fa-books text-primary"></i> Libros de {{ $autor->nombre }} {{ $autor->apellido }}</h5>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Título</th>
                                        <th>Editorial</th>
                                        <th>Año</th>
                                        <th>Stock</th>
                                        <th>Disponible</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($libros as $libro)
                                        <tr>
                                            <td><strong>{{ $libro->titulo }}</strong></td>
                                            <td>{{ $libro->editorial ?? 'N/A' }}</td>
                                            <td>{{ $libro->anio_publicacion ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ $libro->stock_total }}</span>
                                            </td>
                                            <td>
                                                @if($libro->stock_disponible > 0)
                                                    <span class="badge bg-success">{{ $libro->stock_disponible }}</span>
                                                @else
                                                    <span class="badge bg-danger">0</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('libros.show', $libro->id_libro) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> Ver
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>