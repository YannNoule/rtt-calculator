<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Calculatrice RTT</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            margin-top: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input[type="number"] {
            width: 50%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .result {
            margin-top: 20px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .result h2 {
            color: #333;
            margin-bottom: 10px;
        }
        .result p {
            color: #555;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Calculatrice RTT</h1>

    <form action="{{ url('/calculate-rtt') }}" method="POST">
        @csrf <!-- Token CSRF pour la sécurité -->

        <div class="form-group">
            <label for="year">Année:</label>
            <input type="number" id="year" name="year" value="{{ old('year') }}" required>
        </div>

        <div class="form-group">
            <label for="worked_days">Nombre de jours travaillés (218 par défaut):</label>
            <input type="number" id="worked_days" name="worked_days" value="{{ old('worked_days', 218) }}" required>
        </div>

        <div class="form-group">
            <label for="paid_leave_days">Nombre de jours de congés payés (25 par défaut):</label>
            <input type="number" id="paid_leave_days" name="paid_leave_days" value="{{ old('paid_leave_days', 25) }}" required>
        </div>

        <button type="submit">Calculer les RTT</button>
    </form>

    @if(session('year') !== null)
        <div class="result">
            <p>Année : {{ session('year') }} {{ session('isLeapYear') ? '(Bissextile)' : '' }}</p>
            <p>Nombre de weekends : {{ session('weekends') }}</p>
            <p>Nombre de jours fériés ne tombant pas un weekend : {{ session('publicHolidays') }}</p>
            <h2>Nombre de jours de RTT pour l'année {{ session('year') }} : {{ session('rtt_days') }}</h2>
        </div>
    @endif
</div>

</body>
</html>
