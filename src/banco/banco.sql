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

-- 25 PERGUNTAS --

INSERT INTO KNW_PERGUNTA (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("Como aprender PHP de forma eficiente?", "Quero começar a estudar PHP, mas não sei por onde começar. Alguém tem dicas de recursos ou métodos de estudo?", "Davibala");
INSERT INTO KNW_PERGUNTA (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("Qual a diferença entre JavaScript e TypeScript?", "Estou começando a estudar desenvolvimento web e vi que existem JavaScript e TypeScript. Qual é a diferença entre eles e quando devo usar cada um?", "Davibala");
INSERT INTO KNW_PERGUNTA (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("Como configurar um ambiente de desenvolvimento para Python?", "Preciso configurar um ambiente para desenvolver em Python. Quais ferramentas e bibliotecas são essenciais para começar?", "Davibala");
INSERT INTO KNW_PERGUNTA (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("O que é um banco de dados relacional?", "Estou estudando banco de dados e ouvi falar sobre bancos relacionais. Alguém pode explicar o que são e como funcionam?", "Davibala");
INSERT INTO KNW_PERGUNTA (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("Como funciona o Git e GitHub?", "Quero aprender a usar Git e GitHub para versionamento de código. Alguém pode explicar os conceitos básicos e como começar?", "Davibala");
INSERT INTO KNW_PERGUNTA (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("Qual a melhor forma de aprender algoritmos?", "Estou com dificuldades em algoritmos e lógica de programação. Alguém tem dicas de como estudar e praticar?", "Davibala");
INSERT INTO KNW_PERGUNTA (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("Como criar uma API RESTful com Node.js?", "Quero criar uma API RESTful usando Node.js. Alguém pode me indicar um tutorial ou explicar os passos básicos?", "Davibala");
INSERT INTO KNW_PERGUNTA (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("O que é Docker e como usá-lo?", "Ouvi falar sobre Docker, mas não sei exatamente o que é ou como usá-lo. Alguém pode explicar e dar exemplos práticos?", "Davibala");
INSERT INTO KNW_PERGUNTA (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("Como fazer deploy de uma aplicação React?", "Preciso fazer o deploy de uma aplicação React, mas não sei por onde começar. Alguém pode me ajudar com os passos?", "Davibala");
INSERT INTO KNW_PERGUNTA (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("Qual a diferença entre HTTP e HTTPS?", "Estou estudando desenvolvimento web e quero entender a diferença entre HTTP e HTTPS. Por que o HTTPS é mais seguro?", "Davibala");
INSERT INTO KNW_PERGUNTA (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("Como otimizar o desempenho de um site?", "Meu site está lento e quero melhorar o desempenho. Quais são as melhores práticas para otimização?", "Davibala");
INSERT INTO KNW_PERGUNTA (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("O que é machine learning e como começar?", "Estou interessado em machine learning, mas não sei por onde começar. Alguém pode explicar os conceitos básicos e indicar recursos?", "Davibala");
INSERT INTO KNW_PERGUNTA (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("Como funciona o protocolo TCP/IP?", "Estou estudando redes de computadores e quero entender melhor o protocolo TCP/IP. Alguém pode explicar?", "Davibala");
INSERT INTO KNW_PERGUNTA (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("Qual a diferença entre SQL e NoSQL?", "Estou estudando banco de dados e quero entender a diferença entre SQL e NoSQL. Quando devo usar cada um?", "Davibala");
INSERT INTO KNW_PERGUNTA (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("Como criar um chatbot usando Python?", "Quero criar um chatbot simples usando Python. Alguém pode me indicar bibliotecas ou tutoriais para começar?", "Davibala");
INSERT INTO KNW_PERGUNTA (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("O que é um framework e por que usar?", "Estou começando a programar e ouvi falar sobre frameworks. O que são e por que devo usá-los?", "Davibala");
INSERT INTO KNW_PERGUNTA (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("Como funciona a autenticação JWT?", "Quero implementar autenticação JWT no meu projeto, mas não sei como funciona. Alguém pode explicar?", "Davibala");
INSERT INTO KNW_PERGUNTA (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("Qual a melhor forma de aprender desenvolvimento mobile?", "Quero aprender desenvolvimento mobile, mas não sei por onde começar. Alguém tem dicas de recursos ou linguagens?", "Davibala");
INSERT INTO KNW_PERGUNTA (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("Como usar o Bootstrap para criar layouts responsivos?", "Estou começando a usar Bootstrap e quero criar layouts responsivos. Alguém pode me ajudar com exemplos práticos?", "Davibala");
INSERT INTO KNW_PERGUNTA (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("O que é CI/CD e como implementar?", "Ouvi falar sobre CI/CD, mas não sei como implementar. Alguém pode explicar os conceitos e dar exemplos?", "Davibala");
INSERT INTO knw_pergunta (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("Como estudar para provas de matemática?", "Estou com dificuldades para estudar para as provas de matemática. Alguém tem dicas de como organizar os estudos e quais tópicos priorizar?", "Davibala");
INSERT INTO knw_pergunta (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("Qual a melhor forma de fazer um resumo?", "Preciso fazer resumos para as aulas de história, mas não sei por onde começar. Alguém pode me dar dicas de como fazer resumos eficientes?", "Davibala");
INSERT INTO knw_pergunta (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("Como melhorar minha redação?", "Sempre tiro notas baixas em redação. Quais são as melhores técnicas para melhorar a escrita e a argumentação?", "Davibala");
INSERT INTO knw_pergunta (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("Qual a importância da física no dia a dia?", "Não entendo por que precisamos estudar física. Alguém pode me explicar como a física é aplicada no nosso cotidiano?", "Davibala");
INSERT INTO knw_pergunta (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("Como lidar com a pressão dos vestibulares?", "Estou me sentindo muito pressionado com os vestibulares. Alguém tem dicas de como lidar com o estresse e se preparar melhor?", "Davibala");
INSERT INTO knw_pergunta (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES ("Qual a melhor linguagem para iniciantes em programação?", "Quero começar a programar, mas não sei qual linguagem escolher. Alguém pode me recomendar uma linguagem fácil para iniciantes?", "Davibala");