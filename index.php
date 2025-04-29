<?php
include_once("conexao.php");

$sql = "SELECT nome, descricao, imagem FROM disciplinas";
$result = mysqli_query($connection, $sql);
// Separando para poder usar duas vezes o mesmo resultado:
$disciplinas = [];
if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $disciplinas[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coude</title>
    <script defer src="home.js"></script>
    <link rel="stylesheet" href="style_2.css">
    <!-- Link Arrow Forward-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=arrow_forward" />
    <!-- Link Icon Footer-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
</head>

<body>
    <header>
        <img src="img/logo.png" alt="logo">
        <nav>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="about.html">Sobre nós</a></li>
                <li><a href="courses.php">Cursos</a></li>
                <li><a href="contact.html">Contato</a></li>
            </ul>
        </nav>
        <a class="btn-enter" href="login.html">Entrar</a>
    </header>
    
    <section class="intro-section">
        <div class="intro-launcher">
            <div class="box-line-intro"></div>
            <p class="p-line-intro">A porta de entrada para a sua carreira<br>em tecnologia é aqui!</p>
            <div class="box-line-intro">
            <button class="btn-enter"><a href="contact.html">Quero me inscrever</a></button>
            </div>
        </div>
    </section>

    <section class="stats-section">
        <div class="stat">
            <span class="number">807</span>
            <p>Cursos abertos</p>
        </div>
        <div class="stat">
            <span class="number">19</span>
            <p>Cursos exclusivos</p>
        </div>
        <div class="stat">
            <span class="number">94</span>
            <p>Programas</p>
        </div>
        <div class="stat">
            <span class="number">7.2 <small>milhões</small></span>
            <p>Certificados emitidos</p>
        </div>
    </section>

    <section class="courses">
    <div class="courses-tittle">
        <h1>Formação Full Stack</h1>
    </div>

    <div class="categories">
        <?php foreach ($disciplinas as $disciplina): ?>
            <button class="btn-class"><?php echo htmlspecialchars($disciplina['nome']); ?></button>
        <?php endforeach; ?>
    </div>

    <div class="allContent">
        <div class="container">
            <div class="card-wrapper swiper">
                <ul class="card-list swiper-wrapper">
                    <?php foreach ($disciplinas as $disciplina): ?>
                        <li class="card-item swiper-slide" >
                            <a href="courses.php" class="card-link">
                                <img src="<?php echo htmlspecialchars($disciplina['imagem']); ?>" alt="Imagem do curso" class="card-image" style="object-fit: cover;">
                                <p class="badge"><?php echo htmlspecialchars($disciplina['nome']); ?></p>
                                <h2 class="card-tittle"><?php echo htmlspecialchars($disciplina['descricao']); ?></h2>
                                <button class="card-buttom material-symbols-outlined">arrow_forward</button>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <div class="swiper-pagination"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-scrollbar"></div>
            </div>
        </div>
    </div>

    <div>
        <button class="btn-courses">
            <a href="courses.php">Ver catálogo de Cursos</a>
        </button>
    </div>
</section>

<?php
// Fechar conexão
mysqli_close($connection);
?>

    <footer>
            <section class="line-footer">
                <div class="box-line-footer">
                    <img src="img/logo-br.png" alt="Logotipo">
                    <p>COUDE - Sua jornada na
                        programação<br>começa aqui!</p>
                </div>
            
                <div class="box-line-footer">
                    <p class="title">Para alunos</p>
                    <div class="links-footer">
                        <ul>
                            <li><a href="about.html">SOBRE NÓS</a></li>
                            <li><a href="courses.php">CURSOS</a></li>
                            <li><a href="contact.html">CONTATO</a></li>
                        </ul>
                    </div>
                </div>

                <div class="box-line-footer">
                    <p class="title">Para parceiros</p>
                    <div class="links-footer">
                        <ul>
                            <li><a href="adesao.html">ADESÃO</a></li>
                        </ul>
                    </div>
                </div>

                <div class="box-line-footer">
                    <p class="title">Nas redes</p>
                    <div class="btn-redes"></div>
                    <ul>
                        <li><a href="https://www.youtube.com/@CoudeTecnologia"><button><i class="bi bi-youtube"></i></button></a></li>
                        <li><a href="https://www.linkedin.com/company/coude/"><button><i class="bi bi-linkedin"></i></button></a></li>
                        <li><a href="https://www.instagram.com/coude.oficial/"><button><i class="bi bi-instagram"></i></button></a></li>
                        <li><a href="#"><button><i class="bi bi-facebook"></i></button></a></li>
                    </ul>
                 </div>
            </section>
        </div>
    </footer>
    <script  src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</body>
</html>