@extends('layout.app')

@section('conten-wrapper')
<div class="container-fluid px-3 px-md-3 py-3">
  <div class="row justify-content-center">
    <div class="col-12">
      <style>
        .btn-rounded { border-radius: 50px; padding: 0.5rem 1.25rem; }
        /* Evitar table-layout fixed para mejor adaptabilidad */
        /* .table-fixed {
          table-layout: fixed;
          width: 100%;
        } */
        /* Permitir quiebre de palabra para celdas */
        table th, table td {
          white-space: normal !important;
          word-wrap: break-word;
          vertical-align: middle !important;
        }
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

        <!-- Datos del Cliente -->
        <div class="row g-3 mb-3">
          <div class="col-12 col-md-3">
            <label for="nombre_cliente" class="form-label fw-semibold">Nombre del Cliente</label>
            <input type="text" name="nombre_cliente" id="nombre_cliente" class="form-control form-control-sm" required>
          </div>
          <div class="col-12 col-md-3">
            <label for="telefono" class="form-label fw-semibold">Teléfono</label>
            <input type="text" name="telefono" id="telefono" class="form-control form-control-sm" required>
          </div>
          <div class="col-12 col-md-3">
            <label for="direccion" class="form-label fw-semibold">Dirección</label>
            <!-- Este es el que el usuario llena manualmente -->
            <input type="text" name="direccion" id="direccion" class="form-control form-control-sm" disabled>
            <!-- Y este guardará la dirección que vengas de Maps -->
            <input type="hidden" name="direccion_destino" id="direccion_destino">
          </div>
          <div class="col-12 col-md-3">
            <label for="metodo_entrega" class="form-label fw-semibold">Método de Entrega</label>
            <select name="metodo_entrega" id="metodo_entrega" class="form-select form-select-sm" required>
              <option value="" disabled selected>Seleccione</option>
              <option value="Sucursal">Sucursal</option>
              <option value="Domicilio">Domicilio</option>
              <option value="Paquetería">Paquetería</option>
            </select>
          </div>
        

        <div class="col-12 col-md-6">
  <label for="almacen" class="form-label fw-semibold">Seleccione Almacén</label>
  <select name="almacen" id="almacen" class="form-select form-select-sm" required>
    <option value="" disabled selected>Seleccione un almacén</option>
    <option value="fabricaixta">Fabrica Ixtapaluca</option>
    <option value="oficinascentrales">Oficinas Centrales</option>
    <!-- agrega más almacenes si quieres -->
  </select>
</div>
 </div>

        <!-- Link de Google Maps y distancia -->
<div class="row g-3 mb-3">
  <div class="col-12 col-md-6">
    <label for="maps_url" class="form-label fw-semibold">Link de ubicación (Google Maps)</label>
    <input type="url"
           name="maps_url"
           id="maps_url"
           class="form-control form-control-sm"
           placeholder="https://www.google.com/maps/place/…"
           disabled
           required>
  </div>
  <div class="col-12 col-md-6 d-flex align-items-end">
    <p class="mb-0 small">
      Distancia estimada: <strong><span id="distanciaText">—</span> km</strong>
    </p>
  </div>
</div>

<!-- Guarda el valor real para el PDF -->
<input type="hidden" name="distancia_km" id="distancia_km">


        <!-- Botón Agregar Línea -->
        <div class="text-end mb-3">
          <button type="button" id="btnAgregarLinea" class="btn btn-outline-success btn-rounded" disabled>
            <i class="bi bi-plus-lg me-1"></i>Agregar Bici
          </button>
        </div>

        <!-- Tabla de Cotización -->
        <div class="table-responsive mb-3">
          <table class="table table-sm align-middle" id="tablaCotizacion">
            <thead class="table-light text-center small">
              <tr>
                <th>#</th>
                <th>Membresía</th>
                <th>Modelo</th>
                <th class="d-none d-md-table-cell">Color</th>
                <th class="d-none d-md-table-cell">Voltaje</th>
                <th class="d-none d-lg-table-cell">Precio Unitario</th>
                <th>Cantidad</th>
                <th class="d-none d-lg-table-cell">Subtotal</th>
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

// Extraer coords de Google Maps URLs
function extraerCoordsGoogle(url) {
  let m = url.match(/3d([-0-9.]+)!4d([-0-9.]+)/);
  if (m) return { lat: m[1], lng: m[2] };
  m = url.match(/@([-0-9.]+),([-0-9.]+)/);
  return m ? { lat: m[1], lng: m[2] } : null;
}

