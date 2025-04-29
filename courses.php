<?php 
include_once("conexao.php");
$sql = "SELECT nome, descricao, imagem FROM disciplinas";
$result = mysqli_query($connection, $sql);




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coude</title>
    <link rel="stylesheet" href="style_2.css">
    <!-- Link Arrow Forward-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=arrow_forward" />
    <!-- Link Icon Footer-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
    
    <section class="courses">
        <div class="courses-title">
            <h1>Explore nossos principais Cursos</h1>
            <p>Aprenda habilidades práticas e transforme sua carreira com nossos cursos.</p>
        </div>
        <div class="courses-grid">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <div class="course-card">
                        <img src="<?php echo htmlspecialchars($row['imagem']); ?>" alt="Imagem do curso" width="602px" height="150px" style="object-fit: cover;">
                        <h3><?php echo htmlspecialchars($row['nome']); ?></h3>
                        <p><?php echo htmlspecialchars($row['descricao']); ?></p>
                        <a href="contact.html" class="btn-course">Saiba mais</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Nenhum curso disponível no momento.</p>
            <?php endif; ?>
        </div>
    </section>

    <footer>
        <section class="line-footer">
            <div class="box-line-footer">
                <img src="img/logo-br.png" alt="Logotipo">
                <p>COUDE - Sua jornada na programação<br>começa aqui!</p>
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
    </footer>
</body>
</html>

<?php
// Fechar conexão
mysqli_close($connection);
?>