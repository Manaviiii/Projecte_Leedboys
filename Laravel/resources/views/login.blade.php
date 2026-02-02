<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>LedBoys - Iniciar sesión</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f0f2f5;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 50vh;
        }

        .login-container {
            background: #fff;
            width: 350px;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 26px;
        }

        input[type="email"],
        input[type="password"] {
            width: 90%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        button {
            width: 95%;
            margin-top: 15px;
            padding: 10px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
        }

        button:hover {
            background: #2980b9;
        }

        .links {
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
        }

        .links a {
            font-size: 13px;
            color: #3498db;
            text-decoration: none;
        }

        .links a:hover {
            text-decoration: underline;
        }
    </style>

</head>
<body>

<div class="login-container">

    <h1> LedBoys</h1>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <input type="email" name="email" placeholder="Correo electrónico" required>
        <br>
        <input type="password" name="password" placeholder="Contraseña" required>

        <button type="submit">Iniciar sesión</button>
    </form>

    <div class="links">
        <!--<a href="#">¿Olvidaste la contraseña?</a>-->
        <!--<a>Registrarme</a>-->
    </div>

</div>

</body>
</html>
