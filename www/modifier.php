<?php
$host = 'mysql_db';
$dbname = 'inventory';
$username = 'root';
$password = 'rootpassword';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if (isset($_POST['serialnumber'])) {
    $serialnumber = $_POST['serialnumber'];
    $stmt = $pdo->prepare("SELECT * FROM ordinateurs WHERE serialnumber = :serialnumber");
    $stmt->execute(['serialnumber' => $serialnumber]);
    $ordinateur = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$ordinateur) {
        die("Ordinateur non trouvé.");
    }
} else {
    die("Numéro de série non fourni.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modifier'])) {
    $serialnumber = $_POST['serialnumber'];
    $utilisateur = $_POST['utilisateur'];
    $marque = $_POST['marque'];
    $commentaire = $_POST['commentaire'];
    $garantie = $_POST['garantie'];

    $sql = "UPDATE ordinateurs SET serialnumber = :serialnumber, utilisateur = :utilisateur, marque = :marque, commentaire = :commentaire, garantie =:garantie  WHERE serialnumber = :serialnumber";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['serialnumber' => $serialnumber, 'utilisateur' => $utilisateur, 'marque' => $marque, 'garantie' => $garantie , 'commentaire' => $commentaire]);
    
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['supprimer'])) {
    $serialnumber = $_POST['serialnumber'];
    $sql = "DELETE FROM ordinateurs WHERE serialnumber = :serialnumber";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['serialnumber' => $serialnumber]);
    
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Ordinateur</title>
    <link rel="stylesheet" href="/styles.css">
    <script>
        function confirmDelete() {
            return confirm("Êtes-vous sûr de vouloir supprimer cet ordinateur ?");
        }
    </script>
</head>
<body>
    <header>
        <h1>Modifier un PC portable</h1>
    </header>
    <main>
        <form method="post">
            <input type="hidden" name="serialnumber" value="<?= htmlspecialchars($ordinateur['serialnumber']) ?>">
  
            <label for="serialnumber">Numéro de série :</label>
            <input type="text" name="serialnumber" value="<?= htmlspecialchars($ordinateur['serialnumber']) ?>" required>
            
            <label for="utilisateur">Utilisateur de l'ordinateur : </label>
            <input type="text" name="utilisateur" value="<?= htmlspecialchars($ordinateur['utilisateur']) ?>" required>
            
            <label for="marque">Marque de l'ordinateur:</label>
            <input type="text" name="marque" value="<?= htmlspecialchars($ordinateur['marque']) ?>" required>

            <label for="garantie">Date de fin garantie:</label>
            <input type="date" name="garantie" value="<?= htmlspecialchars($ordinateur['garantie']) ?>" required>              
          
            <label for="commentaire">Commentaire :</label>
            <textarea name="commentaire"><?= htmlspecialchars($ordinateur['commentaire']) ?></textarea>

            <button type="submit" name="modifier">Modifier</button>
        </form>
        
        <form method="post" style="margin-top: 20px;" onsubmit="return confirmDelete();">
            <input type="hidden" name="serialnumber" value="<?= htmlspecialchars($ordinateur['serialnumber']) ?>">
            <button type="submit" name="supprimer" class="btn-supprimer">Supprimer</button>
        </form>
        
        <a href="index.php" class="btn-retour">Retour</a>
    </main>
</body>
</html>
