/**
 * Aplicación JavaScript para Gestión de Denuncias
 * Desarrollado por: Milenka Segundo Arteaga
 * Año: 2025
 */

const API_URL = 'api/denuncias.php';

// Estado de la aplicación
let currentPage = 1;
let currentSearch = '';
let denunciaToDelete = null;
const perPage = 10;

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    inicializarEventos();
    inicializarNavegacion();
    cargarDenuncias();
    cargarEstadisticas();
});

/**
 * Inicializar eventos
 */
function inicializarEventos() {
    // Toggle sidebar en móvil
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    
    if(menuToggle) {
        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });
    }

    // Botón nuevo
    document.getElementById('btnNuevo').addEventListener('click', () => {
        abrirModalNuevo();
    });

    // Botón buscar
    document.getElementById('btnBuscar').addEventListener('click', () => {
        buscarDenuncias();
    });

    // Enter en búsqueda
    document.getElementById('searchInput').addEventListener('keypress', (e) => {
        if(e.key === 'Enter') {
            buscarDenuncias();
        }
    });

    // Cerrar modales
    document.getElementById('modalClose').addEventListener('click', cerrarModalDenuncia);
    document.getElementById('btnCerrarModal').addEventListener('click', cerrarModalDenuncia);
    document.getElementById('btnCerrarEliminar').addEventListener('click', cerrarModalEliminar);

    // Guardar denuncia
    document.getElementById('btnGuardar').addEventListener('click', guardarDenuncia);

    // Confirmar eliminación
    document.getElementById('btnConfirmarEliminar').addEventListener('click', confirmarEliminar);

    // Cerrar modal al hacer clic fuera
    document.getElementById('modalDenuncia').addEventListener('click', (e) => {
        if(e.target.id === 'modalDenuncia') {
            cerrarModalDenuncia();
        }
    });

    document.getElementById('modalEliminar').addEventListener('click', (e) => {
        if(e.target.id === 'modalEliminar') {
            cerrarModalEliminar();
        }
    });

    // Validaciones en tiempo real
    document.getElementById('telefono').addEventListener('input', validarTelefono);
    document.getElementById('telefono').addEventListener('blur', validarTelefono);
    document.getElementById('ciudadano').addEventListener('input', validarCiudadano);
    document.getElementById('ciudadano').addEventListener('blur', validarCiudadano);
}

/**
 * Inicializar navegación entre páginas
 */
function inicializarNavegacion() {
    const navItems = document.querySelectorAll('.nav-item');
    
    navItems.forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            const page = item.getAttribute('data-page');
            cambiarPagina(page);
            
            // Actualizar clase activa
            navItems.forEach(nav => nav.classList.remove('active'));
            item.classList.add('active');
        });
    });
}

/**
 * Cambiar de página
 */
function cambiarPagina(page) {
    // Ocultar todas las páginas
    document.querySelectorAll('.page-content').forEach(p => {
        p.style.display = 'none';
    });
    
    // Mostrar la página seleccionada
    const pageElement = document.getElementById(`page${page.charAt(0).toUpperCase() + page.slice(1)}`);
    if(pageElement) {
        pageElement.style.display = 'block';
    }
    
    // Actualizar breadcrumb
    actualizarBreadcrumb(page);
    
    // Cargar datos según la página
    if(page === 'escritorio') {
        cargarEstadisticas();
    }
}

/**
 * Actualizar breadcrumb
 */
function actualizarBreadcrumb(page) {
    const breadcrumb = document.getElementById('breadcrumb') || document.querySelector('.breadcrumb');
    const pageNames = {
        'escritorio': 'Escritorio',
        'denuncias': 'Denuncias',
        'acerca': 'Acerca de'
    };
    
    const icons = {
        'escritorio': 'th-large',
        'denuncias': 'building',
        'acerca': 'info-circle'
    };
    
    breadcrumb.innerHTML = `
        <a href="#" data-page="escritorio"><i class="fas fa-th-large"></i> Escritorio</a>
        <span class="separator">/</span>
        <a href="#" data-page="${page}"><i class="fas fa-${icons[page]}"></i> ${pageNames[page]}</a>
    `;
    
    // Agregar eventos a los links del breadcrumb
    breadcrumb.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const targetPage = link.getAttribute('data-page');
            cambiarPagina(targetPage);
            
            // Actualizar sidebar
            document.querySelectorAll('.nav-item').forEach(nav => {
                nav.classList.remove('active');
                if(nav.getAttribute('data-page') === targetPage) {
                    nav.classList.add('active');
                }
            });
        });
    });
}

