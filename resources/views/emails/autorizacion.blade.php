<x-mail::message>
# Solicitud de autorización de impresión

El usuario **{{ $usuarioSolicita }}** ha solicitado imprimir la bicicleta con número de chasis:  

**{{ $numChasis }}**

Por favor, selecciona una de las siguientes opciones para aprobar o rechazar la impresión:

<x-mail::button :url="route('autorizacion.responder', ['token' => $token, 'accion' => 'approve'])">
✅ Autorizar
</x-mail::button>

<x-mail::button :url="route('autorizacion.responder', ['token' => $token, 'accion' => 'reject'])" color="red">
❌ No Autorizar
</x-mail::button>


Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
