

<?php $__env->startSection('conten-wrapper'); ?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            
            <!-- Título mejorado -->
            <div class="text-center my-4">
                <h3 class="d-flex align-items-center justify-content-center">
                    <span class="me-2">Color de Modelo</span>
                    <span class="badge rounded-pill text-bg-success">Ver</span>
                </h3>
            </div>

            <!-- Barra de búsqueda con AJAX -->
            <div class="d-flex justify-content-center my-3">
                <form class="row g-3 justify-content-center w-100" id="searchForm">
                    <div class="col-md-8 position-relative">
                        <input type="text" id="globalSearchInput" class="form-control ps-5" 
                               placeholder="Buscar en todos los colores..." autocomplete="off">
                        <span class="position-absolute start-0 top-50 translate-middle-y ps-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                            </svg>
                        </span>
                    </div>
                </form>
            </div>

            <!-- Alertas -->
            <?php if(session('success')): ?>
                <div class="text-center">
                    <div class="alert alert-success d-inline-flex align-items-center py-1 px-2 rounded-3 shadow-sm" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi me-2" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                        </svg>
                        <small class="fw-semibold"><?php echo e(session('success')); ?></small>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="text-center">
                    <div class="alert alert-danger d-inline-flex align-items-center py-1 px-2 rounded-3 shadow-sm" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi me-2" viewBox="0 0 16 16">
                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                        </svg>
                        <small class="fw-semibold"><?php echo e(session('error')); ?></small>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Tarjeta contenedora para la tabla -->
            <div class="card shadow-sm rounded-3 border-0 overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="tablaColores" data-search-route="<?php echo e(route('colores.search')); ?>">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">ID Modelo</th>
                                    <th class="text-center">Color</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <?php $__empty_1 = true; $__currentLoopData = $colores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="text-center fw-semibold"><?php echo e($color->id_modelo ?? 'N/A'); ?></td>
                                        <td class="text-center fw-semibold"><?php echo e($color->nombre_color); ?></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-primary btn-sm" 
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalActualizar<?php echo e($color->id_colorM); ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                                </svg>
                                                <span class="d-none d-md-inline">Editar</span>
                                            </button>
                                            <?php echo $__env->make('ColorModelo.actualizar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-circle mb-2" viewBox="0 0 16 16">
                                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                                <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                                            </svg>
                                            <p class="mb-0">No hay colores registrados</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Paginación (se oculta durante búsqueda) -->
            <div id="paginationContainer">
                <?php if($colores->hasPages()): ?>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted small">
                        Mostrando <?php echo e($colores->firstItem()); ?> a <?php echo e($colores->lastItem()); ?> de <?php echo e($colores->total()); ?> registros
                    </div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm justify-content-center">
                            <?php if($colores->onFirstPage()): ?>
                                <li class="page-item disabled">
                                    <span class="page-link text-success border-success bg-white">&laquo;</span>
                                </li>
                            <?php else: ?>
                                <li class="page-item">
                                    <a class="page-link text-success border-success bg-white" href="<?php echo e($colores->previousPageUrl()); ?>" rel="prev">&laquo;</a>
                                </li>
                            <?php endif; ?>

                            <?php $__currentLoopData = $colores->getUrlRange(1, $colores->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($page == $colores->currentPage()): ?>
                                    <li class="page-item active" aria-current="page">
                                        <span class="page-link bg-success text-white border-success"><?php echo e($page); ?></span>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item">
                                        <a class="page-link text-success border-success" href="<?php echo e($url); ?>"><?php echo e($page); ?></a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <?php if($colores->hasMorePages()): ?>
                                <li class="page-item">
                                    <a class="page-link text-success border-success bg-white" href="<?php echo e($colores->nextPageUrl()); ?>" rel="next">&raquo;</a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <span class="page-link text-success border-success bg-white">&raquo;</span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('globalSearchInput');
    const tableBody = document.getElementById('tableBody');
    const paginationContainer = document.getElementById('paginationContainer');
    const searchRoute = document.getElementById('tablaColores').getAttribute('data-search-route');
    
    // Función para generar filas de la tabla
    function generateColorRow(color) {
        return `
            <tr>
                <td class="text-center fw-semibold">${color.id_modelo || 'N/A'}</td>
                <td class="text-center fw-semibold">${color.nombre_color}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-outline-primary btn-sm" 
                        data-bs-toggle="modal"
                        data-bs-target="#modalActualizar${color.id_colorM}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                        </svg>
                        <span class="d-none d-md-inline">Editar</span>
                    </button>
                </td>
            </tr>
        `;
    }

    // Búsqueda con AJAX
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.trim();
        
        if(searchTerm.length > 2) {
            // Ocultar paginación durante la búsqueda
            paginationContainer.style.display = 'none';
            
            // Mostrar loader
            tableBody.innerHTML = `
                <tr>
                    <td colspan="3" class="text-center py-4">
                        <div class="spinner-border text-success" role="status">
                            <span class="visually-hidden">Buscando...</span>
                        </div>
                    </td>
                </tr>
            `;
            
            // Hacer petición AJAX
            fetch(`${searchRoute}?q=${encodeURIComponent(searchTerm)}`)
                .then(response => response.json())
                .then(data => {
                    if(data.data.length > 0) {
                        tableBody.innerHTML = '';
                        data.data.forEach(color => {
                            tableBody.insertAdjacentHTML('beforeend', generateColorRow(color));
                        });
                    } else {
                        tableBody.innerHTML = `
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-circle mb-2" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                        <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                                    </svg>
                                    <p class="mb-0">No se encontraron colores</p>
                                </td>
                            </tr>
                        `;
                    }
                });
        } else if(searchTerm.length === 0) {
            // Restaurar tabla original si el campo está vacío
            paginationContainer.style.display = '';
            window.location.href = window.location.pathname; // Recarga sin parámetros de búsqueda
        }
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\alex2\Escritorio\Repositorios Git\Prueba1\resources\views/ColorModelo/vista.blade.php ENDPATH**/ ?>