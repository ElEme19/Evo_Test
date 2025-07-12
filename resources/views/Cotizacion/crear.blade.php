@extends('layout.app')

@section('conten-wrapper')
<div class="container-fluid px-3 px-md-3 py-3">
  <div class="row justify-content-center">
    <div class="col-12">
      <style>
        .btn-rounded { border-radius: 50px; padding: 0.5rem 1.25rem; }
        .table-fixed { table-layout: fixed; width: 100%; }
      </style>

      <!-- Encabezado -->
      <header class="text-center mb-4">
        <h1 class="h3 mb-0 text-success fw-bold">
          <i class="bi bi-receipt me-2"></i>Crear Cotización
        </h1>
      </header>

      <!-- Alertas -->
      <div class="alert-container mb-4">
        {{-- ... tus alertas ... --}}
      </div>

      <!-- Formulario Cotización -->
      <form id="formPDF" method="POST" action="{{ route('cotizacion.pdf') }}" target="_blank" class="bg-white p-3 rounded-3 border">
        @csrf

        <!-- Selección de Membresía/Modelo/Color/Voltaje -->
        <div class="row g-3 mb-3">
          <div class="col-md-3">
            <label for="id_membresia" class="form-label fw-semibold">Membresía</label>
            <select id="id_membresia" class="form-select form-select-sm" required>
              <option value="" disabled selected>Seleccione membresía</option>
              @foreach($membresias as $m)
                <option value="{{ $m->id_membresia }}">{{ $m->descripcion_general }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <label for="id_modelo" class="form-label fw-semibold">Modelo</label>
            <select id="id_modelo" class="form-select form-select-sm" required>
              <option value="" disabled selected>Seleccione modelo</option>
              @foreach($modelos as $mod)
                <option value="{{ $mod->id_modelo }}">{{ $mod->nombre_modelo }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <label for="id_color" class="form-label fw-semibold">Color</label>
            <select id="id_color" class="form-select form-select-sm" required>
              <option value="" disabled selected>Seleccione color</option>
            </select>
          </div>
          <div class="col-md-3">
            <label for="id_voltaje" class="form-label fw-semibold">Voltaje</label>
            <select id="id_voltaje" class="form-select form-select-sm" required>
              <option value="" disabled selected>Seleccione voltaje</option>
            </select>
          </div>
        </div>

        <!-- Datos del Cliente -->
        <div class="row g-3 mb-3">
          <div class="col-md-3">
            <label for="nombre_cliente" class="form-label fw-semibold">Nombre del Cliente</label>
            <input type="text" name="nombre_cliente" id="nombre_cliente" class="form-control form-control-sm" required>
          </div>
          <div class="col-md-3">
            <label for="telefono" class="form-label fw-semibold">Teléfono</label>
            <input type="text" name="telefono" id="telefono" class="form-control form-control-sm" required>
          </div>
          <div class="col-md-3">
            <label for="direccion" class="form-label fw-semibold">Dirección</label>
            <input type="text" name="direccion" id="direccion" class="form-control form-control-sm" required>
          </div>
          <div class="col-md-3">
            <label for="metodo_entrega" class="form-label fw-semibold">Método de Entrega</label>
            <select name="metodo_entrega" id="metodo_entrega" class="form-select form-select-sm" required>
              <option value="" disabled selected>Seleccione</option>
              <option value="Sucursal">Sucursal</option>
              <option value="Domicilio">Domicilio</option>
              <option value="Paquetería">Paquetería</option>
            </select>
          </div>
        </div>

        <!-- Botón Agregar Línea -->
        <div class="text-end mb-3">
          <button type="button" id="btnAgregarLinea" class="btn btn-outline-success btn-rounded" disabled>
            <i class="bi bi-plus-lg me-1"></i>Agregar Bici
          </button>
        </div>

        <!-- Tabla de Cotización -->
        <div class="table-responsive mb-3">
          <table class="table table-sm table-fixed align-middle" id="tablaCotizacion">
            <thead class="table-light text-center small">
              <tr>
                <th>#</th>
                <th>Membresía</th>
                <th>Modelo</th>
                <th>Color</th>
                <th>Voltaje</th>
                <th>Precio Unitario</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody></tbody>
            <tfoot></tfoot>
          </table>
        </div>

        <!-- Hidden inputs para JS -->
        <input type="hidden" name="membresia" id="pdf_membresia">
        <input type="hidden" name="lineas"     id="pdf_lineas">

        <!-- Botón Generar PDF -->
        <button type="submit" class="btn btn-success" id="btnGenerarPDF" disabled>
          <i class="bi bi-file-earmark-pdf me-1"></i>Generar PDF
        </button>
      </form>

        </div>
      </div>

      <!-- Modales de Info y Error -->
      <div class="modal fade" id="infoModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content rounded-3">
            <div class="modal-body text-center p-4">
              <i class="bi bi-info-circle text-success fs-2 mb-2"></i>
              <p id="infoModalBody" class="mb-0 small text-muted"></p>
              <button type="button" class="btn btn-sm btn-outline-success mt-3" data-bs-dismiss="modal">Aceptar</button>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="errorModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content rounded-3">
            <div class="modal-body text-center p-4">
              <i class="bi bi-exclamation-triangle text-danger fs-2 mb-2"></i>
              <p id="errorModalBody" class="mb-0 small text-muted"></p>
              <button type="button" class="btn btn-sm btn-outline-danger mt-3" data-bs-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
function formatPesos(amount) {
  return '$ ' + amount.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

document.addEventListener('DOMContentLoaded', () => {
  const selMembresia = document.getElementById('id_membresia');
  const selModelo    = document.getElementById('id_modelo');
  const selColor     = document.getElementById('id_color');
  const selVoltaje   = document.getElementById('id_voltaje');
  const selMetodo    = document.getElementById('metodo_entrega');
  const btnAgregar   = document.getElementById('btnAgregarLinea');
  const btnPDF       = document.getElementById('btnGenerarPDF');
  const formPDF      = document.getElementById('formPDF');
  const inputMember  = document.getElementById('pdf_membresia');
  const inputLines   = document.getElementById('pdf_lineas');
  const tbody        = document.querySelector('#tablaCotizacion tbody');
  const tfoot        = document.querySelector('#tablaCotizacion tfoot');

  let lineas = [];

  function habilitarBtn() {
    btnAgregar.disabled = !(selMembresia.value && selModelo.value && selColor.value && selVoltaje.value);
  }

  selMembresia.addEventListener('change', habilitarBtn);
  selModelo.addEventListener('change', async () => {
    selColor.innerHTML   = '<option disabled>…Cargando…</option>';
    selVoltaje.innerHTML = '<option disabled>…Cargando…</option>';
    try {
      const [colRes, volRes] = await Promise.all([
        fetch(`/cotizacion/colores/${selModelo.value}`),
        fetch(`/cotizacion/voltajes/${selModelo.value}`)
      ]);
      const [colores, voltajes] = await Promise.all([colRes.json(), volRes.json()]);

      selColor.innerHTML = '<option disabled selected>Seleccione color</option>';
      colores.forEach(c => {
        const opt = document.createElement('option');
        opt.value = c.id_colorM;
        opt.textContent = c.nombre_color;
        selColor.appendChild(opt);
      });

      selVoltaje.innerHTML = '<option disabled selected>Seleccione voltaje</option>';
      voltajes.forEach(v => {
        const opt = document.createElement('option');
        opt.value = v.id_voltaje;
        opt.textContent = v.tipo_voltaje;
        selVoltaje.appendChild(opt);
      });
    } catch (err) {
      selColor.innerHTML = '<option disabled>Error</option>';
      selVoltaje.innerHTML = '<option disabled>Error</option>';
      console.error(err);
    }
    habilitarBtn();
  });
  selColor.addEventListener('change', habilitarBtn);
  selVoltaje.addEventListener('change', habilitarBtn);

  btnAgregar.addEventListener('click', async (e) => {
    e.preventDefault();
    const idM  = selMembresia.value;
    const idMo = selModelo.value;
    const idC  = selColor.value;
    const idV  = selVoltaje.value;

    try {
      const res = await fetch(`/cotizacion/precio?${new URLSearchParams({
        id_membresia: idM,
        id_modelo:    idMo,
        id_colorM:    idC,
        id_voltaje:   idV
      })}`);
      const ju = await res.json();
      const precioNum = parseFloat(ju.precio);

      // Extraer textos actuales
      const membText = selMembresia.options[selMembresia.selectedIndex].text;
      const modText  = selModelo   .options[selModelo.selectedIndex].text;
      const colText  = selColor    .options[selColor.selectedIndex].text;
      const volText  = selVoltaje  .options[selVoltaje.selectedIndex].text;

      // Verificar duplicado
      const lineaExistente = lineas.find(l =>
        l.idM === idM && l.idMo === idMo && l.idC === idC && l.idV === idV
      );

      if (lineaExistente) {
        // Aumentar cantidad
        lineaExistente.cantidad += 1;
        renderizarTabla();
        // Mostrar modal informativo al duplicar
        document.getElementById('infoModalBody').textContent = 'Esta bicicleta ya está en la cotización. Se aumentó la cantidad en 1.';
        new bootstrap.Modal(document.getElementById('infoModal')).show();
      } else {
        // Agregar nueva línea con textos fijos
        lineas.push({
          idM, idMo, idC, idV,
          precio: precioNum,
          cantidad: 1,
          membresiaText: membText,
          modeloText:    modText,
          colorText:     colText,
          voltajeText:   volText
        });
        renderizarTabla();
      }
    } catch (err) {
      document.getElementById('errorModalBody').textContent = err.message;
      new bootstrap.Modal(document.getElementById('errorModal')).show();
    }
  });

  window.quitarLinea = function(i) {
    lineas.splice(i, 1);
    renderizarTabla();
  };

  window.cambiarCantidad = function(i, inc) {
    const linea = lineas[i];
    if (!linea) return;
    const nueva = linea.cantidad + inc;
    if (nueva < 1) return;
    linea.cantidad = nueva;
    renderizarTabla();
  };

  function renderizarTabla() {
    tbody.innerHTML = '';
    let total = 0;
    lineas.forEach((l, i) => {
      const sub = l.precio * l.cantidad;
      total += sub;
      tbody.innerHTML += `
        <tr class="text-center small">
          <td>${i+1}</td>
          <td>${l.membresiaText}</td>
          <td>${l.modeloText}</td>
          <td>${l.colorText}</td>
          <td>${l.voltajeText}</td>
          <td>${formatPesos(l.precio)}</td>
          <td>
            <button class="btn btn-sm btn-outline-danger" onclick="cambiarCantidad(${i}, -1)">-</button>
            ${l.cantidad}
            <button class="btn btn-sm btn-outline-success" onclick="cambiarCantidad(${i}, 1)">+</button>
          </td>
          <td>${formatPesos(sub)}</td>
          <td>
            <button class="btn btn-sm btn-outline-danger" onclick="quitarLinea(${i})">
              <i class="bi bi-trash"></i>
            </button>
          </td>
        </tr>`;
    });

    tfoot.innerHTML = `
      <tr class="small">
        <th colspan="7" class="text-end">Total</th>
        <th class="text-end">${formatPesos(total)}</th>
        <th></th>
      </tr>`;
    btnPDF.disabled = lineas.reduce((sum, l) => sum + l.cantidad, 0) < 5;
  }

  formPDF.addEventListener('submit', () => {
    inputMember.value = selMembresia.options[selMembresia.selectedIndex].text;
    inputLines.value = JSON.stringify(lineas.map(l => ({
      membresia:      l.membresiaText,
      modelo:         l.modeloText,
      color:          l.colorText,
      voltaje:        l.voltajeText,
      precio:         l.precio,
      cantidad:       l.cantidad,
      metodo_entrega: selMetodo.value
    })));
  });
});
</script>



@endsection