/**
 * Validar teléfono (solo números)
 */
function validarTelefono() {
    const telefono = document.getElementById('telefono');
    const errorMsg = document.getElementById('errorTelefono');
    const valor = telefono.value.trim();
    
    // Limpiar mensaje de error
    errorMsg.textContent = '';
    telefono.classList.remove('error');
    
    if(valor === '') {
        return true; // Campo opcional
    }
    
    // Verificar que solo contenga números
    if(!/^\d+$/.test(valor)) {
        errorMsg.textContent = 'El teléfono solo puede contener números';
        telefono.classList.add('error');
        return false;
    }
    
    // Verificar longitud
    if(valor.length < 7 || valor.length > 15) {
        errorMsg.textContent = 'El teléfono debe tener entre 7 y 15 dígitos';
        telefono.classList.add('error');
        return false;
    }
    
    return true;
}

/**
 * Validar ciudadano (no puede tener números)
 */
function validarCiudadano() {
    const ciudadano = document.getElementById('ciudadano');
    const errorMsg = document.getElementById('errorCiudadano');
    const valor = ciudadano.value.trim();
    
    // Limpiar mensaje de error
    errorMsg.textContent = '';
    ciudadano.classList.remove('error');
    
    if(valor === '') {
        return true; // Se validará con required
    }
    
    // Verificar que no contenga números
    if(/\d/.test(valor)) {
        errorMsg.textContent = 'El nombre del ciudadano no puede contener números';
        ciudadano.classList.add('error');
        return false;
    }
    
    // Verificar que tenga al menos 3 caracteres
    if(valor.length < 3) {
        errorMsg.textContent = 'El nombre debe tener al menos 3 caracteres';
        ciudadano.classList.add('error');
        return false;
    }
    
    return true;
}

/**
 * Cargar estadísticas para el escritorio
 */
async function cargarEstadisticas() {
    try {
        const response = await fetch(`${API_URL}?page=1&per_page=1000`);
        const responseText = await response.text();
        
        if(!response.ok) {
            return;
        }
        
        const data = JSON.parse(responseText);
        
        if(data.success && data.data) {
            const denuncias = data.data;
            const total = denuncias.length;
            const pendientes = denuncias.filter(d => d.estado === 'Pendiente').length;
            const enProceso = denuncias.filter(d => d.estado === 'En proceso').length;
            const resueltas = denuncias.filter(d => d.estado === 'Resuelto').length;
            
            // Actualizar estadísticas
            document.getElementById('totalDenuncias').textContent = total;
            document.getElementById('denunciasPendientes').textContent = pendientes;
            document.getElementById('denunciasProceso').textContent = enProceso;
            document.getElementById('denunciasResueltas').textContent = resueltas;
            
            // Cargar actividad reciente
            cargarActividadReciente(denuncias.slice(0, 5));
        }
    } catch(error) {
        console.error('Error al cargar estadísticas:', error);
    }
}

/**
 * Cargar actividad reciente
 */
function cargarActividadReciente(denuncias) {
    const activityList = document.getElementById('activityList');
    
    if(denuncias.length === 0) {
        activityList.innerHTML = '<p class="empty-activity">No hay actividad reciente</p>';
        return;
    }
    
    activityList.innerHTML = denuncias.map(denuncia => `
        <div class="activity-item">
            <div class="activity-icon status-${getEstadoClass(denuncia.estado)}">
                <i class="fas fa-${denuncia.estado === 'Resuelto' ? 'check-circle' : denuncia.estado === 'En proceso' ? 'spinner' : 'clock'}"></i>
            </div>
            <div class="activity-content">
                <h4>${escapeHtml(denuncia.titulo)}</h4>
                <p>${escapeHtml(denuncia.ciudadano)} - ${denuncia.fecha}</p>
            </div>
            <div class="activity-status">
                <span class="status-badge status-${getEstadoClass(denuncia.estado)}">
                    ${escapeHtml(denuncia.estado)}
                </span>
            </div>
        </div>
    `).join('');
}

