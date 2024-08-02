<?php
session_start();

// Durée de vie de la session en secondes (30 minutes)
$sessionLifetime = 1800;

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $sessionLifetime)) {

    session_unset();  
    session_destroy(); 
    header('Location: login.php');
    exit;
}

$_SESSION['LAST_ACTIVITY'] = time();

require_once '../../config/Database.php';
require_once '../models/HabitatModel.php';

$db = new Database();
$conn = $db->connect();

$habitat = new Habitat($conn);
$habitats = $habitat->getToutHabitats();

include '../../src/views/templates/header.php';
include '../../src/views/templates/navbar_visitor.php';
?>
<style>

h1,h2,h3 {
    text-align: center;
}

body {
    background-image: url('../../assets/image/background.jpg');
    padding-top: 68px;
}
.mt-5, .mb-4 {
    background: whitesmoke;
    border-radius: 15px;
}
</style>

<div class="container mb-4" style="background: linear-gradient(to right, #ffffff, #ccedb6);">
    <br>
    <hr>
    <h1 class="my-4">Tous les Habitats</h1>
    <hr>
    <br>
    <div class="row">
        <?php foreach ($habitats as $habitat): ?>
            <div class="col-md-4">
                <div class="card mb-4  text-dark">
                    <img class="card-img-top" src="../../assets/uploads/<?php echo htmlspecialchars($habitat['image']); ?>" alt="<?php echo htmlspecialchars($habitat['name']); ?>">
                    <div class="card-body">
                        <h5 class="card-title" style="text-align: center";><?php echo htmlspecialchars($habitat['name']); ?></h5>
                        <a href="habitat.php?id=<?php echo $habitat['id']; ?>" class="btn btn-success">Voir les habitants</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include '../../src/views/templates/footer.php'; ?>
