-- Criação do banco de dados
drop DATABASE IF EXISTS sistema_esc;
CREATE DATABASE IF NOT EXISTS sistema_esc;
USE sistema_esc;

-- Tabela de usuários
DROP TABLE IF EXISTS usuarios;
CREATE TABLE usuarios (
  id INT NOT NULL AUTO_INCREMENT,
  nome VARCHAR(40) NOT NULL,
  email VARCHAR(50) UNIQUE NOT NULL,
  idade INT NOT NULL,
  cpf VARCHAR(14) UNIQUE NOT NULL,
  senha VARCHAR(255) NOT NULL,
  tipo ENUM('Professor','Aluno', "Administrador") NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de disciplinas
DROP TABLE IF EXISTS disciplinas;
CREATE TABLE disciplinas (
  id INT NOT NULL AUTO_INCREMENT,
  nome VARCHAR(30) NOT NULL,
  descricao TEXT,
  imagem VARCHAR(255),
  PRIMARY KEY (id)
  );
  
DROP TABLE IF EXISTS administradores;
CREATE TABLE administradores (
 id INT NOT NULL AUTO_INCREMENT,
  fk_user INT NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY (fk_user),
  FOREIGN KEY (fk_user) REFERENCES usuarios (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de professores
DROP TABLE IF EXISTS professores;
CREATE TABLE professores (
  id INT NOT NULL AUTO_INCREMENT,
  fk_user INT NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY (fk_user),
  FOREIGN KEY (fk_user) REFERENCES usuarios (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de alunos
DROP TABLE IF EXISTS alunos;
CREATE TABLE alunos (
  id INT NOT NULL AUTO_INCREMENT,
  matricula INT NOT NULL UNIQUE,
  fk_user INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (fk_user) REFERENCES usuarios (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- Tabela de turmas
DROP TABLE IF EXISTS turmas;
CREATE TABLE turmas (
  id INT NOT NULL AUTO_INCREMENT,
  nome VARCHAR(50) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE prof_disc_turma (
  id INT AUTO_INCREMENT PRIMARY KEY,
  fk_prof INT NOT NULL,
  fk_disc INT NOT NULL,
  fk_turma INT NOT NULL,
  FOREIGN KEY (fk_prof) REFERENCES professores(id) ON DELETE CASCADE,
  FOREIGN KEY (fk_disc) REFERENCES disciplinas(id) ON DELETE CASCADE,
  FOREIGN KEY (fk_turma) REFERENCES turmas(id) ON DELETE CASCADE,
  UNIQUE KEY (fk_prof, fk_disc, fk_turma)
);

-- Tabela de relação alunos-turmas
DROP TABLE IF EXISTS turma_alunos;
CREATE TABLE turma_alunos (
  id INT NOT NULL AUTO_INCREMENT,
  fk_turma INT NOT NULL,
  fk_aluno INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (fk_turma) REFERENCES turmas(id) ON DELETE CASCADE,
  FOREIGN KEY (fk_aluno) REFERENCES alunos(id) ON DELETE CASCADE,
  UNIQUE KEY (fk_turma, fk_aluno)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de notas
DROP TABLE IF EXISTS notas;
CREATE TABLE notas (
  id INT NOT NULL AUTO_INCREMENT,
  nota DECIMAL(5,2) NOT NULL,
  dataL DATE NOT NULL,
  fk_aluno INT NOT NULL,
  fk_prof INT NOT NULL,
  fk_disc INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (fk_aluno) REFERENCES alunos (id) ON DELETE CASCADE,
  FOREIGN KEY (fk_prof) REFERENCES professores (id) ON DELETE CASCADE,
  FOREIGN KEY (fk_disc) REFERENCES disciplinas (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    
-- Inserção de dados na tabela usuarios
LOCK TABLES usuarios WRITE;
INSERT INTO usuarios VALUES 
(1,'Helder','helder@gmail.com',20,'121.456.789-00','12345','Aluno'),
(2,'Matheus','matheus@gmail.com',18,'122.456.789-00','12345','Aluno'),
(3,'Ari','ari@gmail.com',27,'123.456.789-00','12345','Aluno'),
(4,'Giovanna','giovanna@gmail.com',17,'124.456.789-00','12345','Aluno'),
(5,'Gillis','gillis@gmail.com',40,'125.456.789-00','12345','Aluno'),
(6,'William','william@gmail.com',17,'126.456.789-00','12345','Aluno'),
(7,'Nicholas','nicholas@gmail.com',25,'127.456.789-00','12345','Professor'),
(8,'Jhonta','jhonta@gmail.com',28,'128.456.789-00','12345','Professor'),
(9,'lucas','lucas@gmail.com',23,'129.456.789-00','12345','Aluno'),
(10,'caio','caio@gmail.com',34,'130.456.789-00','12345','Aluno'),
(11,'alex','alex@gmail.com',23,'000.456.789-00','12345','Aluno'),
(12,'Pedro','pedro@gmail.com',21,'001.456.789-00','12345','Aluno'),
(13,'Gabriel','gabriel@gmail.com',23,'020.456.789-00','12345','Aluno'),
(14, 'Rafael', 'rafael@gmail.com', 19, '002.456.789-00', '12345', 'Aluno'),
(15, 'Felipe', 'felipe@gmail.com', 22, '003.456.789-00', '12345', 'Aluno'),
(16, 'Gabriele', 'gabriele@gmail.com', 20, '004.456.789-00', '12345', 'Aluno'),
(17, 'Marcos', 'marcos@gmail.com', 24, '005.456.789-00', '12345', 'Aluno'),
(18, 'Leonardo', 'leonardo@gmail.com', 18, '006.456.789-00', '12345', 'Aluno'),
(19, 'Thiago', 'thiago@gmail.com', 21, '007.456.789-00', '12345', 'Aluno'),
(20, 'Yasmim', 'yasmim@gmail.com', 22, '008.456.789-00', '12345', 'Aluno'),
(21, 'Daniel', 'daniel@gmail.com', 31, '009.456.789-00', '12345', 'Aluno'),
(22, 'Bruno', 'bruno@gmail.com', 40, '010.456.789-00', '12345', 'Aluno'),
(23, 'Fernando', 'fernando@gmail.com', 60, '011.456.789-00', '12345', 'Administrador'),
(24, 'Fernanda', 'fernanda@gmail.com', 55, '012.456.789-00', '12345', 'Administrador'),
(25, 'James Silva', 'silva@gmail.com', 39, '013.456.789-00', '12345', 'Professor');
UNLOCK TABLES;
SELECT * FROM alunos WHERE fk_user = 1;
LOCK TABLES disciplinas WRITE;
INSERT INTO disciplinas VALUES 
(1,'BANCO DE DADOS', 'Aprenda a gerenciar bancos de dados com eficiência e domine as consultas SQL.', 'img/sql.jpg'), 
(2,'LINGUAGEM PHP', 'Desenvolva aplicações web dinâmicas com uma das linguagens mais populares.', 'img/php.jpg'),
(3,'HTML', 'Conheça a linguagem padrão usada para criar e estruturar páginas na web, definindo elementos como textos, imagens e links.', 'img/html.jpg'), 
(4,'CSS', 'Aprenda a estilizar com a maior linguagem de estilo das páginas web', 'img/css.png'),
(5,'JAVASCRIPT', 'Domine a linguagem de programação que dá vida às páginas web.', 'img/js.jpg'),
(6,'GIT E GITHUB', 'Controle de versão e colaboração em projetos com Git e GitHub.', 'img/git.png'),
(7,'JQUERY', 'Simplifique a manipulação do DOM e torne suas páginas mais dinâmicas.', 'img/jquery.jpg'),
(8,'BOOTSTRAP', 'Desenvolva sites responsivos usando o framework CSS mais popular do mercado.', 'img/bootstrap.jpg'),
(9,'INGLES', 'Aprenda o idioma mais utilizado no mundo da tecnologia e amplie suas oportunidades.', 'img/ingles.jpg')
;
UNLOCK TABLES;
-- Inserção de dados na tabela professores
LOCK TABLES professores WRITE;
INSERT INTO professores VALUES (1,7),(2,8),(3,25); -- 1 relaciona com Nicholas, 2 relaciona com Jhonta e 3 com James
UNLOCK TABLES;

LOCK TABLES administradores WRITE;
INSERT INTO administradores VALUES (1,23),(2,24); -- 1 relaciona com Fernando e 2 relaciona com Fernanda
UNLOCK TABLES;

-- Inserção de dados na tabela alunos
LOCK TABLES alunos WRITE;
INSERT INTO alunos VALUES 
(1,202401,1),  -- Relaciona com usuário Helder (id 1)
(2,202402,2), -- Relaciona com usuário Matheus (id 2)
(3,202403,3), -- Relaciona com usuário Ari (id 3)
(4,202404,4),  -- Relaciona com usuário Giovanna (id 4)
(5,202405,5), -- Relaciona com usuário Gillis (id 5)
(6,202406,6), -- Relaciona com usuário william (id 6)
(7,202407,9), -- Relaciona com usuário lucas (id 9)
(8,202408,10), -- Relaciona com usuário caio (id 10)
(9,202409,11), -- Relaciona com usuário alex (id 11)
(10, 202410, 12),  -- Relaciona com usuário Pedro (id 12)
(11, 202411, 13),  -- Relaciona com usuário Gabriel (id 13
(12, 202412, 14), -- Relaciona com usuário Rafael (id 14)
(13, 202413, 15),  -- Relaciona com usuário Felipe (id 15)
(14, 202414, 16),  -- Relaciona com usuário Gabriele (id 16)
(15, 202415, 17),  -- Relaciona com usuário Marcos (id 17)
(16, 202416, 18),  -- Relaciona com usuário Leonardo (id 18)
(17, 202417, 19),  -- Relaciona com usuário Thiago (id 19)
(18, 202418, 20),  -- Relaciona com usuário yasmim (id 20)
(19, 202419, 21),  -- Relaciona com usuário Daniel (id 21)
(20, 202420, 22); -- Relaciona com usuário Bruno (id 22)
UNLOCK TABLES;

-- Inserção de dados na tabela disciplinas
use sistema_esc;
SELECT tipo FROM usuarios WHERE id = 8;

-- Inserção de dados na tabela notas
LOCK TABLES notas WRITE;
INSERT INTO notas (nota, dataL, fk_aluno, fk_prof, fk_disc) VALUES 
-- Aluno 1 (Helder)
(8.5, '2024-03-05', 1, 1, 1),
(7.0, '2024-03-12', 1, 1, 2),
(9.0, '2024-03-20', 1, 1, 3),
(6.5, '2024-03-08', 1, 1, 4),
(8.0, '2024-06-05', 1, 2, 5),
(7.5, '2024-06-12', 1, 2, 6),
(8.2, '2024-06-20', 1, 2, 7),
(7.8, '2024-06-08', 1, 2, 8),

-- Aluno 2 (Matheus)
(8.7, '2024-03-06', 2, 1, 1),
(7.3, '2024-03-13', 2, 1, 2),
(9.1, '2024-03-21', 2, 1, 3),
(6.8, '2024-03-09', 2, 1, 4),
(7.5, '2024-06-06', 2, 2, 5),
(8.1, '2024-06-13', 2, 2, 6),
(7.9, '2024-06-21', 2, 2, 7),
(7.4, '2024-06-09', 2, 2, 8),

-- Aluno 3 (Ari)
(8.4, '2024-03-07', 3, 1, 1), 
(7.6, '2024-03-14', 3, 1, 2),
(9.2, '2024-03-22', 3, 1, 3),
(6.9, '2024-03-10', 3, 1, 4),
(8.3, '2024-06-07', 3, 2, 5), 
(7.7, '2024-06-14', 3, 2, 6), 
(8.0, '2024-06-22', 3, 2, 7), 
(7.6, '2024-06-10', 3, 2, 8),

-- Aluno 4 (Giovanna)
(9.0, '2024-03-08', 4, 1, 1),
(7.9, '2024-03-15', 4, 1, 2), 
(9.3, '2024-03-23', 4, 1, 3), 
(6.7, '2024-03-11', 4, 1, 4),
(7.6, '2024-06-08', 4, 2, 5),
(8.2, '2024-06-15', 4, 2, 6),
(8.1, '2024-06-23', 4, 2, 7), 
(7.9, '2024-06-11', 4, 2, 8), 

-- Aluno 5 (Gillis)
(8.6, '2024-03-09', 5, 1, 1), 
(7.4, '2024-03-16', 5, 1, 2), 
(9.4, '2024-03-24', 5, 1, 3),
(6.6, '2024-03-12', 5, 1, 4), 
(7.8, '2024-06-09', 5, 2, 5), 
(8.3, '2024-06-16', 5, 2, 6), 
(8.0, '2024-06-24', 5, 2, 7), 
(7.5, '2024-06-12', 5, 2, 8), 

-- Aluno 6 (William)
(8.8, '2024-03-10', 6, 1, 1), 
(7.1, '2024-03-17', 6, 1, 2), 
(9.5, '2024-03-25', 6, 1, 3), 
(6.4, '2024-03-13', 6, 1, 4), 
(8.0, '2024-06-10', 6, 2, 5), 
(8.4, '2024-06-17', 6, 2, 6),
(8.1, '2024-06-25', 6, 2, 7), 
(7.7, '2024-06-13', 6, 2, 8), 

-- Aluno 7 (Lucas)
(7.6, '2024-03-11', 7, 1, 1), 
(6.0, '2024-03-18', 7, 1, 2), 
(8.3, '2024-03-26', 7, 1, 3), 
(5.0, '2024-03-14', 7, 1, 4), 
(5.9, '2024-06-11', 7, 2, 5), 
(10.0, '2024-06-18', 7, 2, 6), 
(7.3, '2024-06-26', 7, 2, 7), 
(6.0, '2024-06-14', 7, 2, 8), 

-- Aluno 8 (Caio)
(10.0, '2024-03-12', 8, 1, 1), 
(0.0, '2024-03-19', 8, 1, 2), 
(0.0, '2024-03-27', 8, 1, 3), 
(8.0, '2024-03-15', 8, 1, 4),
(0.0, '2024-06-12', 8, 2, 5), 
(0.0, '2024-06-19', 8, 2, 6), 
(0.0, '2024-06-27', 8, 2, 7), 
(7.0, '2024-06-15', 8, 2, 8), 

-- Aluno 9 (Alex)
(10.0, '2024-03-13', 9, 1, 1), 
(0.0, '2024-03-20', 9, 1, 2), 
(10.0, '2024-03-28', 9, 1, 3), 
(0.0, '2024-03-16', 9, 1, 4), 
(0.0, '2024-06-13', 9, 2, 5), 
(10.0, '2024-06-20', 9, 2, 6), 
(0.0, '2024-06-28', 9, 2, 7), 
(10.0, '2024-06-16', 9, 2, 8),

-- Aluno 10 (Pedro)
(8.5, '2024-03-14', 10, 1, 1), 
(7.0, '2024-03-21', 10, 1, 2), 
(9.0, '2024-03-29', 10, 1, 3), 
(6.5, '2024-03-17', 10, 1, 4), 
(8.0, '2024-06-14', 10, 2, 5), 
(7.5, '2024-06-21', 10, 2, 6), 
(8.0, '2024-06-29', 10, 2, 7), 
(7.0, '2024-06-17', 10, 2, 8),

-- Aluno 11 (Gabriel)
(8.2, '2024-03-15', 11, 1, 1),
(7.3, '2024-03-22', 11, 1, 2),
(8.9, '2024-03-30', 11, 1, 3),
(6.7, '2024-03-18', 11, 1, 4), 
(7.9, '2024-06-15', 11, 2, 5), 
(8.3, '2024-06-22', 11, 2, 6), 
(7.8, '2024-06-30', 11, 2, 7), 
(7.2, '2024-06-18', 11, 2, 8),

-- Aluno 12 (Rafael)
(9.1, '2024-03-16', 12, 1, 1),
(8.1, '2024-03-23', 12, 1, 2),
(9.3, '2024-03-31', 12, 1, 3), 
(7.3, '2024-03-19', 12, 1, 4),
(8.5, '2024-06-16', 12, 2, 5),
(8.8, '2024-06-23', 12, 2, 6), 
(8.2, '2024-07-01', 12, 2, 7),
(7.7, '2024-06-19', 12, 2, 8),

-- Aluno 13 (Felipe)
(7.8, '2024-03-17', 13, 1, 1),
(6.9, '2024-03-24', 13, 1, 2),
(8.5, '2024-04-01', 13, 1, 3), 
(6.1, '2024-03-20', 13, 1, 4), 
(7.7, '2024-06-17', 13, 2, 5), 
(8.1, '2024-06-24', 13, 2, 6),
(7.6, '2024-07-02', 13, 2, 7),
(7.0, '2024-06-20', 13, 2, 8),

-- Aluno 14 (gabriele)
(8.9, '2024-03-18', 14, 1, 1),
(8.0, '2024-03-25', 14, 1, 2),
(9.2, '2024-04-02', 14, 1, 3),
(7.2, '2024-03-21', 14, 1, 4),
(8.6, '2024-06-18', 14, 2, 5), 
(8.9, '2024-06-25', 14, 2, 6), 
(8.3, '2024-07-03', 14, 2, 7), 
(7.8, '2024-06-21', 14, 2, 8),

-- Aluno 15 (Marcos)
(7.5, '2024-03-19', 15, 1, 1), 
(6.7, '2024-03-26', 15, 1, 2), 
(8.3, '2024-04-03', 15, 1, 3),
(5.9, '2024-03-22', 15, 1, 4), 
(7.6, '2024-06-19', 15, 2, 5), 
(8.0, '2024-06-26', 15, 2, 6), 
(7.5, '2024-07-04', 15, 2, 7), 
(6.9, '2024-06-22', 15, 2, 8),

-- Aluno 16 (Leonardo)

(8.7, '2024-03-20', 16, 1, 1),
(7.5, '2024-03-27', 16, 1, 2), 
(9.0, '2024-04-04', 16, 1, 3),
(6.8, '2024-03-23', 16, 1, 4),
(8.4, '2024-06-20', 16, 2, 5), 
(8.7, '2024-06-27', 16, 2, 6), 
(8.1, '2024-07-05', 16, 2, 7), 
(7.6, '2024-06-23', 16, 2, 8), 

-- Aluno 17 (Thiago)
(9.2, '2024-03-21', 17, 1, 1),
(8.3, '2024-03-28', 17, 1, 2),
(9.4, '2024-04-05', 17, 1, 3),
(7.6, '2024-03-24', 17, 1, 4), 
(8.8, '2024-06-21', 17, 2, 5),
(9.1, '2024-06-28', 17, 2, 6),
(8.5, '2024-07-06', 17, 2, 7),
(8.0, '2024-06-24', 17, 2, 8), 

-- Aluno 18 (Yasmim)
(7.9, '2024-03-22', 18, 1, 1),
(7.1, '2024-03-29', 18, 1, 2),
(8.7, '2024-04-06', 18, 1, 3), 
(6.4, '2024-03-25', 18, 1, 4), 
(8.0, '2024-06-22', 18, 2, 5), 
(8.4, '2024-06-29', 18, 2, 6), 
(7.9, '2024-07-07', 18, 2, 7), 
(7.3, '2024-06-25', 18, 2, 8),

-- Aluno 19 (Daniel)
(8.3, '2024-03-23', 19, 1, 1), 
(7.6, '2024-03-30', 19, 1, 2), 
(8.9, '2024-04-07', 19, 1, 3), 
(6.9, '2024-03-26', 19, 1, 4), 
(8.3, '2024-06-23', 19, 2, 5), 
(8.7, '2024-06-30', 19, 2, 6), 
(8.2, '2024-07-08', 19, 2, 7), 
(7.7, '2024-06-26', 19, 2, 8),

-- Aluno 20 (Bruno)
(8.0, '2024-03-24', 20, 1, 1),
(7.3, '2024-03-31', 20, 1, 2),
(8.8, '2024-04-08', 20, 1, 3),
(6.6, '2024-03-27', 20, 1, 4), 
(8.2, '2024-06-24', 20, 2, 5), 
(8.6, '2024-07-01', 20, 2, 6),
(8.1, '2024-07-09', 20, 2, 7),
(7.6, '2024-06-27', 20, 2, 8);


UNLOCK TABLES;
INSERT INTO turmas (id, nome) VALUES
(1, 'Turma A'), 
(2, 'Turma B'), 
(3, 'Turma C'), 
(4, 'Turma D'); 

select * from turmas;


-- Disciplinas 1 a 4 para professor 1
INSERT INTO prof_disc_turma (fk_prof, fk_disc, fk_turma) VALUES

(1, 1, 1), (1, 1, 2), (1, 1, 3), (1, 1, 4),
(1, 2, 1), (1, 2, 2), (1, 2, 3), (1, 2, 4),
(1, 3, 1), (1, 3, 2), (1, 3, 3), (1, 3, 4),
(1, 4, 1), (1, 4, 2), (1, 4, 3), (1, 4, 4),

-- Disciplinas 5 a 8 para professor 2 e 9 para prof 3
(2, 5, 1), (2, 5, 2), (2, 5, 3), (2, 5, 4),
(2, 6, 1), (2, 6, 2), (2, 6, 3), (2, 6, 4),
(2, 7, 1), (2, 7, 2), (2, 7, 3), (2, 7, 4),
(2, 8, 1), (2, 8, 2), (2, 8, 3), (2, 8, 4),

(3, 9, 1), (3, 9, 2), (3, 9, 3), (3, 9, 4);


/* -- Inserção de alunos nas turmas dividindo varios alunos em varias turmas
INSERT INTO turma_alunos (fk_turma, fk_aluno) VALUES 
-- Alunos nas turmas do Professor Nicholas (Turmas A e B)
-- Turma A (10 alunos)
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5), 
(1, 6), (1, 7), (1, 8), (1, 9), (1, 10),

-- Turma B (10 alunos)
(2, 11), (2, 12), (2, 13), (2, 14), (2, 15),
(2, 16), (2, 17), (2, 18), (2, 19), (2, 20),

-- Alunos nas turmas do Professor Jhonta (Turmas C e D)
-- Turma C (10 alunos - mesmos alunos da Turma A)
(3, 1), (3, 2), (3, 3), (3, 4), (3, 5),
(3, 6), (3, 7), (3, 8), (3, 9), (3, 10),

-- Turma D (10 alunos - mesmos alunos da Turma B)
(4, 11), (4, 12), (4, 13), (4, 14), (4, 15),
(4, 16), (4, 17), (4, 18), (4, 19), (4, 20);
*/

-- Inserção de alunos nas turmas dividindo alunos em uma turma somente
INSERT INTO turma_alunos (fk_turma, fk_aluno) VALUES 
-- Alunos nas turmas do Professor Nicholas (Turmas A e B)
-- Turma A (5 alunos)
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5),

-- Turma B (5 alunos)
(2, 6), (2, 7), (2, 8), (2, 9), (2, 10),

-- Turma C (5 alunos)
(3, 11), (3, 12), (3, 13), (3, 14), (3, 15),

-- Turma D (5 alunos)
(4, 16), (4, 17), (4, 18), (4, 19), (4, 20);

