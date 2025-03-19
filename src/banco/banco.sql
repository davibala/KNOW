CREATE DATABASE KNOW;
USE KNOW;

CREATE TABLE KNW_TAGS (
    TAG_ID INT NOT NULL AUTO_INCREMENT,
    TAG_NOME VARCHAR(45) NOT NULL,
    PRIMARY KEY (TAG_ID)
);

CREATE TABLE KNW_USUARIOS (
    USU_NOME VARCHAR(45) NOT NULL,
    USU_EMAIL VARCHAR(45) NOT NULL,
    USU_SENHA VARCHAR(60) NOT NULL,
    PRIMARY KEY (USU_NOME)
);

CREATE TABLE KNW_DENUNCIA (
    DEN_ID INT NOT NULL AUTO_INCREMENT,
    DEN_DESCRICAO VARCHAR(250) NOT NULL,
    DEN_USU_NOME VARCHAR(45) NOT NULL,
    PRIMARY KEY (DEN_ID),
    CONSTRAINT DEN_USU_NOME
        FOREIGN KEY (DEN_USU_NOME)
        REFERENCES KNW_USUARIOS (USU_NOME)
        ON UPDATE CASCADE
);

CREATE TABLE KNW_PERGUNTA (
    PER_ID INT AUTO_INCREMENT,
    PER_TITULO VARCHAR(255) NOT NULL,
    PER_DESCRICAO TEXT,
    PER_DATA TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PER_USU_NOME VARCHAR(45) NOT NULL,
    PER_DEN_ID INT NULL,
    PRIMARY KEY (PER_ID),
    CONSTRAINT PER_USU_NOME
        FOREIGN KEY (PER_USU_NOME)
        REFERENCES KNW_USUARIOS (USU_NOME)
        ON UPDATE CASCADE,
    CONSTRAINT PER_DEN_ID
        FOREIGN KEY (PER_DEN_ID)
        REFERENCES KNW_DENUNCIA (DEN_ID)
);

CREATE TABLE KNW_FEEDBACK (
    FDB_ID INT NOT NULL AUTO_INCREMENT,
    FDB_DESCRICAO VARCHAR(250) NOT NULL,
    FDB_USU_NOME VARCHAR(45) NOT NULL,
    PRIMARY KEY (FDB_ID),
    CONSTRAINT FDB_USU_NOME
        FOREIGN KEY (FDB_USU_NOME)
        REFERENCES KNW_USUARIOS (USU_NOME)
        ON UPDATE CASCADE
);

CREATE TABLE KNW_RESPOSTA (
    RES_ID INT NOT NULL AUTO_INCREMENT,
    RES_DESCRICAO VARCHAR(250) NOT NULL,
    RES_PER_ID INT NOT NULL,
    RES_USU_NOME VARCHAR(45) NOT NULL,
    RES_FDB_ID INT NULL,
    RES_DEN_ID INT NULL,
    PRIMARY KEY (RES_ID),
    CONSTRAINT RES_FDB_ID
        FOREIGN KEY (RES_FDB_ID)
        REFERENCES KNW_FEEDBACK (FDB_ID),
    CONSTRAINT RES_PER_ID
        FOREIGN KEY (RES_PER_ID)
        REFERENCES KNW_PERGUNTA (PER_ID),
    CONSTRAINT RES_DEN_ID
        FOREIGN KEY (RES_DEN_ID)
        REFERENCES KNW_DENUNCIA (DEN_ID),
    CONSTRAINT RES_USU_NOME
        FOREIGN KEY (RES_USU_NOME)
        REFERENCES KNW_USUARIOS (USU_NOME)
        ON UPDATE CASCADE
);

CREATE TABLE PERGUNTA_TAGS (
    PER_ID INT NOT NULL,
    TAG_ID INT NOT NULL,
    PRIMARY KEY (PER_ID, TAG_ID),
    CONSTRAINT PER_ID
        FOREIGN KEY (PER_ID)
        REFERENCES KNW_PERGUNTA (PER_ID)
        ON DELETE CASCADE,
    CONSTRAINT TAG_ID
        FOREIGN KEY (TAG_ID)
        REFERENCES KNW_TAGS (TAG_ID)
        ON DELETE CASCADE
);

CREATE TABLE KNW_IMAGEM (
    IMG_ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    IMG_CAMINHO VARCHAR(250) NOT NULL,
    IMG_USU_NOME VARCHAR(45) NOT NULL,
    CONSTRAINT IMG_USU_NOME
        FOREIGN KEY (IMG_USU_NOME)
        REFERENCES KNW_USUARIOS (USU_NOME)
        ON UPDATE CASCADE
);

-- DADOS FAKES PARA TESTES --

