<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Préstamo - Biblioteca Virtual</title>
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
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-plus-circle"></i> Registrar Nuevo Préstamo
                        </h4>
                    </div>
                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(count($libros) == 0)
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                No hay libros disponibles para préstamo en este momento.
                                <a href="{{ route('libros.index') }}" class="alert-link">Ver catálogo de libros</a>
                            </div>
                        @elseif(count($usuarios) == 0)
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                No hay usuarios registrados en el sistema.
                            </div>
                        @else
                            <form action="{{ route('prestamos.store') }}" method="POST">
                                @csrf
                                
                                <div class="mb-4">
                                    <label for="id_usuario" class="form-label">
                                        <i class="fas fa-user"></i> Usuario *
                                    </label>
                                    <select class="form-select" id="id_usuario" name="id_usuario" required>
                                        <option value="">Seleccionar usuario...</option>
                                        @foreach($usuarios as $usuario)
                                            <option value="{{ $usuario->id_usuario }}" 
                                                    {{ old('id_usuario') == $usuario->id_usuario ? 'selected' : '' }}>
                                                {{ $usuario->apellido }}, {{ $usuario->nombre }} - {{ $usuario->email }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">
                                        Selecciona el usuario que realizará el préstamo
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="id_libro" class="form-label">
                                        <i class="fas fa-book"></i> Libro *
                                    </label>
                                    <select class="form-select" id="id_libro" name="id_libro" required>
                                        <option value="">Seleccionar libro...</option>
                                        @foreach($libros as $libro)
                                            <option value="{{ $libro->id_libro }}" 
                                                    data-stock="{{ $libro->stock_disponible }}"
                                                    {{ old('id_libro') == $libro->id_libro ? 'selected' : '' }}>
                                                {{ $libro->titulo }} - {{ $libro->autor}} 
                                                ({{ $libro->stock_disponible }} disponible{{ $libro->stock_disponible != 1 ? 's' : '' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">
                                        Solo se muestran libros con stock disponible
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="dias_prestamo" class="form-label">
                                                <i class="fas fa-calendar-days"></i> Días de Préstamo
                                            </label>
                                            <select class="form-select" id="dias_prestamo" name="dias_prestamo">
                                                <option value="7" {{ old('dias_prestamo') == '7' ? 'selected' : '' }}>7 días</option>
                                                <option value="15" {{ old('dias_prestamo', '15') == '15' ? 'selected' : '' }}>15 días (por defecto)</option>
                                                <option value="21" {{ old('dias_prestamo') == '21' ? 'selected' : '' }}>21 días</option>
                                                <option value="30" {{ old('dias_prestamo') == '30' ? 'selected' : '' }}>30 días</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label class="form-label">
                                                <i class="fas fa-calendar-check"></i> Fecha de Devolución
                                            </label>
                                            <div class="form-control-plaintext bg-light border rounded p-2">
                                                <span id="fecha_devolucion">
                                                    <i class="fas fa-calendar"></i> Selecciona los días de préstamo
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="observaciones" class="form-label">
                                        <i class="fas fa-sticky-note"></i> Observaciones
                                    </label>
                                    <textarea class="form-control" id="observaciones" name="observaciones" rows="3" 
                                              placeholder="Observaciones adicionales sobre el préstamo...">{{ old('observaciones') }}</textarea>
                                </div>

                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Información importante:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Cada usuario puede tener máximo 3 préstamos activos</li>
                                        <li>Los préstamos vencidos generan multa de S/. 2.00 por día</li>
                                        <li>El stock del libro se actualizará automáticamente</li>
                                    </ul>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('prestamos.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-handshake"></i> Registrar Préstamo
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>

                @if(count($libros) > 0)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-bar"></i> Resumen de Disponibilidad
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-book fa-2x text-success me-3"></i>
                                        <div>
                                            <h6 class="mb-0">{{ count($libros) }}</h6>
                                            <small class="text-muted">Títulos Disponibles</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-boxes fa-2x text-primary me-3"></i>
                                        <div>
                                            <h6 class="mb-0">{{ collect($libros)->sum('STOCK_DISPONIBLE') }}</h6>
                                            <small class="text-muted">Ejemplares Disponibles</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const diasSelect = document.getElementById('dias_prestamo');
            const fechaDevolucion = document.getElementById('fecha_devolucion');

            function calcularFechaDevolucion() {
                const dias = parseInt(diasSelect.value);
                if (dias) {
                    const fecha = new Date();
                    fecha.setDate(fecha.getDate() + dias);
                    
                    const opciones = { 
                        weekday: 'long', 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric' 
                    };
                    
                    fechaDevolucion.innerHTML = `
                        <i class="fas fa-calendar-check text-success"></i> 
                        ${fecha.toLocaleDateString('es-ES', opciones)}
                    `;
                }
            }

            diasSelect.addEventListener('change', calcularFechaDevolucion);
            calcularFechaDevolucion(); // Calcular fecha inicial
        });
    </script>
</body>
</html>