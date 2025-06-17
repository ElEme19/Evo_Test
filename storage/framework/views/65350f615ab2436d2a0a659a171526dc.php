
<?php $__env->startSection('fondo'); ?>

<form method="POST" action="<?php echo e(route('registrar')); ?>">
    <?php echo csrf_field(); ?>

                    <div class="d-flex align-items-center mb-5 pb-3">
                    <img src="<?php echo e(asset('images/logo.webp')); ?>" alt="Logo" style="max-width: 350px; max-height: 130px; width: 100%;">

                    </div>
                    <?php if($errors->any()): ?>
              <div class="alert alert-danger mt-1">
                <ul class="mb-0">
                  <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
              </div>
              <?php endif; ?>


                  <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px; ">Registro</h5>

                  <div class="form-outline mb-4">
                    <input type="text" name="user_name" id="form2Example17" class="form-control form-control-lg"  placeholder="user@evobike.com" />
                        <label class="form-label" for="form2Example17">Correo electrónico</label>
                            </div>

                                <div class="form-outline mb-4">
                            <input type="password" name="user_pass" id="form2Example27" class="form-control form-control-lg" placeholder="*********" />
                                <label class="form-label" for="form2Example27">Contraseña</label>
                                        </div>

                                    <div class="form-outline mb-4">
                                     <label class="form-label" for="user_tipo" required>Tipo</label>
                                     <input type="number" id="user_tipo" name="user_tipo" class="form-control" required>
                                    </div>

                                </div>
                        

                  <div class="pt-1 mb-4">
                  <button data-mdb-button-init data-mdb-ripple-init class="btn btn-lg btn-block" 
                        type="submit" style="background-color: #4DB53F; border-color:rgb(255, 255, 255);">
                         registrar
                        </button>

                  </div>

                </form>

                


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout/app2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\alex2\Escritorio\Repositorios Git\Prueba1\resources\views/piezas/registrarse.blade.php ENDPATH**/ ?>