/**
 * Cargar denuncias desde la API
 */
async function cargarDenuncias(page = 1, search = '') {
    try {
        mostrarLoading();
        
        const params = new URLSearchParams({
            page: page,
            per_page: perPage
        });
        
        if(search) {
            params.append('search', search);
        }

        const response = await fetch(`${API_URL}?${params}`);
        
        // Obtener el texto de la respuesta primero
        const responseText = await response.text();
        
        // Verificar si la respuesta es OK
        if(!response.ok) {
            let errorMessage = 'Error al cargar las denuncias';
            
            try {
                const errorData = JSON.parse(responseText);
                errorMessage = errorData.mensaje || errorData.error || errorMessage;
            } catch(e) {
                // Si no es JSON, mostrar el texto tal cual (puede ser HTML de error de PHP)
                errorMessage = responseText || `Error HTTP ${response.status}`;
                // Limitar la longitud del mensaje
                if(errorMessage.length > 200) {
                    errorMessage = errorMessage.substring(0, 200) + '...';
                }
            }
            
            mostrarError(errorMessage);
            ocultarLoading();
            return;
        }
        
        // Intentar parsear como JSON
        let data;
        try {
            data = JSON.parse(responseText);
        } catch(e) {
            console.error('Error al parsear JSON:', e);
            console.error('Respuesta recibida:', responseText);
            mostrarError('Error al procesar la respuesta del servidor. La respuesta no es JSON válido. Verifica la consola para más detalles.');
            ocultarLoading();
            return;
        }

        if(data.success) {
            mostrarDenuncias(data.data);
            mostrarPaginacion(data.pagination);
            currentPage = page;
            currentSearch = search;
        } else {
            mostrarError(data.mensaje || data.error || 'Error al cargar las denuncias');
        }
    } catch(error) {
        console.error('Error:', error);
        
        // Mensaje más específico según el tipo de error
        let errorMessage = 'Error de conexión con el servidor';
        
        if(error.message.includes('Failed to fetch') || error.message.includes('NetworkError')) {
            errorMessage = 'No se pudo conectar al servidor. Verifica que Apache esté corriendo y que la ruta de la API sea correcta.';
        } else if(error.message.includes('JSON')) {
            errorMessage = 'Error al procesar la respuesta del servidor. Verifica la consola para más detalles.';
        }
        
        mostrarError(errorMessage);
    } finally {
        ocultarLoading();
    }
}

/**
 * Mostrar denuncias en la tabla
 */
