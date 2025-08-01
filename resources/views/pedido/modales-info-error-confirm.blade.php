<!-- Confirmación -->
<div class="modal fade" id="confirmModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0">
      <div class="modal-header bg-light">
        <h5 class="modal-title fw-bold">Confirmar Bicicleta</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="confirmModalBody"></div>
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-outline-success" id="confirmAddBtn">Confirmar</button>
      </div>
    </div>
  </div>
</div>

<!-- Info -->
<div class="modal fade" id="infoModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 text-center">
      <div class="modal-body p-4">
        <i class="bi bi-info-circle fs-2 text-primary mb-3"></i>
        <p id="infoModalBody" class="mb-0"></p>
        <button class="btn btn-sm btn-outline-primary mt-3" data-bs-dismiss="modal">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<!-- Error -->
<div class="modal fade" id="errorModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 text-center">
      <div class="modal-body p-4">
        <i class="bi bi-x-circle fs-2 text-danger mb-3"></i>
        <p id="errorModalBody" class="mb-0"></p>
        <button class="btn btn-sm btn-outline-danger mt-3" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
