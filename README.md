# 📚 Sistema de Préstamo de Libros - Biblioteca Virtual

## 📋 Descripción del Proyecto

Sistema completo de gestión bibliotecaria desarrollado con **Laravel** y **Oracle Database**, implementando lógica de negocio en **PL/SQL**. Permite gestionar libros, autores, categorías y préstamos de manera integral.

## 🏗️ Arquitectura

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   FRONTEND      │    │    BACKEND      │    │   BASE DATOS    │
│   (Blade/HTML)  │◄──►│   (Laravel)     │◄──►│  (Oracle+PL/SQL)│
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

### Tecnologías Utilizadas

- **Backend**: Laravel 10
- **Frontend**: Blade Templates + Bootstrap 5
- **Base de Datos**: Oracle Database 19c
- **Lógica**: PL/SQL (Packages, Triggers, Procedures)
- **Servidor**: PHP 8.2 + Apache/XAMPP

## 🚀 Funcionalidades Implementadas

### 📖 Módulo: Gestión de Libros
- ✅ **CRUD completo** de libros
- ✅ **Gestión de categorías** y autores
- ✅ **Control automático de stock**
- ✅ **Búsquedas avanzadas** por título, autor, categoría
- ✅ **Validaciones** de datos

### 🤝 Módulo: Gestión de Préstamos
- ✅ **Registro de préstamos** con validaciones
- ✅ **Devolución de libros** con cálculo de multas
- ✅ **Control de préstamos vencidos**
- ✅ **Renovación de préstamos**
- ✅ **Límite de 3 préstamos por usuario**
- ✅ **Multas automáticas** (S/. 2.00 por día de retraso)

### ⚡ Características Avanzadas
- 🔄 **Actualización automática de stock**
- 📊 **Estadísticas en tiempo real**
- 🛡️ **Triggers para validaciones**
- 📝 **Auditoría de cambios**
- 💰 **Cálculo automático de multas**

## 📦 Instalación y Configuración

### Prerrequisitos

1. **Oracle Database 19c** instalado y funcionando
2. **PHP 8.2+** con extensiones:
   - `oci8` (Oracle)
   - `pdo_oci` (PDO Oracle)
3. **Composer** para dependencias PHP
4. **Laravel 10** compatible

### Pasos de Instalación

#### 1. Configurar Base de Datos Oracle

```sql
-- 1. Crear usuario (como SYSTEM)
CREATE USER prestalibros IDENTIFIED BY prestalibros123;

-- 2. Otorgar permisos
GRANT CONNECT, RESOURCE TO prestalibros;
GRANT CREATE SESSION TO prestalibros;
GRANT CREATE TABLE TO prestalibros;
GRANT CREATE SEQUENCE TO prestalibros;
GRANT CREATE PROCEDURE TO prestalibros;
GRANT CREATE TRIGGER TO prestalibros;
GRANT CREATE VIEW TO prestalibros;
GRANT UNLIMITED TABLESPACE TO prestalibros;
```

#### 2. Instalar Laravel

```bash
# Clonar o crear proyecto
composer create-project laravel/laravel="^10.0" biblioteca-virtual
cd biblioteca-virtual

# Instalar driver Oracle
composer require yajra/laravel-oci8:"^10.0"
php artisan vendor:publish --tag=oracle
```

#### 3. Configurar Variables de Entorno

Crear archivo `.env`:
```env
APP_NAME=BibliotecaVirtual
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=oracle
DB_HOST=localhost
DB_PORT=1521
DB_DATABASE=orclpdb
DB_SERVICE_NAME=orclpdb
DB_USERNAME=prestalibros
DB_PASSWORD=prestalibros123
```

#### 4. Ejecutar Scripts de Base de Datos

```bash
# Conectarse a Oracle como prestalibros
sqlplus prestalibros/prestalibros123@XE

# Ejecutar en orden:
# 1. Crear tablas
# 2. Crear packages
# 3. Crear triggers
# 4. Insertar datos de prueba
```

