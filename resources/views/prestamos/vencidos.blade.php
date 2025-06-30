<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Préstamos Vencidos - Biblioteca Virtual</title>
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
                    <h2 class="text-danger">
                        <i class="fas fa-exclamation-triangle"></i> Préstamos Vencidos
                    </h2>
                    <a href="{{ route('prestamos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver a Préstamos
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-clock"></i> Lista de Préstamos Vencidos
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(count($prestamos) > 0)
                            <div class="alert alert-warning">
                                <i class="fas fa-info-circle"></i>
                                <strong>Atención:</strong> Los siguientes préstamos han superado su fecha límite. 
                                Se aplicará una multa de <strong>S/. 2.00 por día</strong> de retraso.
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Usuario</th>
                                            <th>Contacto</th>
                                            <th>Libro</th>
                                            <th>Fecha Préstamo</th>
                                            <th>Fecha Límite</th>
                                            <th>Días Vencido</th>
                                            <th>Multa</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($prestamos as $prestamo)
                                            <tr class="table-danger">
                                                <td>{{ $prestamo->id_prestamo}}</td>
                                                <td>
                                                    <strong>{{ $prestamo->usuario}}</strong>
                                                </td>
                                                <td>
                                                    @if($prestamo->email)
                                                        <small class="d-block">
                                                            <i class="fas fa-envelope"></i> {{ $prestamo->email }}
                                                        </small>
                                                    @endif
                                                    @if($prestamo->telefono)
                                                        <small class="d-block">
                                                            <i class="fas fa-phone"></i> {{ $prestamo->telefono }}
                                                        </small>
                                                    @endif
                                                </td>
                                                <td>{{ $prestamo->libro }}</td>
                                                <td>{{ date('d/m/Y', strtotime($prestamo->fecha_prestamo)) }}</td>
                                                <td>
                                                    <span class="text-danger fw-bold">
                                                        {{ date('d/m/Y', strtotime($prestamo->fecha_limite)) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-danger fs-6">
                                                        {{ $prestamo->dias_vencido }} día{{ $prestamo->dias_vencido != 1 ? 's' : '' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="text-danger fw-bold">
                                                        S/. {{ number_format($prestamo->multa_calculada, 2) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <button type="button" class="btn btn-warning" 
                                                                onclick="contactarUsuario('{{ $prestamo->email }}', '{{ $prestamo->usuario }}', '{{ $prestamo->libro }}')"
                                                                title="Contactar Usuario">
                                                            <i class="fas fa-envelope"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-primary" 
                                                                onclick="devolverLibro({{ $prestamo->id_prestamo }}, '{{ $prestamo->libro }}', {{ $prestamo->multa_calculada }})"
                                                                title="Devolver">
                                                            <i class="fas fa-undo"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="card border-danger">
                                            <div class="card-body text-center">
                                                <i class="fas fa-exclamation-triangle fa-2x text-danger mb-2"></i>
                                                <h5 class="text-danger">{{ count($prestamos) }}</h5>
                                                <small class="text-muted">Préstamos Vencidos</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
    <div class="card border-warning">
        <div class="card-body text-center">
            <i class="fas fa-calendar-times fa-2x text-warning mb-2"></i>
            <h5 class="text-warning">{{ collect($prestamos)->sum('dias_vencido') }}</h5>
            <small class="text-muted">Total Días Vencidos</small>
        </div>
    </div>
</div>
<div class="col-md-3">
    <div class="card border-info">
        <div class="card-body text-center">
            <i class="fas fa-money-bill-wave fa-2x text-info mb-2"></i>
            <h5 class="text-info">S/. {{ number_format(collect($prestamos)->sum('multa_calculada'), 2) }}</h5>
            <small class="text-muted">Total Multas</small>
        </div>
    </div>
</div>
<div class="col-md-3">
    <div class="card border-secondary">
        <div class="card-body text-center">
            <i class="fas fa-users fa-2x text-secondary mb-2"></i>
            <h5 class="text-secondary">{{ collect($prestamos)->unique('usuario')->count() }}</h5>
            <small class="text-muted">Usuarios Afectados</small>
        </div>
    </div>
</div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                <h4 class="text-success">¡Excelente!</h4>
                                <p class="text-muted">No hay préstamos vencidos en este momento</p>
                                <a href="{{ route('prestamos.index') }}" class="btn btn-primary">
                                    <i class="fas fa-handshake"></i> Ver Préstamos Activos
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para devolver libro vencido -->
    <div class="modal fade" id="devolverModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Devolver Libro Vencido</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="devolverForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Préstamo Vencido:</strong> Se aplicará automáticamente la multa correspondiente.
                        </div>
                        
                        <p><strong>Libro:</strong> <span id="libroNombre"></span></p>
                        <p><strong>Multa a aplicar:</strong> <span id="multaCalculada" class="text-danger fw-bold"></span></p>
                        
                        <div class="mb-3">
                            <label for="observaciones_devolucion" class="form-label">Observaciones de Devolución</label>
                            <textarea class="form-control" name="observaciones" id="observaciones_devolucion" rows="3" 
                                      placeholder="Estado del libro, observaciones sobre la devolución..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Confirmar Devolución con Multa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function devolverLibro(idPrestamo, nombreLibro, multa) {
            document.getElementById('libroNombre').textContent = nombreLibro;
            document.getElementById('multaCalculada').textContent = 'S/. ' + parseFloat(multa).toFixed(2);
            document.getElementById('devolverForm').action = `/prestamos/${idPrestamo}/devolver`;
            new bootstrap.Modal(document.getElementById('devolverModal')).show();
        }

        function contactarUsuario(email, usuario, libro) {
            const asunto = `Recordatorio: Devolución de libro pendiente`;
            const mensaje = `Estimado/a ${usuario},\n\nLe recordamos que tiene pendiente la devolución del libro "${libro}". Por favor, acérquese a la biblioteca a la brevedad posible para evitar multas adicionales.\n\nGracias por su comprensión.\n\nBiblioteca Virtual`;
            
            const mailtoLink = `mailto:${email}?subject=${encodeURIComponent(asunto)}&body=${encodeURIComponent(mensaje)}`;
            window.open(mailtoLink);
        }
    </script>
</body>
</html>