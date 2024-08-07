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

// Inclure les fichiers nécessaires
require_once '../../config/Database.php';
require_once '../models/HabitatModel.php';
require_once '../models/ReviewModel.php';
require_once '../models/ZooHoursModel.php';

// Connexion à la base de données
$db = (new Database())->connect();

$habitat = new Habitat($db);
$habitats = $habitat->getToutHabitats();

$review = new Review($db);
$approvedReviews = $review->getAvisApprouvés();

$zooHours = new ZooHours($db);
$hours = $zooHours->getAllHours();

// Inclure les fichiers de template
include '../../src/views/templates/header.php';
include '../../src/views/templates/navbar_visitor.php';
?>
<style>
h1,h2,h3 {
    text-align: center;
}

body {
    background-image: url('../../assets/image/background.jpg');
    padding-top: 48px;
}
.mt-4 {
    max-height: 500px;
    overflow-y: auto;
}
</style>

<h1 id="apropos" style="color: white;">.</h1>
<div class="container mt-5" style="background: linear-gradient(to right, #ffffff, #ccedb6);">
    <div class="container">
        <br>
        <hr>
        <h1>Bienvenue au Zoo Arcadia</h1>
        <hr>
        <br>
        <p>Situé depuis 1960 à proximité de la légendaire forêt de Brocéliande, dans la région enchanteresse de la Bretagne, le Zoo Arcadia s'est établi comme un sanctuaire dédié à la conservation et à la préservation de la faune mondiale. Depuis ses modestes débuts, il a évolué pour devenir un pilier de l'éducation environnementale et de la sensibilisation à la biodiversité.</p>
        <p>Les vastes étendues du Zoo Arcadia abritent une myriade d'animaux, offrant aux visiteurs une immersion totale dans les merveilles de la nature. Avec des habitats soigneusement aménagés pour refléter les environnements naturels d'origine, dont la savane africaine, la jungle amazonienne et les marais tropicaux, chaque coin du zoo est une invitation à l'aventure.</p>
        <p>Les vastes plaines de la savane accueillent les majestueux lions, les éléphants paisibles et les girafes gracieuses, offrant aux visiteurs un aperçu de la vie sauvage africaine. Dans la jungle dense, les singes espiègles se balancent d'arbre en arbre, les jaguars se faufilent dans les ombres et les oiseaux tropicaux colorent le ciel de leurs plumes éclatantes.</p>
        <p>Les marais tranquilles abritent une multitude d'espèces, des crocodiles somnolents aux hérons élégants, tandis que les tortues glissent silencieusement à travers les eaux calmes. Chaque habitat est conçu pour offrir aux animaux un environnement naturel et stimulant, favorisant leur bien-être et leur épanouissement.</p>
        <p>À travers des initiatives de conservation et des programmes éducatifs, le Zoo Arcadia s'efforce de sensibiliser le public à l'importance de la protection de la faune et de la flore. Des visites guidées, des présentations interactives et des rencontres avec les gardiens permettent aux visiteurs de découvrir de près la beauté et la diversité du monde animal, tout en apprenant les défis auxquels ces espèces sont confrontées dans la nature.</p>
        <p>Que vous soyez un amateur de la nature passionné ou simplement en quête d'une escapade familiale inoubliable, le Zoo Arcadia promet une expérience immersive et enrichissante pour les visiteurs de tous âges. Entrez dans un monde où la magie de la nature prend vie et où chaque visite est une aventure à part entière.</p>
        <br>
        <hr>
        <h2>Habitats</h2>
        <hr>
        <br>
        <div class="row">
            <?php foreach ($habitats as $habitat): ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <img class="card-img-top" src="../../assets/uploads/<?php echo htmlspecialchars($habitat['image']); ?>" alt="<?php echo htmlspecialchars($habitat['name']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($habitat['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($habitat['description']); ?></p>
                            <a href="habitat.php?id=<?php echo $habitat['id']; ?>" class="btn btn-success">Voir les habitants</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <h1 style="color: #ccedb6;" id="openhours">.</h1>
        <hr>
        <h2>Horaires d'ouverture du Zoo</h2>
        <hr>
        <br>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Jour</th>
                        <th>Heures d'ouverture</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($hours as $hour): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($hour['day']); ?></td>
                        <td><?php echo htmlspecialchars(substr($hour['open_time'], 0, 5) . ' - ' . substr($hour['close_time'], 0, 5)); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <br>
        <hr>
        <h2>Laissez un avis</h2>
        <hr>
        <br>
        <div id="avis" class="col md-4">
            <form action="submit_review.php" method="POST" class="mt-4">
                <div class="mb-3">
                    <label for="pseudo" class="form-label">Pseudo:</label>
                    <input type="text" class="form-control" id="pseudo" name="pseudo" required>
                </div>
                <div class="mb-3">
                    <label for="subject" class="form-label">Objet:</label>
                    <input type="text" class="form-control" id="subject" name="subject" required>
                </div>
                <div class="mb-3">
                    <label for="review_text" class="form-label">Texte de l'avis:</label>
                    <textarea class="form-control" id="review_text" name="review_text" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-success">Envoyer</button>
            </form>
        </div>
        <br>
            
        <hr> 
        <h2>Avis des visiteurs</h2>
        <hr>
        <div class="container mt-5">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col" class="col-3">Objet</th>
                            <th scope="col" class="col-8">Avis</th>
                            <th scope="col" class="col-3" style="text-align: right;">Date</th>
                            <th scope="col" class="col-1" style="text-align: right;">Posté par</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($approvedReviews as $review): ?>
                        <tr>
                            <td style="color: green; font-weight: bold; text-align: center;"><?php echo htmlspecialchars($review['subject']); ?></td>
                            <td style="text-align: center;"><?php echo htmlspecialchars($review['review_text']); ?></td>
                            <td style="text-align: right;"><?php echo htmlspecialchars(date('d/m/Y', strtotime($review['created_at']))); ?></td>
                            <td style="text-align: right; color: red; font-weight: bold;"><?php echo htmlspecialchars($review['visitor_name']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../../src/views/templates/footer.php'; ?>
