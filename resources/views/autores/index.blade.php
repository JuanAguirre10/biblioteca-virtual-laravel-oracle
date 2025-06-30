<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Autores - Biblioteca Virtual</title>
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-user-edit"></i> Gestión de Autores</h2>
                    <a href="{{ route('autores.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Nuevo Autor
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
                        @if(count($autores) > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre Completo</th>
                                            <th>Nacionalidad</th>
                                            <th>Fecha Nacimiento</th>
                                            <th>Libros</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($autores as $autor)
                                            <tr>
                                                <td>{{ $autor->id_autor }}</td>
                                                <td>
                                                    <strong>{{ $autor->apellido }}, {{ $autor->nombre }}</strong>
                                                </td>
                                                <td>{{ $autor->nacionalidad ?: 'No registrada' }}</td>
                                                <td>
                                                    @if($autor->fecha_nacimiento)
                                                        {{ date('d/m/Y', strtotime($autor->fecha_nacimiento)) }}
                                                    @else
                                                        No registrada
                                                    @endif
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-info" 
        onclick="verLibrosAutor({{ $autor->id_autor }}, '{{ addslashes($autor->apellido) }}, {{ addslashes($autor->nombre) }}')">
    <i class="fas fa-book"></i> Ver libros
</button>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="{{ route('autores.show', $autor->id_autor) }}" 
                                                           class="btn btn-outline-info" title="Ver Detalles">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('autores.edit', $autor->id_autor) }}" 
                                                           class="btn btn-outline-primary" title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-outline-danger" 
                                                                title="Eliminar" 
                                                                onclick="confirmarEliminacion({{ $autor->id_autor }})">
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
                                <i class="fas fa-user-edit fa-3x text-muted mb-3"></i>
                                <h4 class="text-muted">No hay autores registrados</h4>
                                <p class="text-muted">Comienza agregando tu primer autor</p>
                                <a href="{{ route('autores.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Agregar Primer Autor
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
                                    <i class="fas fa-user-edit fa-2x text-primary mb-2"></i>
                                    <h5>{{ count($autores) }}</h5>
                                    <small class="text-muted">Total de Autores</small>
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
            if (confirm('¿Estás seguro de que deseas eliminar este autor?\n\nEsta acción no se puede deshacer.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/autores/${id}`;
                
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
    <div class="modal fade" id="librosModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-books"></i> Libros de <span id="autorNombre"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="librosContent">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script>
function verLibrosAutor(idAutor, nombreAutor) {
    document.getElementById('autorNombre').textContent = nombreAutor;
    
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('librosModal'));
    modal.show();
    
    // Mostrar spinner
    document.getElementById('librosContent').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2">Cargando libros...</p>
        </div>
    `;
    
    // Cargar libros reales
    fetch(`/api/autores/${idAutor}/libros`)
        .then(response => response.json())
        .then(data => {
            let html = '';
            
            if (data.success && data.libros && data.libros.length > 0) {
                html = `
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Título</th>
                                    <th>Editorial</th>
                                    <th>Año</th>
                                    <th>Stock Total</th>
                                    <th>Disponible</th>
                                    <th>Ubicación</th>
                                </tr>
                            </thead>
                            <tbody>
                `;
                
                data.libros.forEach(libro => {
                    html += `
                        <tr>
                            <td>
                                <strong>${libro.titulo}</strong>
                                ${libro.isbn ? `<br><small class="text-muted">ISBN: ${libro.isbn}</small>` : ''}
                            </td>
                            <td>${libro.editorial || 'N/A'}</td>
                            <td>${libro.anio_publicacion || 'N/A'}</td>
                            <td><span class="badge bg-info">${libro.stock_total}</span></td>
                            <td>
                                ${libro.stock_disponible > 0 
                                    ? `<span class="badge bg-success">${libro.stock_disponible}</span>`
                                    : `<span class="badge bg-danger">0</span>`
                                }
                            </td>
                            <td>${libro.ubicacion || 'N/A'}</td>
                        </tr>
                    `;
                });
                
                html += '</tbody></table></div>';
                
                // Agregar estadística
                const totalLibros = data.libros.length;
                const totalStock = data.libros.reduce((sum, libro) => sum + libro.stock_total, 0);
                const totalDisponible = data.libros.reduce((sum, libro) => sum + libro.stock_disponible, 0);
                
                html += `
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <div class="text-center">
                                <h5 class="text-primary">${totalLibros}</h5>
                                <small class="text-muted">Títulos</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <h5 class="text-info">${totalStock}</h5>
                                <small class="text-muted">Stock Total</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <h5 class="text-success">${totalDisponible}</h5>
                                <small class="text-muted">Disponibles</small>
                            </div>
                        </div>
                    </div>
                `;
                
            } else {
                html = `
                    <div class="text-center py-5">
                        <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No hay libros de este autor</h5>
                        <p class="text-muted">Este autor aún no tiene libros registrados en la biblioteca</p>
                    </div>
                `;
            }
            
            document.getElementById('librosContent').innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('librosContent').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Error al cargar los libros.</strong><br>
                    Intenta nuevamente o contacta al administrador.
                </div>
            `;
        });
}
</script>
</body>

</html>