function mostrarDenuncias(denuncias) {
    const tbody = document.getElementById('denunciasBody');
    
    if(denuncias.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>No hay denuncias registradas</h3>
                    <p>Haz clic en "Nuevo" para crear una denuncia</p>
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = denuncias.map(denuncia => `
        <tr>
            <td>
                <div class="action-buttons">
                    <button class="action-btn edit" onclick="editarDenuncia(${denuncia.id})" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-btn delete" onclick="eliminarDenuncia(${denuncia.id})" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
            <td>${denuncia.id}</td>
            <td>${escapeHtml(denuncia.titulo)}</td>
            <td>${escapeHtml(denuncia.descripcion)}</td>
            <td>${escapeHtml(denuncia.ubicacion)}</td>
            <td>${escapeHtml(denuncia.ciudadano)}</td>
            <td>${denuncia.fecha}</td>
            <td>
                <span class="status-badge status-${getEstadoClass(denuncia.estado)}">
                    ${escapeHtml(denuncia.estado)}
                </span>
            </td>
        </tr>
    `).join('');
}

/**
 * Obtener clase CSS para el estado
 */
function getEstadoClass(estado) {
    const estados = {
        'Pendiente': 'pendiente',
        'En proceso': 'proceso',
        'Resuelto': 'resuelto'
    };
    return estados[estado] || 'pendiente';
}

/**
 * Mostrar paginación
 */
function mostrarPaginacion(pagination) {
    const paginationDiv = document.getElementById('pagination');
    const { current_page, total_pages } = pagination;

    if(total_pages <= 1) {
        paginationDiv.innerHTML = '';
        return;
    }

    let html = `
        <button onclick="cargarDenuncias(${current_page - 1}, '${currentSearch}')" 
                ${current_page === 1 ? 'disabled' : ''}>
            Anterior
        </button>
    `;

    for(let i = 1; i <= total_pages; i++) {
        if(i === 1 || i === total_pages || (i >= current_page - 1 && i <= current_page + 1)) {
            html += `
                <button class="page-number ${i === current_page ? 'active' : ''}" 
                        onclick="cargarDenuncias(${i}, '${currentSearch}')">
                    ${i}
                </button>
            `;
        } else if(i === current_page - 2 || i === current_page + 2) {
            html += `<span>...</span>`;
        }
    }

    html += `
        <button onclick="cargarDenuncias(${current_page + 1}, '${currentSearch}')" 
                ${current_page === total_pages ? 'disabled' : ''}>
            Siguiente
        </button>
    `;

    paginationDiv.innerHTML = html;
}

/**
 * Buscar denuncias
 */
function buscarDenuncias() {
    const search = document.getElementById('searchInput').value.trim();
    cargarDenuncias(1, search);
}

/**
 * Abrir modal para nueva denuncia
 */
function abrirModalNuevo() {
    const modal = document.getElementById('modalDenuncia');
    const form = document.getElementById('formDenuncia');
    const modalTitle = document.getElementById('modalTitle');

    modalTitle.textContent = 'Nuevo Reporte de Denuncia';
    form.reset();
    document.getElementById('denunciaId').value = '';
    
    // Limpiar mensajes de error
    document.getElementById('errorTelefono').textContent = '';
    document.getElementById('errorCiudadano').textContent = '';
    document.getElementById('telefono').classList.remove('error');
    document.getElementById('ciudadano').classList.remove('error');
    
    modal.classList.add('active');
}

/**
 * Editar denuncia
 */
async function editarDenuncia(id) {
    try {
        mostrarLoading();
        
        const response = await fetch(`${API_URL}?id=${id}`);
        
        if(!response.ok) {
            const errorText = await response.text();
            let errorMessage = 'Error al cargar la denuncia';
            try {
                const errorData = JSON.parse(errorText);
                errorMessage = errorData.mensaje || errorData.error || errorMessage;
            } catch(e) {
                errorMessage = errorText || `Error HTTP ${response.status}`;
            }
            mostrarError(errorMessage);
            ocultarLoading();
            return;
        }
        
        const data = await response.json();

        if(data.success) {
            const denuncia = data.data;
            const modal = document.getElementById('modalDenuncia');
            const form = document.getElementById('formDenuncia');
            const modalTitle = document.getElementById('modalTitle');

            modalTitle.textContent = 'Editar Reporte de Denuncia';
            document.getElementById('denunciaId').value = denuncia.id;
            document.getElementById('titulo').value = denuncia.titulo;
            document.getElementById('descripcion').value = denuncia.descripcion;
            document.getElementById('ubicacion').value = denuncia.ubicacion;
            document.getElementById('estado').value = denuncia.estado;
            document.getElementById('ciudadano').value = denuncia.ciudadano;
            document.getElementById('telefono').value = denuncia.telefono_ciudadano || '';
            document.getElementById('fecha').value = denuncia.fecha;
            
            // Limpiar mensajes de error
            document.getElementById('errorTelefono').textContent = '';
            document.getElementById('errorCiudadano').textContent = '';
            document.getElementById('telefono').classList.remove('error');
            document.getElementById('ciudadano').classList.remove('error');

            modal.classList.add('active');
        } else {
            mostrarError(data.mensaje || data.error || 'Error al cargar la denuncia');
        }
    } catch(error) {
        console.error('Error:', error);
        mostrarError('Error de conexión con el servidor: ' + error.message);
    } finally {
        ocultarLoading();
    }
}

/**
 * Guardar denuncia (crear o actualizar)
 */
async function guardarDenuncia() {
    const form = document.getElementById('formDenuncia');
    const formData = new FormData(form);
    const id = document.getElementById('denunciaId').value;

    const denuncia = {
        titulo: formData.get('titulo'),
        descripcion: formData.get('descripcion'),
        ubicacion: formData.get('ubicacion'),
        estado: formData.get('estado'),
        ciudadano: formData.get('ciudadano'),
        telefono_ciudadano: formData.get('telefono_ciudadano')
    };

    if(id) {
        denuncia.id = id;
    }

    // Validaciones
    if(!denuncia.titulo || !denuncia.descripcion || !denuncia.ubicacion || 
       !denuncia.estado || !denuncia.ciudadano) {
        alert('Por favor, complete todos los campos requeridos');
        return;
    }
    
    // Validar teléfono
    if(!validarTelefono()) {
        alert('Por favor, corrija el campo de teléfono');
        return;
    }
    
    // Validar ciudadano
    if(!validarCiudadano()) {
        alert('Por favor, corrija el campo de ciudadano');
        return;
    }

    try {
        mostrarLoading();

        const method = id ? 'PUT' : 'POST';
        const response = await fetch(API_URL, {
            method: method,
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(denuncia)
        });

        const data = await response.json();

        if(data.success) {
            cerrarModalDenuncia();
            cargarDenuncias(currentPage, currentSearch);
            cargarEstadisticas(); // Actualizar estadísticas del escritorio
            alert(id ? 'Denuncia actualizada exitosamente' : 'Denuncia creada exitosamente');
        } else {
            mostrarError(data.mensaje || 'Error al guardar la denuncia');
        }
    } catch(error) {
        console.error('Error:', error);
        mostrarError('Error de conexión con el servidor');
    } finally {
        ocultarLoading();
    }
}

/**
 * Eliminar denuncia
 */
function eliminarDenuncia(id) {
    denunciaToDelete = id;
    const modal = document.getElementById('modalEliminar');
    modal.classList.add('active');
}

/**
 * Confirmar eliminación
 */
async function confirmarEliminar() {
    if(!denunciaToDelete) return;

    try {
        mostrarLoading();

        const response = await fetch(API_URL, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: denunciaToDelete })
        });

        const data = await response.json();

        if(data.success) {
            cerrarModalEliminar();
            cargarDenuncias(currentPage, currentSearch);
            cargarEstadisticas(); // Actualizar estadísticas del escritorio
            alert('Denuncia eliminada exitosamente');
        } else {
            mostrarError(data.mensaje || 'Error al eliminar la denuncia');
        }
    } catch(error) {
        console.error('Error:', error);
        mostrarError('Error de conexión con el servidor');
    } finally {
        ocultarLoading();
        denunciaToDelete = null;
    }
}

