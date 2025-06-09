<!DOCTYPE html>
<html>
<head>
    <title>Verificatiecode</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light text-dark">
    <div class="container mt-5">
        <div class="card border-danger shadow-sm">
            <div class="card-header bg-danger text-white text-center">
                <h2 class="mb-0">Zen Sushi Verificatiecode</h2>
            </div>
            <div class="card-body">
                <p class="mb-3">Beste Sushi Liefhebber,</p>
                <p class="mb-3">Uw verificatiecode is:</p>
                <h1 class="text-center text-danger"><strong>{{ $captcha }}</strong></h1>
                <p class="mt-3">Deze code is geldig voor <strong>5 minuten</strong>.</p>
                <p class="mt-3">Bedankt dat u Zen Sushi kiest, waar elke hap een genot is!</p>
            </div>
            <div class="card-footer text-center bg-light">
                <small class="text-danger">© {{ date('Y') }} Zen Sushi - Versheid Bezorgd</small>
            </div>
        </div>
    </div>
</body>
</html>