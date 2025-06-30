<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Préstamos - Biblioteca Virtual</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
                <a class="nav-link active" href="{{ route('prestamos.index') }}">
                    <i class="fas fa-handshake"></i> Préstamos
                </a>
                <a class="nav-link" href="{{ route('prestamos.historial') }}">
        <i class="fas fa-history"></i> Historial
    </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-handshake"></i> Gestión de Préstamos</h2>
                    <div>
                        <a href="{{ route('prestamos.create') }}" class="btn btn-success me-2">
                            <i class="fas fa-plus"></i> Nuevo Préstamo
                        </a>
                        <a href="{{ route('prestamos.vencidos') }}" class="btn btn-warning">
                            <i class="fas fa-exclamation-triangle"></i> Vencidos
                        </a>
                    </div>
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
                        @if(count($prestamos) > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Usuario</th>
                                            <th>Libro</th>
                                            <th>Fecha Préstamo</th>
                                            <th>Fecha Límite</th>
                                            <th>Estado</th>
                                            <th>Observaciones</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($prestamos as $prestamo)
                                            <tr>
                                                <td>{{ $prestamo->id_prestamo }}</td>
                                                <td>
                                                    <strong>{{ $prestamo->usuario }}</strong>
                                                    @if($prestamo->email)
                                                        <br><small class="text-muted">{{ $prestamo->email }}</small>
                                                    @endif
                                                </td>
                                                <td>{{ $prestamo->libro }}</td>
                                                <td>{{ date('d/m/Y', strtotime($prestamo->fecha_prestamo)) }}</td>
                                                <td>{{ date('d/m/Y', strtotime($prestamo->fecha_limite)) }}</td>
                                                <td>
                                                    @if($prestamo->estado_real == 'VENCIDO')
                                                        <span class="badge bg-danger">Vencido</span>
                                                    @elseif($prestamo->estado_real == 'POR_VENCER')
                                                        <span class="badge bg-warning">Por Vencer</span>
                                                    @else
                                                        <span class="badge bg-success">Activo</span>
                                                    @endif
                                                </td>
                                                <td>{{ $prestamo->observaciones }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-primary" 
                                                            onclick="devolverLibro({{ $prestamo->id_prestamo }}, '{{ $prestamo->libro }}')">
                                                        <i class="fas fa-undo"></i> Devolver
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-handshake fa-3x text-muted mb-3"></i>
                                <h4 class="text-muted">No hay préstamos activos</h4>
                                <p class="text-muted">Comienza registrando un nuevo préstamo</p>
                                <a href="{{ route('prestamos.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Registrar Primer Préstamo
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mt-4">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <i class="fas fa-handshake fa-2x text-primary mb-2"></i>
                                    <h5>{{ count($prestamos) }}</h5>
                                    <small class="text-muted">Préstamos Activos</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-warning">
                                <div class="card-body text-center">
                                    <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                                    <h5>{{ collect($prestamos)->where('ESTADO_DETALLE', 'POR_VENCER')->count() }}</h5>
                                    <small class="text-muted">Por Vencer</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-danger">
                                <div class="card-body text-center">
                                    <i class="fas fa-exclamation-triangle fa-2x text-danger mb-2"></i>
                                    <h5>{{ collect($prestamos)->where('ESTADO_DETALLE', 'VENCIDO')->count() }}</h5>
                                    <small class="text-muted">Vencidos</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para devolver libro -->
    <div class="modal fade" id="devolverModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Devolver Libro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="devolverForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <p>¿Estás seguro de que deseas marcar como devuelto el libro: <strong id="libroNombre"></strong>?</p>
                        <div class="mb-3">
                            <label for="observaciones_devolucion" class="form-label">Observaciones</label>
                            <textarea class="form-control" name="observaciones" id="observaciones_devolucion" rows="3" 
                                      placeholder="Observaciones sobre la devolución..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Confirmar Devolución</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function devolverLibro(idPrestamo, nombreLibro) {
            document.getElementById('libroNombre').textContent = nombreLibro;
            document.getElementById('devolverForm').action = `/prestamos/${idPrestamo}/devolver`;
            new bootstrap.Modal(document.getElementById('devolverModal')).show();
        }
    </script>
</body>
</html>