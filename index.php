<!--/*-->
<!--* Project: 4. Uebung - Kalorienrechner -->
<!--* Klasse: 3aAPC / 2025-->
<!--* Author:  Daniel Pöttler-->
<!--* Last Change: 20.11.2025-->
<!--*    by:   ptr-->
<!--*    date: 20.11.2025-->
<!--* Copyright (c): LBS Eibiswald, 2025-->
<!--*/-->

<?php
// Abfrage, ob das Formular gesendet wurde
if (isset($_REQUEST["geschlecht"])) {
    // Objekt erstellen und Werte ermitteln
    $person = new personData($_REQUEST);

    // Berechnungen durchführen
    $grund_bedarf = calcGrundumsatz($person);
    $pal = calcBewegeung($person);

    // Ausgabe
    $out = $grund_bedarf * $pal;

} else {
    $out = "";
}

// Objekt für alle Werte
class personData {
    public $geschlecht;
    public $alter;
    public $gewicht;
    public $groesse;
    public $h_sitzend;
    public $h_buero;
    public $h_sitz_geh;
    public $h_steh_geh;
    public $h_hard;

    // Beim Erstellen des Objektes werden gleich die Werte geholt
    public function __construct($req) {
        $this->geschlecht = $req["geschlecht"];
        $this->alter = $req["alter"];
        $this->gewicht = $req["gewicht"];
        $this->groesse = $req["groesse"];
        $this->h_sitzend = $req["h_sitzend"];
        $this->h_buero = $req["h_buero"];
        $this->h_sitz_geh = $req["h_sitz_geh"];
        $this->h_steh_geh = $req["h_steh_geh"];
        $this->h_hard = $req["h_hard"];
    }

}

// Grundumsatz berechnen
function calcGrundumsatz($person) {
    // Unterschiedliche Formel für Mann und Frau
    if ($person->geschlecht == "w") {
        $bedarf = 655.1 + (9.6 * $person->gewicht) + (1.8 * $person->groesse) - (4.7 * $person->alter);
    } else {
        $bedarf = 66.47 + (13.7 * $person->gewicht) + (5 * $person->groesse) - (6.8 * $person->alter);
    }

    return $bedarf;
}

// Kalorien durch Bewegung berechnen
function calcBewegeung($person) {
    // berechnen durch Formel
    $pal = $person->h_sitzend * 1.2 + $person->h_buero * 1.45 + $person->h_sitz_geh * 1.65 + $person->h_steh_geh * 1.85 + $person->h_hard * 2.2;
    $pal = $pal / 24;
    return $pal;
}
?>


<!--Frontend durch KI-->
<!doctype html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <title>Kalorienbedarf Rechner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<!--Ausgabe des Kalorienbedarfs-->
<!--Abfrage ob Output überhaupt vorhanden ist-->
<?php if (is_numeric($out)): ?>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-6">

                <div class="card text-center shadow-lg border-success">
                    <div class="card-header bg-success text-white">
                        <h4>Dein täglicher Kalorienbedarf</h4>
                    </div>
                    <div class="card-body">
                        <p class="card-text fs-3 fw-bold">
                            <?= round($out, 2) ?> kcal
                        </p>

                        <hr>

                        <div class="text-start">
                            <p><strong>Gewicht halten:</strong> <?= round($out, 2) ?> kcal</p>
                            <p><strong>Zum Abnehmen (-400 kcal):</strong> <?= round($out - 400, 2) ?> kcal</p>
                            <p><strong>Zum Zunehmen (+400 kcal):</strong> <?= round($out + 400, 2) ?> kcal</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
<?php endif; ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4>Kalorienbedarf – Eingabeformular</h4>
                </div>
                <div class="card-body">

                    <form method="get" action="/itl1/4_uebung_kalorien/index.php">

                    <h5>1. Persönliche Daten</h5>
                        <div class="mb-3">
                            <label class="form-label">Geschlecht</label><br>
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="geschlecht" value="w" required>
                                <label class="form-check-label">Frau</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="geschlecht" value="m" required>
                                <label class="form-check-label">Mann</label>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Alter (Jahre)</label>
                                <input type="number" class="form-control" name="alter" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Gewicht (kg)</label>
                                <input type="number" class="form-control" name="gewicht" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Größe (cm)</label>
                                <input type="number" class="form-control" name="groesse" required>
                            </div>
                        </div>

                        <hr>

                        <h5>2. Stunden pro Tätigkeit (pro Tag)</h5>
                        <p class="small text-muted">Schlaf wird automatisch berechnet.</p>

                        <div class="mb-3">
                            <label class="form-label">Sitzend/liegend – PAL 1.2</label>
                            <input type="number" class="form-control" name="h_sitzend" min="0" step="0.25">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Büro – PAL 1.45</label>
                            <input type="number" class="form-control" name="h_buero" min="0" step="0.25">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Sitzend/gehend – PAL 1.65</label>
                            <input type="number" class="form-control" name="h_sitz_geh" min="0" step="0.25">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Stehend/gehend – PAL 1.85</label>
                            <input type="number" class="form-control" name="h_steh_geh" min="0" step="0.25">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Körperlich schwer – PAL 2.2</label>
                            <input type="number" class="form-control" name="h_hard" min="0" step="0.25">
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Berechnen</button>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
</body>
</html>