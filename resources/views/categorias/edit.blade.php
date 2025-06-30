<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Categoría - Biblioteca Virtual</title>
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
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-warning text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-edit"></i> Editar Categoría
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

                        <form action="{{ route('categorias.update', $categoria->id_categoria) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-3">
                                <label for="nombre_categoria" class="form-label">
                                    <i class="fas fa-tag"></i> Nombre de la Categoría *
                                </label>
                                <input type="text" class="form-control" id="nombre_categoria" name="nombre_categoria" 
                                       value="{{ old('nombre_categoria', $categoria->nombre_categoria) }}" required maxlength="100">
                            </div>

                            <div class="mb-3">
                                <label for="descripcion" class="form-label">
                                    <i class="fas fa-align-left"></i> Descripción
                                </label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="4" 
                                          maxlength="500">{{ old('descripcion', $categoria->descripcion) }}</textarea>
                                <div class="form-text">Máximo 500 caracteres</div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('categorias.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save"></i> Actualizar Categoría
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