#### 5. Configurar Laravel

```bash
# Generar clave de aplicación
php artisan key:generate

# Limpiar cache
php artisan config:clear
php artisan cache:clear

# Iniciar servidor
php artisan serve
```

## 🗄️ Estructura de Base de Datos

### Tablas Principales

| Tabla | Descripción |
|-------|-------------|
| `CATEGORIAS` | Categorías de libros |
| `AUTORES` | Información de autores |
| `LIBROS` | Catálogo principal |
| `USUARIOS` | Usuarios del sistema |
| `PRESTAMOS` | Registro de préstamos |
| `AUDITORIA_STOCK` | Auditoría de cambios |

### Packages PL/SQL

| Package | Funcionalidad |
|---------|---------------|
| `PKG_CATEGORIAS` | CRUD de categorías |
| `PKG_AUTORES` | CRUD de autores |
| `PKG_LIBROS` | CRUD de libros + stock |
| `PKG_PRESTAMOS` | Gestión completa de préstamos |

### Triggers Automáticos

- **Control de stock** al crear/eliminar préstamos
- **Validación de fechas** de préstamos
- **Auditoría** de cambios de stock
- **Actualización automática** de préstamos vencidos

## 🌐 Rutas de la Aplicación

### Frontend Web
- `/` - Página principal (redirige a libros)
- `/libros` - Lista de libros
- `/libros/crear` - Formulario nuevo libro
- `/autores` - Gestión de autores
- `/categorias` - Gestión de categorías
- `/prestamos` - Lista de préstamos activos
- `/prestamos/crear` - Formulario nuevo préstamo
- `/prestamos/vencidos` - Lista de préstamos vencidos
- `/prestamos/historial` - Consulta de historial

### API REST
- `GET /api/v1/libros` - Obtener todos los libros
- `POST /api/v1/libros` - Crear nuevo libro
- `GET /api/v1/prestamos` - Obtener préstamos activos
- `POST /api/v1/prestamos` - Registrar préstamo
- `PUT /api/v1/prestamos/{id}/devolver` - Devolver libro

## 🔧 Uso del Sistema

### Gestionar Libros
1. Ir a `/libros`
2. Click "Nuevo Libro"
3. Llenar formulario con datos del libro
4. Seleccionar autor y categoría existentes
5. Guardar - El stock se actualiza automáticamente

### Registrar Préstamos
1. Ir a `/prestamos/crear`
2. Seleccionar usuario y libro disponible
3. Elegir días de préstamo (7, 15, 21, 30)
4. Agregar observaciones opcionales
5. Confirmar - Stock se reduce automáticamente

### Devolver Libros
1. Ir a `/prestamos`
2. Click "Devolver" en préstamo activo
3. Agregar observaciones de devolución
4. Confirmar - Stock se restaura, multa se calcula automáticamente

## 📊 Características del Negocio

### Reglas de Préstamos
- **Límite**: 3 préstamos activos por usuario
- **Duración**: 7-30 días configurable
- **Multa**: S/. 2.00 por día de retraso
- **Stock**: Control automático de disponibilidad

### Validaciones Automáticas
- Stock disponible antes de préstamo
- Límite de préstamos por usuario
- Fechas válidas de préstamo/devolución
- Integridad referencial entre tablas

## 📈 Métricas del Sistema

- **5 Tablas principales** + 1 auxiliar
- **4 Packages PL/SQL** con 25+ procedures/functions
- **6 Triggers automáticos** para validaciones
- **15 Endpoints** funcionales
- **8 Páginas web** responsivas
- **Frontend Bootstrap 5** moderno


### Archivos de Código
- Proyecto Laravel completo
- Scripts de base de datos Oracle
- Packages PL/SQL implementados
- Triggers y validaciones

**Nota**: De los 4 módulos indicados, se programaron 2 módulos obligatorios con lógica PL/SQL y llamadas desde Laravel.