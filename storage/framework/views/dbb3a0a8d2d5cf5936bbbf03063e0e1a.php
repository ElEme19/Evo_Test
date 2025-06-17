

<?php $__env->startSection('conten-wrapper'); ?>

<div class="text-center my-4">
    <h3>
        <span class="badge rounded-pill text-bg-success">Clientes</span>
    </h3>
</div>

<?php if(session('success')): ?>
    <div class="text-center">
        <div class="alert alert-warning d-inline-flex align-items-center py-1 px-2 rounded-3 shadow-sm" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg"
                width="16" height="16"
                fill="currentColor"
                class="bi me-2" viewBox="0 0 16 16" role="img"
                aria-label="success:">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
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
            <input type="text" id="inputBuscar" class="form-control" placeholder="Buscar cliente o ID...">
        </div>
    </form>
</div>

<?php if(auth()->user()->rol == 0): ?>
    <div class="text-center mb-3">
        <a href="<?php echo e(route('Clientes.create')); ?>" class="btn btn-primary">Crear Nuevo Cliente</a>
    </div>
<?php endif; ?>

<div class="container mt-4"></div>
<table class="table table-bordered table-hover mt-5" id="tablaClientes">
    <thead class="table-light">
        <tr class="text-center">
            <th class="text-center">Foto</th>
            <th class="text-center">ID Cliente</th>
            <th class="text-center">Nombre</th>
            <th class="text-center">Apellido</th>
            <th class="text-center">Teléfono</th>
            <th class="text-center">Membresía</th>
            <th class="text-center">Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr class="text-center">
                <td>
                    <?php if($c->foto_persona): ?>
                        <img src="<?php echo e(asset('img/clientes/' . $c->foto_persona)); ?>" alt="Foto" width="50" class="rounded-circle">
                    <?php else: ?>
                        <span class="text-muted">Sin foto</span>
                    <?php endif; ?>
                </td>
                <td><?php echo e($c->id_cliente); ?></td>
                <td><?php echo e($c->nombre); ?></td>
                <td><?php echo e($c->apellido); ?></td>
                <td><?php echo e($c->telefono); ?></td>
                <td><?php echo e($c->membresia->descripcion_general ?? 'Sin membresía'); ?></td>
                <td>
                    <button type="button" class="btn btn-outline-success"
                        data-bs-toggle="modal"
                        data-bs-target="#modalActualizar<?php echo e($c->id_cliente); ?>">
                        Actualizar
                    </button>
                    <?php echo $__env->make('Clientes.update', ['cliente' => $c], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="7" class="text-center">No hay clientes registrados.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('inputBuscar');
        const tabla = document.getElementById('tablaClientes').getElementsByTagName('tbody')[0];

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

<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\alex2\Escritorio\Repositorios Git\Prueba1\resources\views/Clientes/index.blade.php ENDPATH**/ ?>