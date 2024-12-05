<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        body {
            background-color: #EDF2F7;
        }

        a {
            color: #ffffff !important;
        }

        .card {
            border-radius: 1vw;
            background-color: white;
            text-align: center;
            width: 30vw;
            padding: 3vw;
            margin-left: auto;
            margin-right: auto;
            color: var(--color-neutral4);
            font-size: 1rem;
        }

        .btn {
            background-color: #2563eb;
            padding: 12px 16px;
            border-radius: 5px;
            text-decoration: none;
            color: #ffffff;
            font-size: 1rem;
        }

        .img {
            width: 300px;
        }
    </style>
</head>

<body>
    <div class="card">
        <img class="img" src="https://acerta-cert.com/wp-content/uploads/cropped-acerta-crispon-2048x536.png"
            alt="acerta-logo">
        <p>Hola!</p>

        <p>Has recibido este mensaje porque el usuario asociado a este correo ha solicitado un restablecimiento de
            contraseña. Para establecer la
            contraseña, accede al siguiente enlace:</p>

        <a class="btn" href="{{ $url }}">Restablecer contraseña</a>

        <p>Si no reconoces esta actividad, notifica inmediatamente al administrador del sistema.</p>
    </div>
</body>

</html>
