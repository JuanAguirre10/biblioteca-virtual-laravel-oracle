<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $categoria->nombre_categoria }} - Biblioteca Virtual</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .category-header {
            background: linear-gradient(135deg, #ff7e5f 0%, #feb47b 100%);
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
    <nav class="navbar navbar-expand-lg navbar-dark category-header">
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
                <a class="nav-link active" href="{{ route('categorias.index') }}">
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
                        <!-- Información de la categoría -->
                        <div class="col-md-8">
                            <div class="mb-4">
                                <h1 class="text-primary mb-2">
                                    <i class="fas fa-tag"></i> {{ $categoria->nombre_categoria }}
                                </h1>
                                @if($categoria->descripcion)
                                    <p class="text-muted fs-5">{{ $categoria->descripcion }}</p>
                                @endif
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong><i class="fas fa-calendar text-primary"></i> Fecha de Creación:</strong>
                                        <br>{{ date('d/m/Y', strtotime($categoria->fecha_creacion)) }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong><i class="fas fa-book text-primary"></i> Libros en esta Categoría:</strong>
                                        <br><span class="badge bg-success fs-6">{{ count($libros) }} libro(s)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Estadísticas -->
                        <div class="col-md-4">
                            <h5 class="text-center mb-4"><i class="fas fa-chart-pie text-primary"></i> Estadísticas</h5>
                            
                            <div class="stat-card bg-primary text-white p-3 mb-3 text-center">
                                <i class="fas fa-books fa-2x mb-2"></i>
                                <h3 class="mb-0">{{ count($libros) }}</h3>
                                <small>Libros</small>
                            </div>
                            
                            @if(count($libros) > 0)
                                @php
                                    $totalStock = collect($libros)->sum('stock_total');
                                    $stockDisponible = collect($libros)->sum('stock_disponible');
                                @endphp
                                
                                <div class="stat-card bg-success text-white p-3 mb-3 text-center">
                                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                                    <h3 class="mb-0">{{ $stockDisponible }}</h3>
                                    <small>Disponibles</small>
                                </div>
                                
                                <div class="stat-card bg-info text-white p-3 mb-3 text-center">
                                    <i class="fas fa-layer-group fa-2x mb-2"></i>
                                    <h3 class="mb-0">{{ $totalStock }}</h3>
                                    <small>Stock Total</small>
                                </div>
                            @endif
                            
                            <div class="d-grid gap-2 mt-4">
                                <a href="{{ route('categorias.edit', $categoria->id_categoria) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Editar Categoría
                                </a>
                                <a href="{{ route('categorias.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                @if(count($libros) > 0)
                    <div class="info-card p-4 mt-4">
                        <h5><i class="fas fa-books text-primary"></i> Libros de {{ $categoria->nombre_categoria }}</h5>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Título</th>
                                        <th>Autor</th>
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
                                            <td>{{ $libro->autor_completo ?? 'N/A' }}</td>
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
                @else
                    <div class="info-card p-4 mt-4 text-center">
                        <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No hay libros en esta categoría</h4>
                        <p class="text-muted">Agrega libros a esta categoría desde la gestión de libros</p>
                        <a href="{{ route('libros.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Agregar Libro
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>