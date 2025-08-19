<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Autorización</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="alert alert-success text-center">
        <h4>✅ Éxito</h4>
        <p>{{ $mensaje }}</p>
        <a href="{{ url('/') }}" class="btn btn-primary mt-3">Volver al inicio</a>
    </div>
</div>
</body>
</html>
