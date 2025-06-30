<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Biblioteca Virtual - Gestión de Libros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-books"></i> Gestión de Libros</h2>
                    <a href="{{ route('libros.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Nuevo Libro
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        @if(count($libros) > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Título</th>
                                            <th>Autor</th>
                                            <th>Categoría</th>
                                            <th>Editorial</th>
                                            <th>Año</th>
                                            <th>Stock</th>
                                            <th>Disponible</th>
                                            <th>Ubicación</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($libros as $libro)
    <tr>
        <td>{{ $libro->id_libro }}</td>
        <td>
            <strong>{{ $libro->titulo }}</strong>
            @if($libro->isbn)
                <br><small class="text-muted">ISBN: {{ $libro->isbn }}</small>
            @endif
        </td>
        <td>{{ $libro->autor_completo }}</td>
        <td>
            <span class="badge bg-secondary">{{ $libro->nombre_categoria }}</span>
        </td>
        <td>{{ $libro->editorial }}</td>
        <td>{{ $libro->anio_publicacion }}</td>
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
        <td>{{ $libro->ubicacion }}</td>
        <td>
    <div class="btn-group btn-group-sm" role="group">
        <a href="{{ route('libros.show', $libro->id_libro) }}" 
           class="btn btn-outline-info" title="Ver Detalles">
            <i class="fas fa-eye"></i>
        </a>
        <a href="{{ route('libros.edit', $libro->id_libro) }}" 
           class="btn btn-outline-primary" title="Editar">
            <i class="fas fa-edit"></i>
        </a>
        <button type="button" class="btn btn-outline-danger" 
                title="Eliminar" 
                onclick="confirmarEliminacion({{ $libro->id_libro }})">
            <i class="fas fa-trash"></i>
        </button>
    </div>
</td>
    </tr>
@endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                                <h4 class="text-muted">No hay libros registrados</h4>
                                <p class="text-muted">Comienza agregando tu primer libro al sistema</p>
                                <a href="{{ route('libros.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Agregar Primer Libro
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mt-4">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <i class="fas fa-book fa-2x text-primary mb-2"></i>
                                    <h5>{{ count($libros) }}</h5>
                                    <small class="text-muted">Total de Libros</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                    <h5>{{ collect($libros)->sum('stock_disponible') }}</h5>
                                    <small class="text-muted">Libros Disponibles</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-warning">
                                <div class="card-body text-center">
                                    <i class="fas fa-handshake fa-2x text-warning mb-2"></i>
                                    <h5>{{ collect($libros)->sum('stock_total') - collect($libros)->sum('stock_disponible') }}</h5>
                                    <small class="text-muted">Libros Prestados</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-info">
                                <div class="card-body text-center">
                                    <i class="fas fa-layer-group fa-2x text-info mb-2"></i>
                                    <h5>{{ collect($libros)->sum('stock_total') }}</h5>
                                    <small class="text-muted">Stock Total</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmarEliminacion(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este libro?\n\nEsta acción no se puede deshacer.')) {
        // Crear formulario dinámico para enviar DELETE
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/libros/${id}`;
        
        // Token CSRF
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);
        
        // Method DELETE
        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';
        form.appendChild(method);
        
        document.body.appendChild(form);
        form.submit();
    }
}
    </script>
</body>
</html>