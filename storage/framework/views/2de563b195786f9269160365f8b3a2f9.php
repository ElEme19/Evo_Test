

<?php $__env->startSection('title'); ?>

<?php $__env->startSection('conten'); ?>

<div class="text-center my-4">
    <h3>
        Crear Bicicleta
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


<form method="POST" action="<?php echo e(route('Bicicleta.store')); ?>" class="row g-3 was-validated">
    <?php echo csrf_field(); ?>

    <div class="col-md-6">
        <label for="num_chasis" class="form-label">Número de Chasis</label>
        <input type="text" name="num_chasis" class="form-control" required>
    </div>

    <div class="col-md-6">
        <label for="num_motor" class="form-label">Número de Motor</label>
        <input type="text" name="num_motor" class="form-control">
    </div>

    <div class="col-md-6">
        <label for="id_modelo" class="form-label">Modelo</label>
        <select name="id_modelo" id="id_modelo" class="form-select" required>
            <option value="">Seleccione un modelo</option>
            <?php $__currentLoopData = $modelos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modelo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($modelo->id_modelo); ?>"><?php echo e($modelo->nombre_modelo); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    <div class="col-md-6">
        <label for="id_color" class="form-label">Color</label>
        <select name="id_color" id="id_color" class="form-select" required>
            <option value="">Seleccione un modelo primero</option>
        </select>
    </div>

    <div class="col-md-4">
        <label for="id_lote" class="form-label">Lote</label>
        <select name="id_lote" class="form-select" required>
            <?php $__currentLoopData = $lotes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lote): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($lote->id_lote); ?>"><?php echo e($lote->fecha_produccion); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    <div class="col-md-4">
        <label for="id_tipoStock" class="form-label">Tipo de Stock</label>
        <select name="id_tipoStock" class="form-select" required>
            <?php $__currentLoopData = $tipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($tipo->id_tipoStock); ?>"><?php echo e($tipo->nombre_stock); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    <div class="col-md-4">
        <label for="voltaje" class="form-label">Voltaje</label>
        <input type="text" name="voltaje" class="form-control">
    </div>

    <div class="col-md-6">
        <label for="error_iden_produccion" class="form-label">Error Identificación Producción</label>
        <input type="text" name="error_iden_produccion" class="form-control">
    </div>


    <div class="col-12 mt-3 text-center">
        <button type="submit" class="btn btn-outline-success">Guardar Bicicleta</button>
    </div>
     <div class="col text-end">
            <a href="<?php echo e(route('Bicicleta.ver')); ?>" class="btn btn-outline-success">
                Ver Bicis
            </a>
        </div>
</form>


<script>
    document.getElementById('id_modelo').addEventListener('change', function () {
        const modeloId = this.value;
        const colorSelect = document.getElementById('id_color');

        colorSelect.innerHTML = '<option value="">Cargando...</option>';

        fetch(`/colores-por-modelo/${modeloId}`)
            .then(res => res.json())
            .then(colores => {
                colorSelect.innerHTML = '<option value="">Seleccione un color</option>';
                colores.forEach(color => {
                    const opt = document.createElement('option');
                    opt.value = color.id_colorM;
                    opt.textContent = color.nombre_color;
                    colorSelect.appendChild(opt);
                });
            })
            .catch(() => {
                colorSelect.innerHTML = '<option value="">Error al cargar colores</option>';
            });
    });
</script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\alex2\Escritorio\Repositorios Git\Prueba1\resources\views/bicicleta/crear.blade.php ENDPATH**/ ?>