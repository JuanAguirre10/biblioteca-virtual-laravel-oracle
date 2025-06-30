<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Libro - Biblioteca Virtual</title>
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
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-warning text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-edit"></i> Editar Libro
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

                        <form action="{{ route('libros.update', $libro->id_libro) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="titulo" class="form-label">
                                            <i class="fas fa-book"></i> Título *
                                        </label>
                                        <input type="text" class="form-control" id="titulo" name="titulo" 
                                               value="{{ old('titulo', $libro->titulo) }}" required maxlength="200">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="isbn" class="form-label">
                                            <i class="fas fa-barcode"></i> ISBN
                                        </label>
                                        <input type="text" class="form-control" id="isbn" name="isbn" 
                                               value="{{ old('isbn', $libro->isbn) }}" maxlength="20">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="id_autor" class="form-label">
                                            <i class="fas fa-user-edit"></i> Autor *
                                        </label>
                                        <select class="form-select" id="id_autor" name="id_autor" required>
                                            <option value="">Seleccionar autor...</option>
                                            @foreach($autores as $autor)
                                                <option value="{{ $autor->id_autor }}" 
                                                        {{ old('id_autor', $libro->id_autor) == $autor->id_autor ? 'selected' : '' }}>
                                                    {{ $autor->apellido }}, {{ $autor->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="id_categoria" class="form-label">
                                            <i class="fas fa-tags"></i> Categoría *
                                        </label>
                                        <select class="form-select" id="id_categoria" name="id_categoria" required>
                                            <option value="">Seleccionar categoría...</option>
                                            @foreach($categorias as $categoria)
                                                <option value="{{ $categoria->id_categoria }}" 
                                                        {{ old('id_categoria', $libro->id_categoria) == $categoria->id_categoria ? 'selected' : '' }}>
                                                    {{ $categoria->nombre_categoria }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="editorial" class="form-label">
                                            <i class="fas fa-building"></i> Editorial
                                        </label>
                                        <input type="text" class="form-control" id="editorial" name="editorial" 
                                               value="{{ old('editorial', $libro->editorial) }}" maxlength="100">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="anio_publicacion" class="form-label">
                                            <i class="fas fa-calendar"></i> Año
                                        </label>
                                        <input type="number" class="form-control" id="anio_publicacion" name="anio_publicacion" 
                                               value="{{ old('anio_publicacion', $libro->anio_publicacion) }}" 
                                               min="1000" max="{{ date('Y') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="numero_paginas" class="form-label">
                                            <i class="fas fa-file-alt"></i> Páginas
                                        </label>
                                        <input type="number" class="form-control" id="numero_paginas" name="numero_paginas" 
                                               value="{{ old('numero_paginas', $libro->numero_paginas) }}" min="1">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="idioma" class="form-label">
                                            <i class="fas fa-language"></i> Idioma
                                        </label>
                                        <select class="form-select" id="idioma" name="idioma">
                                            <option value="Espanol" {{ old('idioma', $libro->idioma) == 'Espanol' ? 'selected' : '' }}>Español</option>
                                            <option value="Ingles" {{ old('idioma', $libro->idioma) == 'Ingles' ? 'selected' : '' }}>Inglés</option>
                                            <option value="Frances" {{ old('idioma', $libro->idioma) == 'Frances' ? 'selected' : '' }}>Francés</option>
                                            <option value="Portugues" {{ old('idioma', $libro->idioma) == 'Portugues' ? 'selected' : '' }}>Portugués</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="stock_total" class="form-label">
                                            <i class="fas fa-boxes"></i> Stock Total *
                                        </label>
                                        <input type="number" class="form-control" id="stock_total" name="stock_total" 
                                               value="{{ old('stock_total', $libro->stock_total) }}" min="1" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="ubicacion" class="form-label">
                                            <i class="fas fa-map-marker-alt"></i> Ubicación
                                        </label>
                                        <input type="text" class="form-control" id="ubicacion" name="ubicacion" 
                                               value="{{ old('ubicacion', $libro->ubicacion) }}" maxlength="50">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="descripcion" class="form-label">
                                    <i class="fas fa-align-left"></i> Descripción
                                </label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $libro->descripcion) }}</textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('libros.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save"></i> Actualizar Libro
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