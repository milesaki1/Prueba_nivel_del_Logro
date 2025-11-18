# Guía Rápida de Instalación

## Paso 1: Importar Base de Datos

1. Abre **phpMyAdmin** (http://localhost/phpmyadmin)
2. Haz clic en **"Nueva"** para crear una base de datos
3. O simplemente importa el archivo `database/denuncias.sql` que creará la base de datos automáticamente

## Paso 2: Configurar Credenciales

Edita el archivo `config/database.php`:

```php
private $host = "localhost";        // No cambiar si usas XAMPP
private $db_name = "denuncias_municipio";
private $username = "root";         // Tu usuario de MySQL
private $password = "";             // Tu contraseña de MySQL (vacía por defecto en XAMPP)
```

## Paso 3: Acceder a la Aplicación

1. Asegúrate de que **Apache** y **MySQL** estén corriendo en XAMPP
2. Abre tu navegador y visita:
   ```
   http://localhost/Prueba_nivel_del_Logro
   ```

## Verificación

Si todo está correcto, deberías ver:
- ✅ La página de inicio con la tabla de denuncias
- ✅ 5 denuncias de ejemplo cargadas
- ✅ Botones funcionales (Nuevo, Editar, Eliminar, Buscar)

## Solución de Problemas

### Error: "Error de conexión"
- Verifica que MySQL esté corriendo
- Revisa las credenciales en `config/database.php`
- Asegúrate de que la base de datos existe

### Error 404 en la API
- Verifica que el archivo `.htaccess` esté presente
- Asegúrate de que `mod_rewrite` esté habilitado en Apache

### No se muestran las denuncias
- Abre la consola del navegador (F12) y revisa errores
- Verifica que la API responda: `http://localhost/Prueba_nivel_del_Logro/api/denuncias.php`

## Datos de Prueba

El script SQL incluye 5 denuncias de ejemplo que se cargarán automáticamente.

---

**Desarrollado por:** Milenka Segundo Arteaga  
**Año:** 2025

