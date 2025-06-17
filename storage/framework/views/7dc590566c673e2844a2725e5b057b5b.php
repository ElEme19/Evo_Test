

<?php $__env->startSection('title'); ?>

<?php $__env->startSection('conten-wrapper'); ?>
    <div class="text-center  mt-4 px-3">
        <h3>
            Sucursales 
            <span class="badge rounded-pill text-bg-success">Ver</span>
        </h3>
    </div>

    <div class="d-flex justify-content-center my-3">
        <form class="row g-3 justify-content-center" novalidate>
            <div class="col-md-12">
                <input
                    type="text"
                    id="inputBuscar"
                    class="form-control"
                    placeholder="Buscar por ID, nombre o localización..."
                >
            </div>
        </form>
    </div>

    <?php if(session('success')): ?>
        <div class="text-center">
            <div
                class="alert alert-warning d-inline-flex align-items-center py-1 px-2 rounded-3 shadow-sm"
                role="alert"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16" height="16"
                    fill="currentColor"
                    class="bi me-2"
                    viewBox="0 0 16 16"
                    role="img"
                    aria-label="Warning:"
                >
                    <path
                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96
                           0L.165 13.233c-.457.778.091 1.767.98
                           1.767h13.713c.889 0 1.438-.99.98-
                           1.767L8.982 1.566zM8 5c.535 0
                           .954.462.9.995l-.35 3.507a.552.552
                           0 0 1-1.1 0L7.1 5.995A.905.905 0
                           0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1
                           0 0 1 0-2z"
                    />
                </svg>
                <small class="fw-semibold">
                    <?php echo e(session('success')); ?>

                </small>
            </div>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="text-center">
            <div
                class="alert alert-danger d-inline-flex align-items-center py-1 px-2 rounded-3 shadow-sm"
                role="alert"
            >
                <strong><?php echo e(session('error')); ?></strong>
            </div>
        </div>
    <?php endif; ?>

    <div class="container mt-4">
        <table class="table table-bordered table-hover mt-5" id="tablaBicicletas">
             <thead class="table-light">
            <tr>
                <th scope="col"  class="text-center">ID Sucursal</th>
                <th scope="col"  class="text-center">Nombre</th>
                <th scope="col"  class="text-center">Localización</th>
                <th scope="col"  class="text-center">Fachada</th>
                <th scope="col"  class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $sucursales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sucursal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="text-center" ><?php echo e($sucursal->id_sucursal); ?></td>
                    <td class="text-center" ><?php echo e($sucursal->nombre_sucursal); ?></td>
                    <td class="text-center" ><?php echo e($sucursal->localizacion); ?></td>
                    <td class="text-center" >
                        <?php if($sucursal->foto_fachada): ?>
                                <a
                                   href="<?php echo e(route('sucursal.imagen', ['path' => $sucursal->foto_fachada])); ?>"
                                    target="_blank"
                                    class="text-decoration-none"
                                >
                                    Ver imagen
                                </a>

                        <?php else: ?>
                            <span class="text-muted">Sin foto</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center" >
                        <button
                            type="button"
                            class="btn btn-outline-success"
                            data-bs-toggle="modal"
                            data-bs-target="#modalActualizar<?php echo e($sucursal->id_sucursal); ?>"
                        >
                            Actualizar
                        </button>

                        
                        
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    </div> 

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const input = document.getElementById('inputBuscar');
            const tabla = document
                .getElementById('tablaSucursales')
                .getElementsByTagName('tbody')[0];

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

<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\alex2\Escritorio\Repositorios Git\Prueba1\resources\views/Sucursal/vista.blade.php ENDPATH**/ ?>