-- 10 TAGS -- 
INSERT INTO KNW_TAGS (TAG_NOME) VALUES ('LP');
INSERT INTO KNW_TAGS (TAG_NOME) VALUES ('RED');
INSERT INTO KNW_TAGS (TAG_NOME) VALUES ('MAT');
INSERT INTO KNW_TAGS (TAG_NOME) VALUES ('PORT');
INSERT INTO KNW_TAGS (TAG_NOME) VALUES ('ING');
INSERT INTO KNW_TAGS (TAG_NOME) VALUES ('POO');
INSERT INTO KNW_TAGS (TAG_NOME) VALUES ('WEB');
INSERT INTO KNW_TAGS (TAG_NOME) VALUES ('PROJ');
INSERT INTO KNW_TAGS (TAG_NOME) VALUES ('FUND');
INSERT INTO KNW_TAGS (TAG_NOME) VALUES ('BD');

-- USUARIOS --

INSERT INTO `knw_usuarios` VALUES 
('alexandres','alexandres@gmail.com','$2y$10$vJWHLMrd3IJ27x//a.qtA.SVQ/PjHIcZ5UA9AZaB0J4sPOGQkFgE6'),
('bela','bela@gmail.com','$2y$10$dPMzc8okUViWf7tAxg/V5.na/lbjDBqxjG0XRxywMEfiQ1xkP.0ja'),
('beto','beto@gmail.com','$2y$10$uHNrcGlApVKPwnjKToveXumhlrVbKukLL9NJxHGcI3aEscix0ybYy'),
('brkn','brkn@gmail.com','$2y$10$ATYMsconryqTOqEWowhoYu/SSFdUMRdciMKwlIdXlC/rKK/mlGTYy'),
('chulipa','chulipa@gmail.com','$2y$10$A6YtOk0rgFBjwY6TB25FpeXuPoLILfBoeq0MyciCTOqB18/Pd7ZhS'),
('fael','fael@gmail.com','$2y$10$gx8a60CIVN5XPnm6uS2rdeDZQxA430ygggIMpUjub3b/6yD4XT9ou'),
('jao','jao@gmail.com','$2y$10$Ed8Dy0o8BSJgd6IYlh4KIOuV9z7UcSkvHaf87N5opIJx.NAKfee6i'),
('kauanmunista','kauanmunista@gmail.com','$2y$10$Xst/Adhe2NWAl7uot8g17elSDLUsiafuP3HRx.V6zx3xXtAz97Ch6'),
('lilias','lilias@gmail.com','$2y$10$.MtNFe1ZvNxwFMvDlu9tluLEZP.tCh324Mmm4lvYgVEYi0wseJl1i'),
('maisena','maisena@gmail.com','$2y$10$jf8w/CoEdkUtU7ywpbSYT.hVSsuAOGgwWpDV8v3DZmUUVFU6/3K5q'),
('mantegavoadora','mantegavoadora@gmail.com','$2y$10$k2bJ7HZyl7oZA7QFcQZZZOcXsREiokQCzpc1DFhzzxnf8Z88o4Bhy'),
('rntboiada','rntboiada@gmail.com','$2y$10$A9D//DVMIYlOsGU4bwgkc.a8cVHy5qnUyZYGPACXdzW2lkOtr2FHa'),
('xavinho','xavinho@gmail.com','$2y$10$r7gZFpgeAocfas/CJYDtu.KosEB/KrWLPU492II2k1ASZ7gbEcTPq'),
('shacolate','shacolate@gmail.com','$2y$10$uJBuSOpeM3AZbELI0WG4zOyuuVgOHGJQRP9LZ10.0JeuwNbrUYhai'),
('yassuo','yassuo@gmail.com','$2y$10$pNfe9T6JlnzHQYlWVtF7l.ZU/2s.Ls1WTSBm26h8UEyr2VkMgmkHm');

-- ALGUMAS PERGUNTAS --

