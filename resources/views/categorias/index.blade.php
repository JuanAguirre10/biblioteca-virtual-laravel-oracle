<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Categorías - Biblioteca Virtual</title>
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-tags"></i> Gestión de Categorías</h2>
                    <a href="{{ route('categorias.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Nueva Categoría
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
                        @if(count($categorias) > 0)
                            <div class="row">
                                @foreach($categorias as $categoria)
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card border-primary h-100">
                                            <div class="card-header bg-primary text-white">
                                                <h5 class="mb-0">
                                                    <i class="fas fa-tag"></i> {{ $categoria->nombre_categoria }}
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text">
                                                    {{ $categoria->DESCRIPCION ?? 'Sin descripción' }}
                                                </p>
                                                <div class="text-center mb-3">
                                                    <span class="badge bg-info fs-6">ID: {{ $categoria->id_categoria }}</span>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <div class="btn-group w-100" role="group">
                                                    <a href="{{ route('categorias.show', $categoria->id_categoria) }}" 
                                                       class="btn btn-outline-info btn-sm">
                                                        <i class="fas fa-eye"></i> Ver
                                                    </a>
                                                    <a href="{{ route('categorias.edit', $categoria->id_categoria) }}" 
                                                       class="btn btn-outline-primary btn-sm">
                                                        <i class="fas fa-edit"></i> Editar
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger btn-sm" 
                                                            onclick="confirmarEliminacion({{ $categoria->id_categoria }})">
                                                        <i class="fas fa-trash"></i> Eliminar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                                <h4 class="text-muted">No hay categorías registradas</h4>
                                <p class="text-muted">Comienza agregando tu primera categoría</p>
                                <a href="{{ route('categorias.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Agregar Primera Categoría
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mt-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <i class="fas fa-tags fa-2x text-primary mb-2"></i>
                                    <h5>{{ count($categorias) }}</h5>
                                    <small class="text-muted">Total de Categorías</small>
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
            if (confirm('¿Estás seguro de que deseas eliminar esta categoría?\n\nEsta acción no se puede deshacer.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/categorias/${id}`;
                
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);
                
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