document.addEventListener('DOMContentLoaded', () => {
  const selAlmacen   = document.getElementById('almacen'); // <select> almacén
  const selMembresia = document.getElementById('id_membresia');
  const selModelo    = document.getElementById('id_modelo');
  const selColor     = document.getElementById('id_color');
  const selVoltaje   = document.getElementById('id_voltaje');
  const selMetodo    = document.getElementById('metodo_entrega');
  const mapsInput    = document.getElementById('maps_url');
  const distanciaTextEl = document.getElementById('distanciaText');
  const distanciaInput  = document.getElementById('distancia_km');
  const direccionDestinoInput = document.getElementById('direccion_destino'); // hidden input in form
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

  // Deshabilitar input de link al inicio
  mapsInput.disabled = true;

  // Cuando cambia el almacen habilitar o deshabilitar el input maps_url
  selAlmacen.addEventListener('change', () => {
    if (selAlmacen.value) {
      mapsInput.disabled = false;
    } else {
      mapsInput.value = '';
      mapsInput.disabled = true;
      distanciaTextEl.textContent = '—';
      distanciaInput.value = '';
      direccionDestinoInput.value = '';
    }
  });

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
    } catch {
      selColor.innerHTML = '<option disabled>Error</option>';
      selVoltaje.innerHTML = '<option disabled>Error</option>';
    }
    habilitarBtn();
  });
  selColor.addEventListener('change', habilitarBtn);
  selVoltaje.addEventListener('change', habilitarBtn);

  // Evento: cuando cambia el link de Maps, pide distancia y la dirección
  mapsInput.addEventListener('change', async () => {
    const coords = extraerCoordsGoogle(mapsInput.value.trim());
    if (!coords) {
      distanciaTextEl.textContent = 'URL inválida';
      distanciaInput.value = '';
      direccionDestinoInput.value = '';
      return;
    }

    if (!selAlmacen.value) {
      distanciaTextEl.textContent = 'Seleccione un almacén primero';
      distanciaInput.value = '';
      direccionDestinoInput.value = '';
      return;
    }

    try {
      const res = await fetch("{{ route('cotizacion.distancia') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
          lat: coords.lat,
          lng: coords.lng,
          almacen: selAlmacen.value
        })
      });
      const body = await res.json();
      if (res.ok && body.km != null) {
        distanciaTextEl.textContent = body.km;
        distanciaInput.value = body.km;

        // aquí guardamos la dirección legible en el hidden correcto
        direccionDestinoInput.value = body.direccion || '';
      } else {
        distanciaTextEl.textContent = 'N/D';
        distanciaInput.value = '';
        direccionDestinoInput.value = '';
      }
    } catch {
      distanciaTextEl.textContent = 'Error';
      distanciaInput.value = '';
      direccionDestinoInput.value = '';
    }
  });

  // Agregar línea de cotización
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
      const membText = selMembresia.options[selMembresia.selectedIndex].text;
      const modText  = selModelo.options[selModelo.selectedIndex].text;
      const colText  = selColor.options[selColor.selectedIndex].text;
      const volText  = selVoltaje.options[selVoltaje.selectedIndex].text;

      const lineaExistente = lineas.find(l =>
        l.idM === idM && l.idMo === idMo && l.idC === idC && l.idV === idV
      );
      if (lineaExistente) {
        lineaExistente.cantidad += 1;
        renderizarTabla();
        document.getElementById('infoModalBody').textContent = 'Esta bicicleta ya está en la cotización. Se aumentó la cantidad en 1.';
        new bootstrap.Modal(document.getElementById('infoModal')).show();
      } else {
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

  // Quitar o cambiar cantidad
  window.quitarLinea = i => {
    lineas.splice(i, 1);
    renderizarTabla();
  };
  window.cambiarCantidad = (i, inc) => {
    const linea = lineas[i];
    if (!linea) return;
    const nueva = linea.cantidad + inc;
    if (nueva < 1) return;
    linea.cantidad = nueva;
    renderizarTabla();
  };

  // Renderizar tabla y activar botón PDF si hay al menos 5 bicis
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
          <td class="d-none d-md-table-cell">${l.colorText}</td>
          <td class="d-none d-md-table-cell">${l.voltajeText}</td>
          <td class="d-none d-lg-table-cell">${formatPesos(l.precio)}</td>
          <td>
            <button class="btn btn-sm btn-outline-danger" onclick="cambiarCantidad(${i}, -1)">-</button>
            ${l.cantidad}
            <button class="btn btn-sm btn-outline-success" onclick="cambiarCantidad(${i}, 1)">+</button>
          </td>
          <td class="d-none d-lg-table-cell">${formatPesos(sub)}</td>
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
        <th class="text-end d-none d-lg-table-cell">${formatPesos(total)}</th>
        <th></th>
      </tr>`;
    btnPDF.disabled = lineas.reduce((sum, l) => sum + l.cantidad, 0) < 5;
  }

  // Antes de enviar el formulario, prepara los datos para el PDF
  formPDF.addEventListener('submit', () => {
    inputMember.value = selMembresia.options[selMembresia.selectedIndex].text;
    inputLines.value  = JSON.stringify(lineas.map(l => ({
      membresia:      l.membresiaText,
      modelo:         l.modeloText,
      color:          l.colorText,
      voltaje:        l.voltajeText,
      precio:         l.precio,
      cantidad:       l.cantidad,
      metodo_entrega: selMetodo.value
    })));
    // El campo distancia_km y direccion_destino ya estarán poblados
  });
});
</script>



@endsection