INSERT INTO KNW_PERGUNTA (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("Como a lógica de programação pode ajudar na otimização de consultas SQL?", "Estou tentando otimizar consultas em um banco de dados relacional e percebi que a lógica de programação é essencial. Alguém pode me explicar como esses conceitos se relacionam?", "brkn");
INSERT INTO KNW_PERGUNTA (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("Qual a relação entre matemática discreta e estruturas de dados em POO?", "Estou estudando programação orientada a objetos e percebi que muitos conceitos de matemática discreta são aplicados em estruturas de dados. Alguém pode me explicar essa relação?", "yassuo");
INSERT INTO KNW_PERGUNTA (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("Como o design web pode impactar a experiência do usuário em aplicações que usam banco de dados?", "Estou desenvolvendo uma aplicação web que usa banco de dados e queria saber como o design web pode melhorar a experiência do usuário. Alguém tem dicas?", "shacolate");
INSERT INTO KNW_PERGUNTA (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("Qual a importância do inglês técnico para entender documentações de redes e protocolos?", "Estou estudando redes e percebi que muitas documentações técnicas estão em inglês. Alguém pode me explicar como o inglês técnico pode ajudar nessa área?", "mantegavoadora");
INSERT INTO KNW_PERGUNTA (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("Como a matemática é aplicada no desenvolvimento de algoritmos para redes?", "Estou estudando redes e algoritmos, e percebi que a matemática é fundamental. Alguém pode me explicar como esses conceitos se conectam?", "rntboiada");
INSERT INTO KNW_PERGUNTA (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("Qual a importância da gramática na documentação de projetos de software?", "Estou documentando um projeto de software e queria saber como a gramática pode impactar a clareza e a qualidade da documentação. Alguém tem dicas?", "kauanmunista");
INSERT INTO KNW_PERGUNTA (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("Como a programação orientada a objetos pode ser aplicada no desenvolvimento de interfaces web responsivas?", "Estou criando uma interface web responsiva e queria saber como os conceitos de POO podem ajudar nesse processo. Alguém pode me explicar?", "lilias");
INSERT INTO KNW_PERGUNTA (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("Qual a relação entre fundamentos da informática e a segurança em banco de dados?", "Estou estudando segurança em banco de dados e percebi que muitos conceitos de fundamentos da informática são importantes. Alguém pode me explicar essa relação?", "alexandres");
INSERT INTO KNW_PERGUNTA (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("Como a lógica de programação pode ser usada para resolver problemas matemáticos em projetos de software?", "Estou desenvolvendo um software que resolve problemas matemáticos e queria saber como a lógica de programação pode ser aplicada. Alguém tem exemplos?", "xavinho");
INSERT INTO KNW_PERGUNTA (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("Qual a importância do inglês técnico para entender frameworks de design web?", "Estou aprendendo a usar frameworks de design web, mas muitas documentações estão em inglês. Alguém pode me explicar como o inglês técnico pode ajudar?", "maisena");
INSERT INTO KNW_PERGUNTA (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("Como a matemática discreta é aplicada no desenvolvimento de algoritmos para banco de dados?", "Estou estudando algoritmos para banco de dados e percebi que a matemática discreta é fundamental. Alguém pode me explicar como esses conceitos se relacionam?", "chulipa");
INSERT INTO KNW_PERGUNTA (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("Qual a relação entre redes e banco de dados no desenvolvimento de aplicações web?", "Estou desenvolvendo uma aplicação web e queria entender como redes e banco de dados se conectam. Alguém pode me explicar?", "jao");
INSERT INTO KNW_PERGUNTA (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("Como a programação orientada a objetos pode ser usada para organizar projetos de software?", "Estou gerenciando um projeto de software e queria saber como a POO pode ajudar na organização do código. Alguém tem dicas?", "fael");
INSERT INTO KNW_PERGUNTA (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("Qual a importância do inglês técnico para entender documentações de frameworks de POO?", "Estou estudando frameworks de POO, mas muitas documentações estão em inglês. Alguém pode me explicar como o inglês técnico pode ajudar?", "beto");
INSERT INTO KNW_PERGUNTA (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("Como a matemática é aplicada no desenvolvimento de algoritmos para redes de computadores?", "Estou estudando algoritmos para redes e percebi que a matemática é essencial. Alguém pode me explicar como esses conceitos se conectam?", "bela");


-- TAGS ASSOCIADAS AS PERGUNTAS --

insert into pergunta_tags (per_id, tag_id) values (1,1);
insert into pergunta_tags (per_id, tag_id) values (1,10);

insert into pergunta_tags (per_id, tag_id) values (2,3);
insert into pergunta_tags (per_id, tag_id) values (2,6);

insert into pergunta_tags (per_id, tag_id) values (3,7);
insert into pergunta_tags (per_id, tag_id) values (3,10);

insert into pergunta_tags (per_id, tag_id) values (4,2);
insert into pergunta_tags (per_id, tag_id) values (4,5);

insert into pergunta_tags (per_id, tag_id) values (5,1);
insert into pergunta_tags (per_id, tag_id) values (5,3);
insert into pergunta_tags (per_id, tag_id) values (5,10);

insert into pergunta_tags (per_id, tag_id) values (6,2);
insert into pergunta_tags (per_id, tag_id) values (6,7);
insert into pergunta_tags (per_id, tag_id) values (6,10);

insert into pergunta_tags (per_id, tag_id) values (7,6);
insert into pergunta_tags (per_id, tag_id) values (7,8);

insert into pergunta_tags (per_id, tag_id) values (8,5);
insert into pergunta_tags (per_id, tag_id) values (8,6);

insert into pergunta_tags (per_id, tag_id) values (9,1);
insert into pergunta_tags (per_id, tag_id) values (9,2);
insert into pergunta_tags (per_id, tag_id) values (9,3);