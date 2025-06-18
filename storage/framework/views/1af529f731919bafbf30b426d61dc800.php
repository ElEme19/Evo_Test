

<?php $__env->startSection('conten-wrapper'); ?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            
            <!-- Encabezado -->
            <div class="text-center my-4">
                <h3 class="d-flex align-items-center justify-content-center">
                    <span class="me-2">Bicicletas Registradas</span>
                    <span class="badge rounded-pill text-bg-success">Ver</span>
                </h3>
            </div>

            <!-- Modales de búsqueda -->
            <?php echo $__env->make('Busquedas.busChasis', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->make('Busquedas.busMotor', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->make('Busquedas.busModelo', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->make('Busquedas.busStock', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <!-- Tarjeta contenedora de la tabla -->
            <div class="card shadow-sm rounded-3 border-0 overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="tablaBicicletas">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="text-center">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <span>Num. Serie</span>
                                            <i class="bi bi-search text-primary ms-2" data-bs-toggle="modal" 
                                               data-bs-target="#modalBuscarBici" title="Buscar por Num. Serie" 
                                               style="cursor: pointer;"></i>
                                        </div>
                                    </th>
                                    <th scope="col" class="text-center">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <span>Motor</span>
                                            <i class="bi bi-search text-primary ms-2" data-bs-toggle="modal" 
                                               data-bs-target="#modalBuscarBiciMotor" title="Buscar por Motor" 
                                               style="cursor: pointer;"></i>
                                        </div>
                                    </th>
                                    <th scope="col" class="text-center">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <span>Modelo</span>
                                            <i class="bi bi-search text-primary ms-2" data-bs-toggle="modal" 
                                               data-bs-target="#modalBuscarBiciModelo" title="Buscar por Modelo" 
                                               style="cursor: pointer;"></i>
                                        </div>
                                    </th>
                                    <th scope="col" class="text-center">Color</th>
                                    <th scope="col" class="text-center">Voltaje</th>
                                    <th scope="col" class="text-center">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <span>Stock</span>
                                            <i class="bi bi-search text-primary ms-2" data-bs-toggle="modal" 
                                               data-bs-target="#modalBuscarBiciStock" title="Buscar por Stock" 
                                               style="cursor: pointer;"></i>
                                        </div>
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $bicicletas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bici): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="text-center fw-semibold"><?php echo e($bici->num_chasis); ?></td>
                                    <td class="text-center"><?php echo e($bici->num_motor ?? 'N/A'); ?></td>
                                    <td class="text-center"><?php echo e($bici->modelo->nombre_modelo ?? 'N/A'); ?></td>
                                    <td class="text-center">
                                        <?php if($bici->color && $bici->color->nombre_color): ?>
                                            <?php echo e($bici->color->nombre_color); ?>

                                        <?php else: ?>
                                            N/A
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center"><?php echo e($bici->voltaje ?? 'N/A'); ?></td>
                                    <td class="text-center">
                                        <span class="badge 
                                            <?php if(isset($bici->tipoStock) && $bici->tipoStock->nombre_stock == 'Fabrica'): ?> bg-success
                                            <?php elseif(isset($bici->tipoStock) && $bici->tipoStock->nombre_stock == 'Nacional'): ?> bg-danger
                                            <?php elseif(isset($bici->tipoStock) && $bici->tipoStock->nombre_stock == 'San wicho'): ?> bg-warning text-dark
                                            <?php else: ?> bg-info
                                            <?php endif; ?>">
                                            <?php echo e($bici->tipoStock->nombre_stock ?? 'N/A'); ?>

                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-circle mb-2" viewBox="0 0 16 16">
                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                            <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                                        </svg>
                                        <p class="mb-0">No hay bicicletas registradas</p>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Paginación segura -->
            <?php if(method_exists($bicicletas, 'links')): ?>
                <div class="mt-3">
                    <?php echo e($bicicletas->links('pagination::bootstrap-4')); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Incluir CSS de Bootstrap e iconos -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\alex2\Escritorio\Repositorios Git\Prueba1\resources\views/bicicleta/vista.blade.php ENDPATH**/ ?>