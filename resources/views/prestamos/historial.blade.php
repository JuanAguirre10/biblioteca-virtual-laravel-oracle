<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Préstamos - Biblioteca Virtual</title>
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
                <a class="nav-link active" href="{{ route('prestamos.index') }}">
                    <i class="fas fa-handshake"></i> Préstamos
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-history"></i> Historial de Préstamos</h2>
                    <div>
                        <a href="{{ route('prestamos.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                        <button type="button" class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#filtros">
                            <i class="fas fa-filter"></i> Filtros
                        </button>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="collapse mb-4" id="filtros">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-search"></i> Filtrar Préstamos</h5>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('prestamos.historial') }}">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" 
                                               value="{{ $fechaInicio }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="fecha_fin" class="form-label">Fecha Fin</label>
                                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" 
                                               value="{{ $fechaFin }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="usuario" class="form-label">Usuario</label>
                                        <input type="text" class="form-control" id="usuario" name="usuario" 
                                               value="{{ $usuario }}" placeholder="Nombre del usuario...">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="estado" class="form-label">Estado</label>
                                        <select class="form-select" id="estado" name="estado">
                                            <option value="TODOS" {{ $estado == 'TODOS' ? 'selected' : '' }}>Todos</option>
                                            <option value="ACTIVO" {{ $estado == 'ACTIVO' ? 'selected' : '' }}>Activo</option>
                                            <option value="DEVUELTO" {{ $estado == 'DEVUELTO' ? 'selected' : '' }}>Devuelto</option>
                                            <option value="VENCIDO" {{ $estado == 'VENCIDO' ? 'selected' : '' }}>Vencido</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> Buscar
                                        </button>
                                        <a href="{{ route('prestamos.historial') }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> Limpiar
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-list"></i> Registro Completo de Préstamos
                            <span class="badge bg-light text-dark ms-2">{{ count($prestamos) }} registros</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(count($prestamos) > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Usuario</th>
                                            <th>Libro</th>
                                            <th>Autor</th>
                                            <th>Categoría</th>
                                            <th>Fecha Préstamo</th>
                                            <th>Fecha Límite</th>
                                            <th>Fecha Devolución</th>
                                            <th>Estado</th>
                                            <th>Multa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($prestamos as $prestamo)
                                            <tr>
                                                <td>{{ $prestamo->id_prestamo }}</td>
                                                <td>
                                                    <strong>{{ $prestamo->usuario }}</strong>
                                                    <br><small class="text-muted">{{ $prestamo->email }}</small>
                                                </td>
                                                <td>{{ $prestamo->libro }}</td>
                                                <td>{{ $prestamo->autor }}</td>
                                                <td>
                                                    <span class="badge bg-secondary">{{ $prestamo->categoria }}</span>
                                                </td>
                                                <td>{{ date('d/m/Y', strtotime($prestamo->fecha_prestamo)) }}</td>
                                                <td>{{ date('d/m/Y', strtotime($prestamo->fecha_limite)) }}</td>
                                                <td>
                                                    @if($prestamo->fecha_devolucion)
                                                        {{ date('d/m/Y', strtotime($prestamo->fecha_devolucion)) }}
                                                    @else
                                                        <span class="text-muted">Pendiente</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($prestamo->estado == 'ACTIVO')
                                                        <span class="badge bg-success">Activo</span>
                                                    @elseif($prestamo->estado == 'DEVUELTO')
                                                        <span class="badge bg-primary">Devuelto</span>
                                                    @elseif($prestamo->estado == 'VENCIDO')
                                                        <span class="badge bg-danger">Vencido</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($prestamo->multa > 0)
                                                        <span class="text-danger fw-bold">S/. {{ number_format($prestamo->multa, 2) }}</span>
                                                    @else
                                                        <span class="text-muted">S/. 0.00</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Estadísticas del filtro -->
                            <div class="mt-4">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="card border-primary">
                                            <div class="card-body text-center">
                                                <i class="fas fa-list fa-2x text-primary mb-2"></i>
                                                <h5>{{ count($prestamos) }}</h5>
                                                <small class="text-muted">Total Registros</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card border-success">
                                            <div class="card-body text-center">
                                                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                                <h5>{{ collect($prestamos)->where('estado', 'DEVUELTO')->count() }}</h5>
                                                <small class="text-muted">Devueltos</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card border-warning">
                                            <div class="card-body text-center">
                                                <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                                                <h5>{{ collect($prestamos)->where('estado', 'ACTIVO')->count() }}</h5>
                                                <small class="text-muted">Activos</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card border-info">
                                            <div class="card-body text-center">
                                                <i class="fas fa-money-bill-wave fa-2x text-info mb-2"></i>
                                                <h5>S/. {{ number_format(collect($prestamos)->sum('multa'), 2) }}</h5>
                                                <small class="text-muted">Total Multas</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                <h4 class="text-muted">No se encontraron préstamos</h4>
                                <p class="text-muted">Intenta ajustar los filtros de búsqueda</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>