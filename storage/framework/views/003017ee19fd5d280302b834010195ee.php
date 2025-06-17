<div class="modal fade" id="modalActualizar<?php echo e($precio->id_precio); ?>" tabindex="-1" aria-labelledby="modalLabel<?php echo e($precio->id_precio); ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border border-success">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalLabel<?php echo e($precio->id_precio); ?>">
                    Actualizar Precio: #<?php echo e($precio->id_precio); ?>

                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <form action="<?php echo e(route('Precio.update', $precio->id_precio)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="id_membresia_<?php echo e($precio->id_precio); ?>" class="form-label">Tipo de Membresía</label>
                        <select name="id_membresia" id="id_membresia<?php echo e($precio->id_precio); ?>" class="form-select" required>
                            <option value="" disabled>-- Seleccionar membresía --</option>
                            <?php $__currentLoopData = $membresias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $membresia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($membresia->id_membresia); ?>"
                                    <?php echo e($precio->id_membresia == $membresia->id_membresia ? 'selected' : ''); ?>>
                                    <?php echo e($membresia->descripcion_general); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="id_modelo_<?php echo e($precio->id_precio); ?>" class="form-label">Modelo de Bicicleta</label>
                        <select name="id_modelo" id="id_modelo_<?php echo e($precio->id_precio); ?>" class="form-select" required>
                            <option value="" disabled>-- Seleccionar modelo --</option>
                            <?php $__currentLoopData = $modelos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modelo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($modelo->id_modelo); ?>"
                                    <?php echo e($precio->id_modelo == $modelo->id_modelo ? 'selected' : ''); ?>>
                                    <?php echo e($modelo->nombre_modelo); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="precio_<?php echo e($precio->id_precio); ?>" class="form-label">Precio ($ MXN)</label>
                        <input type="number" name="precio" id="precio_<?php echo e($precio->id_precio); ?>" class="form-control" value="<?php echo e($precio->precio); ?>" step="0.01" min="0" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\alex2\Escritorio\Repositorios Git\Prueba1\resources\views/Precio/update.blade.php ENDPATH**/ ?>