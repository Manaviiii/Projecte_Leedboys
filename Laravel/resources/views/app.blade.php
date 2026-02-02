<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Profesional</title>
    <link href="../css/app.css"  rel="stylesheet"> <!-- Enlace al CSS -->
</head>
<body>
    <header class="header">
        <a href="/">Inicio</a>
        <nav>
            <a href="/about">Sobre nosotros</a>
            <a href="/login">Login</a>
        </nav>
    </header>

    <section class="hero">
        <h1>Bienvenido a nuestro sitio</h1>
        <p>Una plataforma profesional para tus necesidades, con un diseño simple y efectivo.</p>
        <a href="/learn-more" class="btn">Saber más</a>
    </section>

    <section class="form-container">
        <h2>Formulario de contacto</h2>
        <form action="#">
            <input type="text" class="input" placeholder="Tu nombre" required>
            <input type="email" class="input" placeholder="Tu email" required>
            <textarea class="input" rows="4" placeholder="Tu mensaje" required></textarea>
            <button type="submit" class="btn">Enviar mensaje</button>
        </form>
    </section>

    <section class="card">
        <h2>Producto destacado</h2>
        <p>Este es un ejemplo de un producto o servicio destacado que puedes mostrar en la página.</p>
        <button class="btn">Ver más</button>
    </section>

    <footer class="footer">
        <p>&copy; 2026 Tu Empresa | <a href="/privacy-policy">Política de privacidad</a></p>
    </footer>
</body>
</html>
