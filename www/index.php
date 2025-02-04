<?php
$host = 'localhost';
$dbname = 'inventory';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajouter'])) {
    $serialnumber = $_POST['serialnumber'] ?? '';
    $utilisateur = $_POST['utilisateur'] ?? '';
    $marque = $_POST['marque'] ?? '';
    $commentaire  = $_POST['commentaire'] ?? '';

    if ($utilisateur && $reservation && $os && $serialnumber) {
        $sql = "INSERT INTO ordinateurs (serialnumber, utilisateur,marque,commentaire ) VALUES ( :serialnumber, :utilisateur, :marque, :commentaire)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['serialnumber' => $serialnumber, 'utilisateur' => $utilisateur, 'marque' => $marque, 'commentaire' => $commentaire]);
    }
}

$liste_ordinateurs = $pdo->query("SELECT * FROM ordinateurs")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaire des Ordinateurs</title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
    <header>
        <h1>Inventaire des PC portables</h1>
    </header>
    <main>
        <section id="ajout-ordinateur">
            <h2>Ajouter un ordinateur</h2>
            <form method="post">
                            
                <label for="serialnumber"> Numéro de série :</label>
                <input type="text" name="serialnumber" required>
            
                <label for="utilisateur">Utilisateur :</label>
                <input type="text" name="utilisateur" required>
                
                <label for="marque">Marque du PC :</label>
                <input type="text" name="marque" required>
                
                <label for="commentaire">Commentaire</label>
                <textarea cols=10 rows=5 name="commentaire"></textarea>
                
                <button type="submit" name="ajouter">Ajouter</button>
            </form>
        </section>
        
        <section id="liste-ordinateurs">
            <h2>Liste des ordinateurs</h2>
            <table>
                <thead>
                    <tr>
                        <th>Numéro de série</th>
                        <th>Utilisateur</th>
                        <th>Marque du PC</th>
                        <th>Commentaire</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($liste_ordinateurs as $ordinateur) : ?>
                        <tr>
                            <td><?= htmlspecialchars($ordinateur['serialnumber']) ?></td>
                            <td><?= htmlspecialchars($ordinateur['utilisateur']) ?></td>
                            <td><?= htmlspecialchars($ordinateur['marque']) ?></td>
                            <td><?= htmlspecialchars($ordinateur['commentaire']) ?></td>
                            <td>
                                <form method="post" action="modifier.php" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $ordinateur['id'] ?>">
                                    <button type="submit">Modifier</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
