# Sistema de Gestión de Denuncias Municipales

Sistema web desarrollado para gestionar denuncias ciudadanas sobre problemas urbanos.

**Desarrollado por:** Karen Milenka Segundo Arteaga  
**Año:** 2025

## Características

- ✅ CRUD completo de denuncias (Crear, Leer, Actualizar, Eliminar)
- ✅ Búsqueda por título, ciudadano o ubicación
- ✅ Paginación de resultados
- ✅ Estados de denuncias: Pendiente, En proceso, Resuelto
- ✅ Diseño responsive para móviles y escritorio
- ✅ Arquitectura en capas (Modelo, Controlador, Vista)
- ✅ API REST con PHP

## Requisitos

- PHP 7.4 o superior
- MySQL 5.7 o superior (o MariaDB)
- Servidor web (Apache recomendado)
- XAMPP, WAMP, LAMP o similar

## Instalación

### 1. Base de Datos

1. Abre phpMyAdmin o tu cliente MySQL preferido
2. Importa el archivo `database/denuncias.sql` o ejecuta el script SQL manualmente
3. Verifica que la base de datos `denuncias_municipio` se haya creado correctamente

### 2. Configuración

Edita el archivo `config/database.php` con tus credenciales de base de datos:

```php
private $host = "localhost";
private $db_name = "denuncias_municipio";
private $username = "root";  // Cambia según tu configuración
private $password = "";      // Cambia según tu configuración
```

### 3. Servidor Web

Si usas XAMPP:
1. Coloca el proyecto en `C:\xampp\htdocs\Prueba_nivel_del_Logro`
2. Inicia Apache y MySQL desde el panel de control de XAMPP
3. Abre tu navegador y visita: `http://localhost/Prueba_nivel_del_Logro`

## Estructura del Proyecto

```
Prueba_nivel_del_Logro/
├── api/
│   └── denuncias.php          # Endpoint API REST
├── assets/
│   ├── css/
│   │   └── style.css          # Estilos CSS
│   └── js/
│       └── app.js             # Lógica JavaScript
├── config/
│   └── database.php           # Configuración de BD
├── controllers/
│   └── DenunciaController.php # Controlador (Lógica de negocio)
├── models/
│   └── Denuncia.php           # Modelo (Capa de datos)
├── database/
│   └── denuncias.sql          # Script SQL
├── index.html                 # Página principal
└── README.md                  # Este archivo
```

## Arquitectura

El proyecto sigue una arquitectura en capas:

### Capa de Datos (Modelo)
- `models/Denuncia.php`: Maneja todas las operaciones con la base de datos

### Capa de Lógica de Negocio (Controlador)
- `controllers/DenunciaController.php`: Procesa las solicitudes y aplica la lógica de negocio

### Capa de Presentación (Vista)
- `index.html`: Interfaz de usuario
- `assets/css/style.css`: Estilos
- `assets/js/app.js`: Interactividad del frontend

### API REST
- `api/denuncias.php`: Endpoint que expone los servicios

## Funcionalidades

### Gestión de Denuncias

- **Crear**: Click en "Nuevo" → Llenar formulario → Guardar
- **Editar**: Click en el botón amarillo (lápiz) → Modificar → Guardar
- **Eliminar**: Click en el botón rojo (papelera) → Confirmar
- **Buscar**: Escribir en el campo de búsqueda → Click en "Buscar"
- **Paginación**: Navegar entre páginas usando los controles inferiores

### Campos de Denuncia

- **ID**: Auto-generado
- **Título**: Título de la denuncia (requerido)
- **Descripción**: Descripción detallada (requerido)
- **Ubicación**: Dirección o coordenadas (requerido)
- **Estado**: Pendiente, En proceso, Resuelto (requerido)
- **Ciudadano**: Nombre completo (requerido)
- **Teléfono**: Teléfono del ciudadano (opcional)
- **Fecha**: Se registra automáticamente

## API Endpoints

### GET `/api/denuncias.php`
Obtener lista de denuncias con paginación y búsqueda

**Parámetros:**
- `page`: Número de página (default: 1)
- `per_page`: Registros por página (default: 10)
- `search`: Término de búsqueda (opcional)
- `id`: ID de denuncia específica (opcional)

**Ejemplo:**
```
GET /api/denuncias.php?page=1&per_page=10&search=bache
```

### POST `/api/denuncias.php`
Crear una nueva denuncia

**Body (JSON):**
```json
{
  "titulo": "Bache en calle principal",
  "descripcion": "Descripción detallada",
  "ubicacion": "Calle Lora y Cordero 172",
  "estado": "Pendiente",
  "ciudadano": "Juan Pérez",
  "telefono_ciudadano": "987654321"
}
```

### PUT `/api/denuncias.php`
Actualizar una denuncia existente

**Body (JSON):**
```json
{
  "id": 1,
  "titulo": "Bache en calle principal",
  "descripcion": "Descripción actualizada",
  "ubicacion": "Calle Lora y Cordero 172",
  "estado": "En proceso",
  "ciudadano": "Juan Pérez",
  "telefono_ciudadano": "987654321"
}
```

### DELETE `/api/denuncias.php`
Eliminar una denuncia

**Body (JSON):**
```json
{
  "id": 1
}
```

## Diseño Responsive

El sistema está optimizado para:
- 📱 Dispositivos móviles (320px+)
- 📱 Tablets (768px+)
- 💻 Escritorio (1024px+)

## Tecnologías Utilizadas

- **Backend**: PHP 7.4+
- **Base de Datos**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Iconos**: Font Awesome 6.4.0
- **Arquitectura**: MVC (Modelo-Vista-Controlador)

## Notas de Desarrollo

- El código sigue las mejores prácticas de PHP
- Se utiliza PDO para prevenir inyección SQL
- Los datos se sanitizan antes de guardar
- El frontend utiliza fetch API para comunicación asíncrona
- Diseño moderno y profesional con colores corporativos

## Soporte

Para cualquier consulta o problema, contactar al desarrollador:
**Milenka Segundo Arteaga**

---

© 2025 PNL Ing. Sistemas. Todos los derechos reservados.