/**
 * Cerrar modal de denuncia
 */
function cerrarModalDenuncia() {
    const modal = document.getElementById('modalDenuncia');
    modal.classList.remove('active');
}

/**
 * Cerrar modal de eliminación
 */
function cerrarModalEliminar() {
    const modal = document.getElementById('modalEliminar');
    modal.classList.remove('active');
    denunciaToDelete = null;
}

/**
 * Mostrar loading
 */
function mostrarLoading() {
    const tbody = document.getElementById('denunciasBody');
    tbody.innerHTML = `
        <tr>
            <td colspan="8" class="loading">
                <div class="spinner"></div>
            </td>
        </tr>
    `;
}

/**
 * Ocultar loading
 */
function ocultarLoading() {
    // Se reemplazará cuando se carguen los datos
}

/**
 * Mostrar error
 */
function mostrarError(mensaje) {
    // Mostrar en consola para debugging
    console.error('Error:', mensaje);
    
    // Mostrar alerta al usuario
    alert(mensaje);
    
    // También mostrar en la tabla si está vacía
    const tbody = document.getElementById('denunciasBody');
    if(tbody && tbody.children.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="empty-state">
                    <i class="fas fa-exclamation-triangle" style="color: #dc2626;"></i>
                    <h3>Error de conexión</h3>
                    <p>${escapeHtml(mensaje)}</p>
                    <p style="margin-top: 10px; font-size: 12px; color: #6b7280;">
                        Verifica: 1) MySQL está corriendo, 2) La base de datos existe, 3) Las credenciales son correctas
                    </p>
                </td>
            </tr>
        `;
    }
}

/**
 * Escapar HTML para prevenir XSS
 */
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

