<!-- <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>404</title>
     <link rel="icon" type="image/x-icon" href="{{ asset('images/favico.ico') }}">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
        }
        body {
            background-image: url("{{ asset('images/404.png') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: sans-serif;
            color: white;
        }
        .message {
            background: rgba(0, 0, 0, 0.5); /* fondo oscuro transparente para el texto */
            padding: 40px;
            border-radius: 12px;
            text-align: center;
        }
        h1 {
            font-size: 64px;
            margin: 0 0 20px;
        }
        p {
            font-size: 20px;
        }
        a {
            color: #fff;
            text-decoration: underline;
            margin-top: 10px;
            display: inline-block;
        }
    </style>
</head>
<body>
   
</body>
</html>
 -->

 <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Error 404 - Página no encontrada</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --bg: #1e293b;
            --text: #f8fafc;
            --accent: #38bdf8;
            --warning: #facc15;
            --font: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: var(--bg);
            color: var(--text);
            font-family: var(--font);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .container {
            max-width: 500px;
            padding: 20px;
            animation: fadeIn 0.8s ease-in-out;
        }

        h1 {
            font-size: 5rem;
            margin-bottom: 0.5rem;
            color: var(--warning);
        }

        h2 {
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }

        p {
            font-size: 1rem;
            color: #cbd5e1;
            margin-bottom: 2rem;
        }

        .icon {
            width: 120px;
            margin: 0 auto 1rem;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: var(--accent);
            color: #000;
            font-weight: bold;
            text-decoration: none;
            border-radius: 8px;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background-color: #0ea5e9;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">
            <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="32" cy="32" r="30" stroke="#facc15" stroke-width="4"/>
                <path d="M24 24h16v16H24z" stroke="#facc15" stroke-width="4" fill="none"/>
                <path d="M32 44v4" stroke="#facc15" stroke-width="4" stroke-linecap="round"/>
            </svg>
        </div>
        <h1>404</h1>
        <h2>Página no encontrada</h2>
        <p>La URL que intentas acceder no existe o fue movida. Verifica o regresa a la página principal.</p>
        <a href="{{ url('/') }}" class="btn">Volver al inicio</a>
    </div>
</body>
</html>
