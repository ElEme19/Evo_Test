<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Error 500 - Algo salió mal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --bg: #1e293b;
            --text: #f8fafc;
            --accent: #38bdf8;
            --danger: #ef4444;
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
            color: var(--danger);
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
                <circle cx="32" cy="32" r="30" stroke="#ef4444" stroke-width="4"/>
                <line x1="20" y1="20" x2="44" y2="44" stroke="#ef4444" stroke-width="4" stroke-linecap="round"/>
                <line x1="44" y1="20" x2="20" y2="44" stroke="#ef4444" stroke-width="4" stroke-linecap="round"/>
            </svg>
        </div>
        <h1>500</h1>
        <h2>¡Ups! Algo salió mal.</h2>
        <p>Ocurrió un error inesperado en el servidor. Por favor, intenta nuevamente más tarde.</p>
        <a href="{{ url('/Mexico/inicio') }}" class="btn">Volver al inicio</a>
    </div>
</body>
</html>
