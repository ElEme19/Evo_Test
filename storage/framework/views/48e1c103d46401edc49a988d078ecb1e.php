

<?php $__env->startSection('conten'); ?>

<div class="text-center my-4">
    <h3>
        <span class="badge rounded-pill text-bg-success">Precios</span>
    </h3>
</div>

<?php if(session('success')): ?>
    <div class="text-center">
        <div class="alert alert-warning d-inline-flex align-items-center py-1 px-2 rounded-3 shadow-sm" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                fill="currentColor" class="bi me-2" viewBox="0 0 16 16" role="img"
                aria-label="success:">
                <path
                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
            </svg>
            <small class="fw-semibold">
                <?php echo e(session('success')); ?>

            </small>
        </div>
    </div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div class="text-center">
        <div class="alert alert-danger d-inline-flex align-items-center py-1 px-2 rounded-3 shadow-sm" role="alert">
            <strong><?php echo e(session('error')); ?></strong>
        </div>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-center my-3">
    <form class="row g-3 justify-content-center">
        <div class="col-md-12">
            <input type="text" id="inputBuscar" class="form-control" placeholder="Buscar precio o ID...">
        </div>
    </form>
</div>

<?php if(auth()->user()->rol == 0): ?>
    <div class="text-center mb-3">
        <a href="<?php echo e(route('Precio.create')); ?>" class="btn btn-primary">Crear Nuevo Precio</a>
    </div>
<?php endif; ?>

<div class="container mt-4"></div>
<table class="table table-bordered table-hover mt-5" id="tablaPrecios">
    <thead class="table-light">
        <tr class="text-center">
            <th>ID Precio</th>
            <th>Nombre Modelo</th>
            <th>Tipo Membresía</th>
            <th>Seleccionar Precio</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $precios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr class="text-center">
                <td><?php echo e($p->id_precio); ?></td>
                <td><?php echo e($p->modelo->nombre_modelo ?? 'Sin modelo'); ?></td>
                <td><?php echo e($p->membresia->tipo_membresia ?? 'Sin tipo'); ?></td>
                <td>
                    <select class="form-select form-select-sm" disabled>
                        <?php $__currentLoopData = $precios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $op): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($op->id_precio); ?>" <?php echo e($p->id_precio == $op->id_precio ? 'selected' : ''); ?>>
                                $<?php echo e(number_format($op->precio, 2)); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </td>
                <td>
                    <button type="button" class="btn btn-outline-success"
                        data-bs-toggle="modal"
                        data-bs-target="#modalActualizar<?php echo e($p->id_precio); ?>">
                        Actualizar
                    </button>
                    <?php echo $__env->make('Precio.update', ['precio' => $p], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="5" class="text-center">No hay precios registrados.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('inputBuscar');
        const tabla = document.getElementById('tablaPrecios').getElementsByTagName('tbody')[0];

        const normalizarTexto = (texto) => {
            return texto
                .toLowerCase()
                .normalize("NFD")
                .replace(/[\u0300-\u036f]/g, "")
                .replace(/\s+/g, " ")
                .trim();
        };

        input.addEventListener('input', () => {
            const filtro = normalizarTexto(input.value);
            const filas = tabla.querySelectorAll('tr');

            filas.forEach(fila => {
                const celdas = fila.querySelectorAll('td');
                let coincide = false;

                celdas.forEach(celda => {
                    const textoCelda = normalizarTexto(celda.textContent);
                    if (textoCelda.includes(filtro)) {
                        coincide = true;
                    }
                });

                fila.style.display = coincide ? '' : 'none';
            });
        });
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\alex2\Escritorio\Repositorios Git\Prueba1\resources\views/Precio/index.blade.php ENDPATH**/ ?>