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
        @if (session('success'))
          <div class="text-center">
            <div class="alert alert-success d-inline-flex align-items-center py-1 px-2 rounded-3" role="alert">
              <i class="bi bi-check-circle me-2"></i>
              <small class="fw-semibold">{{ session('success') }}</small>
            </div>
          </div>
        @endif
        @if (session('error'))
          <div class="text-center">
            <div class="alert alert-danger d-inline-flex align-items-center py-1 px-2 rounded-3" role="alert">
              <i class="bi bi-exclamation-triangle me-2"></i>
              <small class="fw-semibold">{{ session('error') }}</small>
            </div>
          </div>
        @endif
      </div>

      <!-- Formulario Cotización -->
      <div class="bg-white p-3 rounded-3 border">
        <div class="row g-3 mb-3">
          <div class="col-12 col-md-3">
            <label for="id_membresia" class="form-label fw-semibold">Membresía</label>
            <select id="id_membresia" class="form-select form-select-sm" required>
              <option value="" disabled selected>Seleccione membresía</option>
              @foreach($membresias as $m)
                <option value="{{ $m->id_membresia }}">{{ $m->descripcion_general }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-12 col-md-3">
            <label for="id_modelo" class="form-label fw-semibold">Modelo</label>
            <select id="id_modelo" class="form-select form-select-sm" required>
              <option value="" disabled selected>Seleccione modelo</option>
              @foreach($modelos as $mod)
                <option value="{{ $mod->id_modelo }}">{{ $mod->nombre_modelo }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-12 col-md-3">
            <label for="id_color" class="form-label fw-semibold">Color</label>
            <select id="id_color" class="form-select form-select-sm" required>
              <option value="" disabled selected>Seleccione color</option>
            </select>
          </div>
          <div class="col-12 col-md-3">
            <label for="id_voltaje" class="form-label fw-semibold">Voltaje</label>
            <select id="id_voltaje" class="form-select form-select-sm" required>
              <option value="" disabled selected>Seleccione voltaje</option>
            </select>
          </div>
        </div>
        <div class="text-end mb-3">
          <button type="button" id="btnAgregarLinea" class="btn btn-outline-success btn-rounded" disabled>
            <i class="bi bi-plus-lg me-1"></i>Agregar Bici
          </button>
        </div>

        <!-- Tabla Cotización -->
        <div class="table-responsive">
          <table class="table table-sm table-fixed align-middle" id="tablaCotizacion">
            <thead class="table-light text-center small">
                <tr>
                <th style="width:5%">#</th>
                <th style="width:18%">Membresía</th>
                <th style="width:18%">Modelo</th>
                <th style="width:14%">Color</th>
                <th style="width:14%">Voltaje</th>
                <th style="width:14%">Precio Unitario</th>
                <th style="width:12%">Cantidad</th>  <!-- Nueva columna -->
                <th style="width:14%">Subtotal</th>
                <th style="width:5%">Acción</th>
                </tr>
            </thead>
            <tbody></tbody>
            <tfoot></tfoot>
            </table>

           
            <form id="formPDF" method="POST" action="{{ route('cotizacion.pdf') }}" target="_blank" class="mt-3">
                @csrf
                <input type="hidden" name="membresia" id="pdf_membresia">
                <input type="hidden" name="lineas" id="pdf_lineas">
                <button type="submit" class="btn btn-success mt-3" id="btnGenerarPDF" disabled><i class="bi bi-file-earmark-pdf me-1"></i>Generar PDF</button>
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
      if (!colRes.ok || !volRes.ok) throw new Error('Error al obtener datos');
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
  const params = new URLSearchParams({ id_membresia: idM, id_modelo: idMo, id_colorM: idC, id_voltaje: idV });

  try {
    const res = await fetch(`/cotizacion/precio?${params}`);
    if (!res.ok) throw new Error('Error al obtener precio');
    const ju = await res.json();
    if (!ju.success) throw new Error(ju.message || 'Precio no encontrado');
    const precioNum = parseFloat(ju.precio);

    // Verificar si ya existe la bici con mismas características
    const lineaExistente = lineas.find(l =>
      l.idM === idM &&
      l.idMo === idMo &&
      l.idC === idC &&
      l.idV === idV
    );

    if(lineaExistente) {
      // Aumentar cantidad y mostrar modal
      lineaExistente.cantidad += 1;
      renderizarTabla();

      // Mostrar modal informativo
      document.getElementById('infoModalBody').textContent = 'Esta bicicleta ya está en la cotización. Se aumentó la cantidad en 1.';
      new bootstrap.Modal(document.getElementById('infoModal')).show();
    } else {
      // Agregar nueva línea
      lineas.push({ idM, idMo, idC, idV, precio: precioNum, cantidad: 1 });
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

  window.cambiarCantidad = function(i, incremento) {
    const linea = lineas[i];
    if (!linea) return;

    const nuevaCantidad = linea.cantidad + incremento;
    if (nuevaCantidad < 1) return; // mínimo 1

    linea.cantidad = nuevaCantidad;
    renderizarTabla();
  };

  function renderizarTabla() {
    tbody.innerHTML = '';
    let total = 0;
    lineas.forEach((l, i) => {
      const memb = selMembresia.querySelector(`option[value="${l.idM}"]`).text;
      const mod  = selModelo   .querySelector(`option[value="${l.idMo}"]`).text;
      const col  = selColor    .querySelector(`option[value="${l.idC}"]`).text;
      const vol  = selVoltaje  .querySelector(`option[value="${l.idV}"]`).text;
      const sub  = l.precio * l.cantidad;
      total += sub;

      tbody.innerHTML += `
        <tr class="text-center small">
          <td>${i+1}</td>
          <td >${memb}</td>
          <td>${mod}</td>
          <td>${col}</td>
          <td>${vol}</td>
          <td>${formatPesos(l.precio)}</td>
          <td class="text-center">
            <button class="btn btn-sm btn-outline-danger me-1" onclick="cambiarCantidad(${i}, -1)">-</button>
            ${l.cantidad}
            <button class="btn btn-sm btn-outline-success ms-1" onclick="cambiarCantidad(${i}, 1)">+</button>
          </td>
          <td >${formatPesos(sub)}</td>
          <td>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="quitarLinea(${i})">
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

    const totalCantidad = lineas.reduce((acc, l) => acc + l.cantidad, 0);
    btnPDF.disabled = totalCantidad < 5;

  }

  formPDF.addEventListener('submit', () => {
    inputMember.value = selMembresia.options[selMembresia.selectedIndex].text;
    inputLines.value = JSON.stringify(
      lineas.map(l => ({
        membresia: selMembresia.options[selMembresia.selectedIndex].text,
        modelo:    selModelo.options[selModelo.selectedIndex].text,
        color:     selColor.options[selColor.selectedIndex].text,
        voltaje:   selVoltaje.options[selVoltaje.selectedIndex].text,
        precio:    l.precio,
        cantidad:  l.cantidad
      }))
    );
  });
});
</script>

@endsection
