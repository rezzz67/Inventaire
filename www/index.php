<?php
$servername = 'mysql_db';
$host = 'mysql_db';
$dbname = 'inventory';
$username = 'root';
$password = 'rootpassword';

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajouter'])) {
    $serialnumber = $_POST['serialnumber'] ?? '';
    $utilisateur = $_POST['utilisateur'] ?? '';
    $marque = $_POST['marque'] ?? '';
    $garantie = $_POST['garantie'] ?? '';
    $commentaire = $_POST['commentaire'] ?? ''; // Facultatif

    if ($serialnumber && $utilisateur && $marque) {
        try {
            $sql = "INSERT INTO ordinateurs (serialnumber, utilisateur, marque, garantie, commentaire) VALUES (:serialnumber, :utilisateur, :marque,:garantie, :commentaire)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['serialnumber' => $serialnumber, 'utilisateur' => $utilisateur, 'marque' => $marque, 'garantie' => $garantie, 'commentaire' => $commentaire]);
            echo "Ordinateur ajouté avec succès.<br>";
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout de l'ordinateur : " . $e->getMessage() . "<br>";
        }
    } else {
        echo "Tous les champs sont obligatoires, sauf le commentaire.<br>";
    }
}

$liste_ordinateurs = $pdo->query("SELECT * FROM ordinateurs")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inventaire</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Ajouter un ordinateur</h1>
    <form method="POST">
        <label for="serialnumber">Numéro de série :</label>
        <input type="text" id="serialnumber" name="serialnumber" required><br>
        <label for="utilisateur">Nom de l'utilisteur :</label>
        <input type="text" id="utilisateur" name="utilisateur" required><br>
        <label for="marque">Marque :</label>
        <input type="text" id="marque" name="marque" required><br>
        <label for="garantie">Date de fin de garantie :</label>
        <input type="date" id="garantie" name="garantie" required><br>
        <label for="commentaire">Commentaire :</label>
        <textarea id="commentaire" name="commentaire"></textarea><br>
        <button type="submit" name="ajouter">Ajouter</button>
    </form>

    <h1>Liste des ordinateurs</h1>
    <table>
        <thead>
            <tr>
                <th>Numéro de série</th>
                <th>Utilisateur</th>
                <th>Marque du PC</th>
                <th>Date de fin de garantie</th>
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
                    <td><?= htmlspecialchars($ordinateur['garantie']) ?></td>
                    <td><?= htmlspecialchars($ordinateur['commentaire']) ?></td>
                    <td>
                        <form method="post" action="modifier.php" style="display:inline;">
                            <input type="hidden" name="serialnumber" value="<?= htmlspecialchars($ordinateur['serialnumber']) ?>">
                            <button type="submit">Modifier</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
