

hola

<?php $__env->startSection('conten'); ?>

<div class="text-center my-4">
    <h3>
        Membresía
        <span class="badge rounded-pill text-bg-success">Nueva</span>
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
            <small class="fw-semibold">  <!-- Para la alerta -->
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

<form class="row g-3 was-validated" method="POST" action="<?php echo e(route('Membresia.store')); ?>">
    <?php echo csrf_field(); ?>
<div class="col-md-4">
    <label for="id_membresia" class="form-label">ID Membresía (Generado)</label>
    <input type="text" class="form-control" value="<?php echo e($nextId); ?>" readonly>
</div>


    <div class="col-md-4">
        <label for="descripcion_general" class="form-label">Descripcion General</label>
        <input type="text" class="form-control is-valid" id="descripcion_general" name="descripcion_general" placeholder="Ej: Premium" required>
        <div class="invalid-feedback">Ingrese un nombre válido.</div>
    </div>


    <div class="row mb-3 align-items-center mt-4">
        <div class="col text-start">
            <button class="btn btn-outline-success" type="submit">
                Guardar Membresía
            </button>
        </div>
        <div class="col text-end">
            <a href="<?php echo e(route('Membresia.index')); ?>" class="btn btn-outline-success">
                Ver Membresías
            </a>
        </div>
    </div>
</form>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\alex2\Escritorio\Repositorios Git\Prueba1\resources\views/Membresia/create.blade.php ENDPATH**/ ?>