

<?php $__env->startSection('conten'); ?>

<div class="text-center my-4">
    <h3>
        Cliente
        <span class="badge rounded-pill text-bg-success">Nuevo</span>
    </h3>
</div>

<?php if(session('success')): ?>
    <div class="text-center">
        <div class="alert alert-success d-inline-flex align-items-center py-1 px-2 rounded-3 shadow-sm" role="alert">
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

<form class="row g-3 was-validated" method="POST" action="<?php echo e(route('Clientes.store')); ?>">
    <?php echo csrf_field(); ?>

    <div class="col-md-4">
        <label for="id_cliente" class="form-label">ID Cliente (Generado)</label>
        <input type="text" class="form-control" value="<?php echo e($nextId); ?>" readonly>
    </div>

    <div class="mb-3">
    <label for="id_membresia" class="form-label">Seleccionar Membresía</label>
    <select name="id_membresia" id="id_membresia" class="form-select" required>
        <option value="" disabled selected>Seleccione una membresía</option>
        <?php $__currentLoopData = $membresias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($m->id_membresia); ?>"><?php echo e($m->descripcion_general); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div>


    <div class="col-md-4">
        <label for="nombre" class="form-label">Nombre</label>
        <input type="text" class="form-control is-valid" id="nombre" name="nombre" placeholder="Ej: Juan" required>
        <div class="invalid-feedback">Ingrese un nombre válido.</div>
    </div>

    <div class="col-md-4">
        <label for="apellido" class="form-label">Apellido</label>
        <input type="text" class="form-control is-valid" id="apellido" name="apellido" placeholder="Ej: Pérez" required>
        <div class="invalid-feedback">Ingrese un apellido válido.</div>
    </div>

    <div class="col-md-6">
        <label for="correo" class="form-label">Correo Electrónico</label>
        <input type="email" class="form-control is-valid" id="correo" name="correo" placeholder="correo@ejemplo.com" required>
        <div class="invalid-feedback">Ingrese un correo válido.</div>
    </div>

    <div class="col-md-6">
        <label for="telefono" class="form-label">Teléfono</label>
        <input type="text" class="form-control is-valid" id="telefono" name="telefono" placeholder="Ej: 5551234567" required>
        <div class="invalid-feedback">Ingrese un teléfono válido.</div>
    </div>

    <div class="row mb-3 align-items-center mt-4">
        <div class="col text-start">
            <button class="btn btn-outline-success" type="submit">
                Guardar Cliente
            </button>
        </div>
        <div class="col text-end">
            <a href="<?php echo e(route('Clientes.index')); ?>" class="btn btn-outline-success">
                Ver Clientes
            </a>
        </div>
    </div>
</form>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\alex2\Escritorio\Repositorios Git\Prueba1\resources\views/Clientes/create.blade.php ENDPATH**/ ?>