<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Autor - Biblioteca Virtual</title>
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
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-warning text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-edit"></i> Editar Autor
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

                        <form action="{{ route('autores.update', $autor->id_autor) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nombre" class="form-label">
                                            <i class="fas fa-user"></i> Nombre *
                                        </label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" 
                                               value="{{ old('nombre', $autor->nombre) }}" required maxlength="100">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="apellido" class="form-label">
                                            <i class="fas fa-user"></i> Apellido *
                                        </label>
                                        <input type="text" class="form-control" id="apellido" name="apellido" 
                                               value="{{ old('apellido', $autor->apellido) }}" required maxlength="100">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nacionalidad" class="form-label">
                                            <i class="fas fa-flag"></i> Nacionalidad
                                        </label>
                                        <input type="text" class="form-control" id="nacionalidad" name="nacionalidad" 
                                               value="{{ old('nacionalidad', $autor->nacionalidad) }}" maxlength="50">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="fecha_nacimiento" class="form-label">
                                            <i class="fas fa-calendar"></i> Fecha de Nacimiento
                                        </label>
                                        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" 
                                               value="{{ old('fecha_nacimiento', $autor->fecha_nacimiento ? date('Y-m-d', strtotime($autor->fecha_nacimiento)) : '') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="biografia" class="form-label">
                                    <i class="fas fa-align-left"></i> Biografía
                                </label>
                                <textarea class="form-control" id="biografia" name="biografia" rows="4">{{ old('biografia', $autor->biografia) }}</textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('autores.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save"></i> Actualizar Autor
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>