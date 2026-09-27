-- ============================================================
-- SISTEMA DE GESTÃO DE ESPECIALISTAS
-- BASE DE DADOS V3

CREATE DATABASE IF NOT EXISTS sistema_especialistas_v3
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sistema_especialistas_v3;

SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS notificacoes, solicitacoes, documentos, fotografias, ferias,
condecoracoes, habilitacoes_literarias, formacoes, dados_servico, familiares,
dados_pessoais, equipamentos, especialistas, utilizador_unidade, utilizador_ramo,
perfil_permissoes, permissoes, utilizadores, perfis, configuracoes_alertas,
tipos_alerta, modelos_solicitacao, tipos_condecoracao, estados_equipamento,
tipos_equipamento, tipos_documento, niveis_habilitacao, tipos_formacao,
instituicoes, graus_parentesco, situacoes_servico, patentes, funcoes,
departamentos, unidades, municipios, provincias, paises, quadros_servico,
sexos, estados_civis, ramo;
SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE ramo (
 id_ramo INT AUTO_INCREMENT PRIMARY KEY, nome VARCHAR(150) NOT NULL,
 sigla VARCHAR(30) NOT NULL UNIQUE, descricao TEXT, logo VARCHAR(500),
 imagem_fundo VARCHAR(500), cor_principal VARCHAR(20), cor_secundaria VARCHAR(20),
 estado ENUM('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
 data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 INDEX idx_ramo_estado (estado)
) ENGINE=InnoDB;

CREATE TABLE perfis (
 id_perfil INT AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(100) NOT NULL UNIQUE,
 descricao TEXT,
 nivel_acesso ENUM('GLOBAL','RAMO','UNIDADE') NOT NULL,
 estado ENUM('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
 data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE permissoes (
 id_permissao INT AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(100) NOT NULL UNIQUE,
 modulo VARCHAR(100) NOT NULL,
 descricao TEXT,
 estado ENUM('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
 data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE utilizadores (
 id_utilizador INT AUTO_INCREMENT PRIMARY KEY,
 nome_utilizador VARCHAR(100) NOT NULL UNIQUE,
 senha VARCHAR(255) NOT NULL,
 email VARCHAR(150) UNIQUE,
 id_perfil INT NOT NULL,
 foto VARCHAR(500) NULL,
 tema ENUM('CLARO','ESCURO') NOT NULL DEFAULT 'CLARO',
 estado ENUM('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
 data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 data_atualizacao DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
 ultimo_acesso DATETIME NULL,
 FOREIGN KEY (id_perfil) REFERENCES perfis(id_perfil) ON UPDATE CASCADE ON DELETE RESTRICT,
 INDEX idx_utilizadores_perfil (id_perfil),
 INDEX idx_utilizadores_estado (estado)
) ENGINE=InnoDB;

CREATE TABLE perfil_permissoes (
 id_perfil INT NOT NULL, id_permissao INT NOT NULL,
 PRIMARY KEY(id_perfil,id_permissao),
 FOREIGN KEY(id_perfil) REFERENCES perfis(id_perfil) ON DELETE CASCADE,
 FOREIGN KEY(id_permissao) REFERENCES permissoes(id_permissao) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE utilizador_ramo (
 id_utilizador INT NOT NULL, id_ramo INT NOT NULL,
 PRIMARY KEY(id_utilizador,id_ramo),
 FOREIGN KEY(id_utilizador) REFERENCES utilizadores(id_utilizador) ON DELETE CASCADE,
 FOREIGN KEY(id_ramo) REFERENCES ramo(id_ramo) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE paises (
 id_pais INT AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(150) NOT NULL UNIQUE,
 codigo VARCHAR(10) UNIQUE,
 nacionalidade VARCHAR(100),
 estado ENUM('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
 data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE provincias (
 id_provincia INT AUTO_INCREMENT PRIMARY KEY,
 id_pais INT NOT NULL,
 nome VARCHAR(150) NOT NULL,
 estado ENUM('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
 data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 UNIQUE(id_pais,nome),
 FOREIGN KEY(id_pais) REFERENCES paises(id_pais) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE municipios (
 id_municipio INT AUTO_INCREMENT PRIMARY KEY,
 id_provincia INT NOT NULL,
 nome VARCHAR(150) NOT NULL,
 estado ENUM('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
 data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 UNIQUE(id_provincia,nome),
 FOREIGN KEY(id_provincia) REFERENCES provincias(id_provincia) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE unidades (
 id_unidade INT AUTO_INCREMENT PRIMARY KEY,
 id_ramo INT NOT NULL,
 nome VARCHAR(200) NOT NULL,
 sigla VARCHAR(50),
 localizacao VARCHAR(200),
 descricao TEXT,
 estado ENUM('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
 data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 UNIQUE(id_ramo,nome),
 FOREIGN KEY(id_ramo) REFERENCES ramo(id_ramo) ON DELETE RESTRICT,
 INDEX idx_unidades_ramo (id_ramo)
) ENGINE=InnoDB;

CREATE TABLE departamentos (
 id_departamento INT AUTO_INCREMENT PRIMARY KEY,
 id_unidade INT NOT NULL,
 nome VARCHAR(200) NOT NULL,
 sigla VARCHAR(50),
 descricao TEXT,
 estado ENUM('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
 data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 UNIQUE(id_unidade,nome),
 FOREIGN KEY(id_unidade) REFERENCES unidades(id_unidade) ON DELETE RESTRICT,
 INDEX idx_departamentos_unidade (id_unidade)
) ENGINE=InnoDB;

CREATE TABLE funcoes (
 id_funcao INT AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(200) NOT NULL UNIQUE,
 descricao TEXT,
 estado ENUM('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
 data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE patentes (
 id_patente INT AUTO_INCREMENT PRIMARY KEY,
 id_ramo INT NOT NULL,
 nome VARCHAR(150) NOT NULL,
 sigla VARCHAR(50),
 ordem INT NOT NULL DEFAULT 0,
 descricao TEXT,
 estado ENUM('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
 data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 UNIQUE(id_ramo,nome),
 UNIQUE KEY uq_patente_ramo_id (id_ramo,id_patente),
 FOREIGN KEY(id_ramo) REFERENCES ramo(id_ramo) ON DELETE RESTRICT,
 INDEX idx_patentes_ramo_ordem (id_ramo,ordem)
) ENGINE=InnoDB;

CREATE TABLE quadros_servico (
 id_quadro INT AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(150) NOT NULL UNIQUE,
 sigla VARCHAR(30),
 descricao TEXT,
 estado ENUM('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
 data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE sexos (
 id_sexo INT AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(50) NOT NULL UNIQUE,
 estado ENUM('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
 data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE estados_civis (
 id_estado_civil INT AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(100) NOT NULL UNIQUE,
 estado ENUM('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
 data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE situacoes_servico (
 id_situacao_servico INT AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(150) NOT NULL UNIQUE,
 grupo ENUM('ATIVO','INATIVO') NOT NULL,
 descricao TEXT, estado ENUM('ATIVO','INATIVO') DEFAULT 'ATIVO'
) ENGINE=InnoDB;

CREATE TABLE graus_parentesco (
 id_grau_parentesco INT AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(100) NOT NULL UNIQUE,
 descricao TEXT,
 estado ENUM('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
 data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE instituicoes (
 id_instituicao INT AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(200) NOT NULL UNIQUE,
 sigla VARCHAR(50), id_pais INT, localizacao VARCHAR(200), descricao TEXT,
 estado ENUM('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
 data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(id_pais) REFERENCES paises(id_pais) ON DELETE RESTRICT,
 INDEX idx_instituicoes_pais (id_pais)
) ENGINE=InnoDB;

CREATE TABLE tipos_formacao (
 id_tipo_formacao INT AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(150) NOT NULL UNIQUE,
 descricao TEXT,
 estado ENUM('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
 data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE niveis_habilitacao (
 id_nivel_habilitacao INT AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(150) NOT NULL UNIQUE,
 ordem INT NOT NULL DEFAULT 0,
 descricao TEXT,
 estado ENUM('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
 data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE tipos_documento (
 id_tipo_documento INT AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(150) NOT NULL UNIQUE,
 descricao TEXT,
 estado ENUM('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
 data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE tipos_equipamento (
 id_tipo_equipamento INT AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(150) NOT NULL UNIQUE,
 descricao TEXT,
 estado ENUM('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
 data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE estados_equipamento (
 id_estado_equipamento INT AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(150) NOT NULL UNIQUE,
 descricao TEXT,
 estado ENUM('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
 data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE tipos_condecoracao (
 id_tipo_condecoracao INT AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(200) NOT NULL UNIQUE,
 descricao TEXT,
 estado ENUM('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
 data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE tipos_alerta (
 id_tipo_alerta INT AUTO_INCREMENT PRIMARY KEY, nome VARCHAR(150) NOT NULL,
 codigo VARCHAR(100) NOT NULL UNIQUE, descricao TEXT,
 nivel_padrao ENUM('INFO','ATENCAO','URGENTE') DEFAULT 'INFO',
 dias_antecedencia INT DEFAULT 0, estado ENUM('ATIVO','INATIVO') DEFAULT 'ATIVO'
) ENGINE=InnoDB;

CREATE TABLE configuracoes_alertas (
 id_configuracao_alerta INT AUTO_INCREMENT PRIMARY KEY, id_tipo_alerta INT NOT NULL,
 id_ramo INT NULL, dias_antecedencia INT DEFAULT 0, ativo BOOLEAN DEFAULT TRUE,
 descricao TEXT, UNIQUE(id_tipo_alerta,id_ramo),
 FOREIGN KEY(id_tipo_alerta) REFERENCES tipos_alerta(id_tipo_alerta) ON DELETE CASCADE,
 FOREIGN KEY(id_ramo) REFERENCES ramo(id_ramo) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE modelos_solicitacao (
 id_modelo INT AUTO_INCREMENT PRIMARY KEY, nome VARCHAR(200) NOT NULL,
 codigo VARCHAR(100) NOT NULL UNIQUE, assunto VARCHAR(255), conteudo LONGTEXT,
 categoria VARCHAR(100), estado ENUM('ATIVO','INATIVO') DEFAULT 'ATIVO',
 data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
 data_atualizacao DATETIME NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE utilizador_unidade (
 id_utilizador INT NOT NULL, id_unidade INT NOT NULL,
 PRIMARY KEY(id_utilizador,id_unidade),
 FOREIGN KEY(id_utilizador) REFERENCES utilizadores(id_utilizador) ON DELETE CASCADE,
 FOREIGN KEY(id_unidade) REFERENCES unidades(id_unidade) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE especialistas (
 id_especialista INT AUTO_INCREMENT PRIMARY KEY,
 id_ramo INT NOT NULL,
 id_quadro INT NOT NULL,
 id_patente INT NULL,
 estado ENUM('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
 data_registo DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 observacoes TEXT,
 FOREIGN KEY(id_ramo) REFERENCES ramo(id_ramo) ON DELETE RESTRICT,
 FOREIGN KEY(id_quadro) REFERENCES quadros_servico(id_quadro) ON DELETE RESTRICT,
 FOREIGN KEY(id_patente) REFERENCES patentes(id_patente) ON DELETE RESTRICT,
 FOREIGN KEY(id_ramo,id_patente) REFERENCES patentes(id_ramo,id_patente) ON DELETE RESTRICT,
 INDEX idx_especialistas_ramo (id_ramo),
 INDEX idx_especialistas_quadro (id_quadro),
 INDEX idx_especialistas_patente (id_patente),
 INDEX idx_especialistas_estado (estado)
) ENGINE=InnoDB;

CREATE TABLE dados_pessoais (
 id_dados_pessoais INT AUTO_INCREMENT PRIMARY KEY, id_especialista INT NOT NULL UNIQUE,
 nome_completo VARCHAR(200) NOT NULL, data_nascimento DATE,
 id_sexo INT, id_pais_nascimento INT, id_provincia_nascimento INT,
 id_municipio_nascimento INT, id_pais_nacionalidade INT, id_estado_civil INT,
 numero_bi VARCHAR(50) UNIQUE, data_emissao_bi DATE, data_validade_bi DATE,
 local_emissao_bi VARCHAR(150), telefone VARCHAR(30), email VARCHAR(150), morada TEXT,
 FOREIGN KEY(id_especialista) REFERENCES especialistas(id_especialista) ON DELETE CASCADE,
 FOREIGN KEY(id_sexo) REFERENCES sexos(id_sexo) ON DELETE RESTRICT,
 FOREIGN KEY(id_pais_nascimento) REFERENCES paises(id_pais) ON DELETE RESTRICT,
 FOREIGN KEY(id_provincia_nascimento) REFERENCES provincias(id_provincia) ON DELETE RESTRICT,
 FOREIGN KEY(id_municipio_nascimento) REFERENCES municipios(id_municipio) ON DELETE RESTRICT,
 FOREIGN KEY(id_pais_nacionalidade) REFERENCES paises(id_pais) ON DELETE RESTRICT,
 FOREIGN KEY(id_estado_civil) REFERENCES estados_civis(id_estado_civil) ON DELETE RESTRICT,
 INDEX(nome_completo)
) ENGINE=InnoDB;

CREATE TABLE familiares (
 id_familiar INT AUTO_INCREMENT PRIMARY KEY, id_especialista INT NOT NULL,
 nome VARCHAR(200) NOT NULL, id_grau_parentesco INT, data_nascimento DATE,
 profissao VARCHAR(150), telefone VARCHAR(30), morada TEXT, observacoes TEXT,
 FOREIGN KEY(id_especialista) REFERENCES especialistas(id_especialista) ON DELETE CASCADE,
 FOREIGN KEY(id_grau_parentesco) REFERENCES graus_parentesco(id_grau_parentesco) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE dados_servico (
 id_dados_servico INT AUTO_INCREMENT PRIMARY KEY,
 id_especialista INT NOT NULL UNIQUE,
 nip VARCHAR(50) NOT NULL UNIQUE,
 numero_ordem VARCHAR(50),
 numero_processo VARCHAR(50),
 data_ingresso DATE,
 data_incorporacao DATE,
 data_promocao DATE,
 id_funcao INT,
 cargo VARCHAR(150),
 id_unidade INT,
 id_departamento INT,
 id_situacao_servico INT,
 data_inicio_funcao DATE,
 data_fim_funcao DATE,
 observacoes TEXT,
 FOREIGN KEY(id_especialista) REFERENCES especialistas(id_especialista) ON DELETE CASCADE,
 FOREIGN KEY(id_funcao) REFERENCES funcoes(id_funcao) ON DELETE RESTRICT,
 FOREIGN KEY(id_unidade) REFERENCES unidades(id_unidade) ON DELETE RESTRICT,
 FOREIGN KEY(id_departamento) REFERENCES departamentos(id_departamento) ON DELETE RESTRICT,
 FOREIGN KEY(id_situacao_servico) REFERENCES situacoes_servico(id_situacao_servico) ON DELETE RESTRICT,
 INDEX idx_dados_servico_unidade (id_unidade),
 INDEX idx_dados_servico_departamento (id_departamento),
 INDEX idx_dados_servico_funcao (id_funcao),
 INDEX idx_dados_servico_situacao (id_situacao_servico)
) ENGINE=InnoDB;

CREATE TABLE formacoes (
 id_formacao INT AUTO_INCREMENT PRIMARY KEY, id_especialista INT NOT NULL,
 categoria ENUM('MILITAR','GERAL') NOT NULL, id_tipo_formacao INT,
 designacao VARCHAR(200) NOT NULL, id_instituicao INT, id_pais INT,
 local VARCHAR(150), data_inicio DATE, data_fim DATE, duracao VARCHAR(100),
 certificado VARCHAR(100), observacoes TEXT,
 FOREIGN KEY(id_especialista) REFERENCES especialistas(id_especialista) ON DELETE CASCADE,
 FOREIGN KEY(id_tipo_formacao) REFERENCES tipos_formacao(id_tipo_formacao) ON DELETE RESTRICT,
 FOREIGN KEY(id_instituicao) REFERENCES instituicoes(id_instituicao) ON DELETE RESTRICT,
 FOREIGN KEY(id_pais) REFERENCES paises(id_pais) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE habilitacoes_literarias (
 id_habilitacao INT AUTO_INCREMENT PRIMARY KEY, id_especialista INT NOT NULL,
 categoria ENUM('MILITAR','GERAL') NOT NULL, id_nivel_habilitacao INT,
 curso VARCHAR(200), id_instituicao INT, id_pais INT,
 data_inicio DATE, data_conclusao DATE, numero_certificado VARCHAR(100), observacoes TEXT,
 FOREIGN KEY(id_especialista) REFERENCES especialistas(id_especialista) ON DELETE CASCADE,
 FOREIGN KEY(id_nivel_habilitacao) REFERENCES niveis_habilitacao(id_nivel_habilitacao) ON DELETE RESTRICT,
 FOREIGN KEY(id_instituicao) REFERENCES instituicoes(id_instituicao) ON DELETE RESTRICT,
 FOREIGN KEY(id_pais) REFERENCES paises(id_pais) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE condecoracoes (
 id_condecoracao INT AUTO_INCREMENT PRIMARY KEY, id_especialista INT NOT NULL,
 id_tipo_condecoracao INT, entidade VARCHAR(200), data_atribuicao DATE,
 motivo TEXT, numero_documento VARCHAR(100), observacoes TEXT,
 FOREIGN KEY(id_especialista) REFERENCES especialistas(id_especialista) ON DELETE CASCADE,
 FOREIGN KEY(id_tipo_condecoracao) REFERENCES tipos_condecoracao(id_tipo_condecoracao) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE ferias (
 id_ferias INT AUTO_INCREMENT PRIMARY KEY, id_especialista INT NOT NULL, ano YEAR NOT NULL,
 data_inicio DATE NOT NULL, data_fim DATE NOT NULL, numero_dias INT NOT NULL,
 estado ENUM('PROGRAMADA','EM_CURSO','CONCLUIDA','CANCELADA') DEFAULT 'PROGRAMADA',
 observacoes TEXT, data_registo DATETIME DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(id_especialista) REFERENCES especialistas(id_especialista) ON DELETE CASCADE,
 INDEX(data_inicio,data_fim,estado)
) ENGINE=InnoDB;

CREATE TABLE equipamentos (
 id_equipamento INT AUTO_INCREMENT PRIMARY KEY, id_ramo INT NOT NULL, id_unidade INT,
 id_tipo_equipamento INT, designacao VARCHAR(200) NOT NULL, marca VARCHAR(100),
 modelo VARCHAR(100), numero_serie VARCHAR(100) UNIQUE, numero_patrimonio VARCHAR(100),
 data_aquisicao DATE, id_estado_equipamento INT, localizacao VARCHAR(200),
 descricao TEXT, observacoes TEXT,
 FOREIGN KEY(id_ramo) REFERENCES ramo(id_ramo) ON DELETE RESTRICT,
 FOREIGN KEY(id_unidade) REFERENCES unidades(id_unidade) ON DELETE RESTRICT,
 FOREIGN KEY(id_tipo_equipamento) REFERENCES tipos_equipamento(id_tipo_equipamento) ON DELETE RESTRICT,
 FOREIGN KEY(id_estado_equipamento) REFERENCES estados_equipamento(id_estado_equipamento) ON DELETE RESTRICT,
 INDEX(id_ramo,id_unidade)
) ENGINE=InnoDB;

CREATE TABLE fotografias (
 id_fotografia INT AUTO_INCREMENT PRIMARY KEY, id_especialista INT NULL,
 id_equipamento INT NULL, nome_ficheiro VARCHAR(255) NOT NULL,
 caminho_ficheiro VARCHAR(500) NOT NULL, data_registo DATETIME DEFAULT CURRENT_TIMESTAMP,
 principal BOOLEAN DEFAULT FALSE, descricao TEXT,
 FOREIGN KEY(id_especialista) REFERENCES especialistas(id_especialista) ON DELETE CASCADE,
 FOREIGN KEY(id_equipamento) REFERENCES equipamentos(id_equipamento) ON DELETE CASCADE,
 INDEX idx_fotografias_especialista (id_especialista),
 INDEX idx_fotografias_equipamento (id_equipamento),
 CONSTRAINT chk_foto_owner CHECK(
  (id_especialista IS NOT NULL AND id_equipamento IS NULL) OR
  (id_especialista IS NULL AND id_equipamento IS NOT NULL)
 )
) ENGINE=InnoDB;

CREATE TABLE documentos (
 id_documento INT AUTO_INCREMENT PRIMARY KEY, id_especialista INT NULL,
 id_equipamento INT NULL, id_tipo_documento INT NOT NULL, titulo VARCHAR(200) NOT NULL,
 nome_ficheiro VARCHAR(255) NOT NULL, caminho_ficheiro VARCHAR(500) NOT NULL,
 data_documento DATE, data_validade DATE, descricao TEXT,
 data_registo DATETIME DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(id_especialista) REFERENCES especialistas(id_especialista) ON DELETE CASCADE,
 FOREIGN KEY(id_equipamento) REFERENCES equipamentos(id_equipamento) ON DELETE CASCADE,
 FOREIGN KEY(id_tipo_documento) REFERENCES tipos_documento(id_tipo_documento) ON DELETE RESTRICT,
 CONSTRAINT chk_doc_dates CHECK (data_validade IS NULL OR data_documento IS NULL OR data_validade >= data_documento),
 CONSTRAINT chk_doc_owner CHECK(
  (id_especialista IS NOT NULL AND id_equipamento IS NULL) OR
  (id_especialista IS NULL AND id_equipamento IS NOT NULL)
 ),
 INDEX(data_validade)
) ENGINE=InnoDB;

CREATE TABLE solicitacoes (
 id_solicitacao INT AUTO_INCREMENT PRIMARY KEY, id_especialista INT NOT NULL,
 id_modelo INT NOT NULL, id_utilizador INT NOT NULL, id_ramo INT NOT NULL,
 numero VARCHAR(50) NOT NULL UNIQUE, data_solicitacao DATETIME DEFAULT CURRENT_TIMESTAMP,
 estado ENUM('RASCUNHO','PENDENTE','EMITIDA','CANCELADA') DEFAULT 'RASCUNHO',
 conteudo_gerado LONGTEXT, observacoes TEXT,
 FOREIGN KEY(id_especialista) REFERENCES especialistas(id_especialista) ON DELETE RESTRICT,
 FOREIGN KEY(id_modelo) REFERENCES modelos_solicitacao(id_modelo) ON DELETE RESTRICT,
 FOREIGN KEY(id_utilizador) REFERENCES utilizadores(id_utilizador) ON DELETE RESTRICT,
 FOREIGN KEY(id_ramo) REFERENCES ramo(id_ramo) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE notificacoes (
 id_notificacao INT AUTO_INCREMENT PRIMARY KEY, id_ramo INT NOT NULL,
 id_utilizador INT NOT NULL, id_especialista INT NULL, id_equipamento INT NULL,
 id_tipo_alerta INT NOT NULL, titulo VARCHAR(255) NOT NULL, mensagem TEXT,
 nivel ENUM('INFO','ATENCAO','URGENTE') DEFAULT 'INFO', data_referencia DATETIME NULL,
 lida BOOLEAN DEFAULT FALSE, data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
 data_leitura DATETIME NULL,
 FOREIGN KEY(id_ramo) REFERENCES ramo(id_ramo) ON DELETE CASCADE,
 FOREIGN KEY(id_utilizador) REFERENCES utilizadores(id_utilizador) ON DELETE CASCADE,
 FOREIGN KEY(id_especialista) REFERENCES especialistas(id_especialista) ON DELETE CASCADE,
 FOREIGN KEY(id_equipamento) REFERENCES equipamentos(id_equipamento) ON DELETE CASCADE,
 FOREIGN KEY(id_tipo_alerta) REFERENCES tipos_alerta(id_tipo_alerta) ON DELETE RESTRICT,
 INDEX(id_utilizador,lida,data_criacao)
) ENGINE=InnoDB;

-- Dados iniciais
INSERT INTO perfis(nome,descricao,nivel_acesso) VALUES
('ADMINISTRADOR_GERAL','Acesso global','GLOBAL'),
('ADMINISTRADOR_RAMO','Acesso ao ramo atribuído','RAMO'),
('GESTOR_LOCAL','Acesso à unidade atribuída','UNIDADE'),
('CONSULTA','Consulta dentro do âmbito','UNIDADE');

INSERT INTO permissoes(nome,modulo,descricao) VALUES
('DASHBOARD_VISUALIZAR','DASHBOARD','Dashboard'),
('ESPECIALISTAS_VISUALIZAR','ESPECIALISTAS','Ver especialistas'),
('ESPECIALISTAS_CRIAR','ESPECIALISTAS','Criar especialistas'),
('ESPECIALISTAS_EDITAR','ESPECIALISTAS','Editar especialistas'),
('ESPECIALISTAS_ELIMINAR','ESPECIALISTAS','Eliminar especialistas'),
('EQUIPAMENTOS_VISUALIZAR','EQUIPAMENTOS','Ver equipamentos'),
('EQUIPAMENTOS_CRIAR','EQUIPAMENTOS','Criar equipamentos'),
('EQUIPAMENTOS_EDITAR','EQUIPAMENTOS','Editar equipamentos'),
('EQUIPAMENTOS_ELIMINAR','EQUIPAMENTOS','Eliminar equipamentos'),
('UTILIZADORES_VISUALIZAR','UTILIZADORES','Ver utilizadores'),
('UTILIZADORES_CRIAR','UTILIZADORES','Criar utilizadores'),
('UTILIZADORES_EDITAR','UTILIZADORES','Editar utilizadores'),
('UTILIZADORES_ELIMINAR','UTILIZADORES','Eliminar utilizadores'),
('FOTOGRAFIAS_VISUALIZAR','FOTOGRAFIAS','Ver fotografias'),
('FOTOGRAFIAS_CRIAR','FOTOGRAFIAS','Adicionar fotografias'),
('FOTOGRAFIAS_EDITAR','FOTOGRAFIAS','Editar fotografias'),
('FOTOGRAFIAS_ELIMINAR','FOTOGRAFIAS','Eliminar fotografias'),
('DOCUMENTOS_VISUALIZAR','DOCUMENTOS','Ver documentos'),
('DOCUMENTOS_CRIAR','DOCUMENTOS','Adicionar documentos'),
('DOCUMENTOS_EDITAR','DOCUMENTOS','Editar documentos'),
('DOCUMENTOS_ELIMINAR','DOCUMENTOS','Eliminar documentos'),
('SOLICITACOES_VISUALIZAR','SOLICITACOES','Ver solicitações'),
('SOLICITACOES_CRIAR','SOLICITACOES','Criar solicitações'),
('SOLICITACOES_IMPRIMIR','SOLICITACOES','Imprimir solicitações'),
('CONFIGURACOES_VISUALIZAR','CONFIGURACOES','Ver configurações'),
('CONFIGURACOES_GERIR','CONFIGURACOES','Gerir configurações'),
('RAMOS_VISUALIZAR','CONFIGURACOES','Ver ramos'),
('RAMOS_GERIR','CONFIGURACOES','Gerir ramos'),
('UNIDADES_VISUALIZAR','CONFIGURACOES','Ver unidades'),
('UNIDADES_GERIR','CONFIGURACOES','Gerir unidades'),
('ALERTAS_VISUALIZAR','ALERTAS','Ver alertas'),
('ALERTAS_GERIR','ALERTAS','Gerir alertas'),
('RELATORIOS_VISUALIZAR','RELATORIOS','Ver relatórios'),
('RELATORIOS_IMPRIMIR','RELATORIOS','Imprimir relatórios'),
('FERIAS_VISUALIZAR','FERIAS','Ver férias'),
('FERIAS_CRIAR','FERIAS','Criar férias'),
('FERIAS_EDITAR','FERIAS','Editar férias'),
('FERIAS_ELIMINAR','FERIAS','Eliminar férias'),
('CONSULTA_FALECIDOS','CONSULTA','Consultar falecidos'),
('CONSULTA_DOENTES','CONSULTA','Consultar doentes');

INSERT INTO perfil_permissoes
SELECT 1,id_permissao FROM permissoes;

INSERT INTO perfil_permissoes(id_perfil,id_permissao)
SELECT 4,id_permissao FROM permissoes
WHERE nome IN('DASHBOARD_VISUALIZAR','ESPECIALISTAS_VISUALIZAR',
'EQUIPAMENTOS_VISUALIZAR','FOTOGRAFIAS_VISUALIZAR','DOCUMENTOS_VISUALIZAR',
'SOLICITACOES_VISUALIZAR','ALERTAS_VISUALIZAR','RELATORIOS_VISUALIZAR',
'FERIAS_VISUALIZAR','CONSULTA_FALECIDOS','CONSULTA_DOENTES');

-- Administrador de ramo: gestão operacional dentro do ramo atribuído.
INSERT INTO perfil_permissoes(id_perfil,id_permissao)
SELECT 2,id_permissao FROM permissoes
WHERE nome NOT IN ('UTILIZADORES_ELIMINAR','CONFIGURACOES_GERIR');

-- Gestor local: gestão operacional, sem gestão global de utilizadores/configurações.
INSERT INTO perfil_permissoes(id_perfil,id_permissao)
SELECT 3,id_permissao FROM permissoes
WHERE nome IN(
 'DASHBOARD_VISUALIZAR',
 'ESPECIALISTAS_VISUALIZAR','ESPECIALISTAS_CRIAR','ESPECIALISTAS_EDITAR',
 'EQUIPAMENTOS_VISUALIZAR','EQUIPAMENTOS_CRIAR','EQUIPAMENTOS_EDITAR',
 'FOTOGRAFIAS_VISUALIZAR','FOTOGRAFIAS_CRIAR','FOTOGRAFIAS_EDITAR',
 'DOCUMENTOS_VISUALIZAR','DOCUMENTOS_CRIAR','DOCUMENTOS_EDITAR',
 'SOLICITACOES_VISUALIZAR','SOLICITACOES_CRIAR','SOLICITACOES_IMPRIMIR',
 'FERIAS_VISUALIZAR','FERIAS_CRIAR','FERIAS_EDITAR',
 'ALERTAS_VISUALIZAR','RELATORIOS_VISUALIZAR','RELATORIOS_IMPRIMIR'
);

INSERT INTO quadros_servico(nome,sigla) VALUES
('Quadro Permanente','QP'),('Quadro Miliciano','QM'),('Quadro Especial','QE'),('Civil','CIV');

INSERT INTO sexos(nome) VALUES('Masculino'),('Feminino');

INSERT INTO estados_civis(nome) VALUES
('Solteiro'),('Casado'),('Divorciado'),('Viúvo'),('União de facto');

INSERT INTO situacoes_servico(nome,grupo,descricao) VALUES
('ATIVO','ATIVO','Em efetividade de serviço'),
('DOENTE','INATIVO','Situação de doença'),
('FALECIDO','INATIVO','Especialista falecido'),
('REFORMADO','INATIVO','Reformado'),
('TRANSFERIDO','INATIVO','Transferido'),
('DESTACADO','ATIVO','Em destacamento'),
('LICENÇA','INATIVO','Em licença'),
('OUTRO','INATIVO','Outra situação');

INSERT INTO tipos_documento(nome) VALUES
('BI'),('Certificado'),('Diploma'),('Despacho'),('Declaração'),('Contrato'),('Relatório');

INSERT INTO tipos_alerta(nome,codigo,nivel_padrao,dias_antecedencia) VALUES
('Documento expirado','DOCUMENTO_EXPIRADO','URGENTE',0),
('Documento a expirar','DOCUMENTO_A_EXPIRAR','ATENCAO',30),
('BI expirado','BI_EXPIRADO','URGENTE',0),
('BI a expirar','BI_A_EXPIRAR','ATENCAO',60),
('Férias próximas','FERIAS_PROXIMAS','INFO',15),
('Férias em curso','FERIAS_EM_CURSO','INFO',0),
('Formação a terminar','FORMACAO_A_EXPIRAR','ATENCAO',30),
('Fim de função','FIM_FUNCAO','ATENCAO',30),
('Manutenção de equipamento','MANUTENCAO_EQUIPAMENTO','ATENCAO',0),
('Inspeção de equipamento','INSPECAO_EQUIPAMENTO','ATENCAO',0);

INSERT INTO modelos_solicitacao(nome,codigo,assunto,conteudo,categoria) VALUES
('Declaração de Efetividade','DECL_EFETIVIDADE','Declaração de Efetividade',
'Declara-se que {{NOME}}, NIP {{NIP}}, {{PATENTE}}, pertence à unidade {{UNIDADE}} e exerce funções de {{FUNCAO}} no {{RAMO}}.','Declaração'),
('Adiantamento Salarial','ADIANTAMENTO_SALARIAL','Pedido de Adiantamento Salarial',
'Eu, {{NOME}}, NIP {{NIP}}, venho solicitar adiantamento salarial.','Pedido'),
('Declaração de Serviço','DECL_SERVICO','Declaração de Serviço',
'Declara-se que {{NOME}}, NIP {{NIP}}, presta serviço na unidade {{UNIDADE}} do {{RAMO}}.','Declaração'),
('Declaração de Vínculo','DECL_VINCULO','Declaração de Vínculo',
'Declara-se que {{NOME}}, NIP {{NIP}}, mantém vínculo com o {{RAMO}}.','Declaração'),
('Pedido de Férias','PEDIDO_FERIAS','Pedido de Férias',
'Eu, {{NOME}}, NIP {{NIP}}, venho solicitar férias.','Pedido'),
('Pedido de Dispensa','PEDIDO_DISPENSA','Pedido de Dispensa',
'Eu, {{NOME}}, NIP {{NIP}}, venho solicitar dispensa.','Pedido'),
('Pedido de Transferência','PEDIDO_TRANSFERENCIA','Pedido de Transferência',
'Eu, {{NOME}}, NIP {{NIP}}, venho solicitar transferência.','Pedido'),
('Declaração de Tempo de Serviço','DECL_TEMPO_SERVICO','Declaração de Tempo de Serviço',
'Declara-se o tempo de serviço de {{NOME}}, NIP {{NIP}}.','Declaração');

USE sistema_especialistas_v3;
DROP VIEW IF EXISTS vw_especialistas_dashboard;
CREATE VIEW vw_especialistas_dashboard AS
SELECT e.id_especialista,e.id_ramo,e.id_quadro,e.id_patente,
       dp.nome_completo,sx.nome sexo,ds.nip,ds.id_unidade,
       q.nome quadro,p.nome patente,p.ordem ordem_patente,
       ss.nome situacao_servico,ss.grupo grupo_situacao
FROM especialistas e
JOIN dados_pessoais dp ON dp.id_especialista=e.id_especialista
JOIN quadros_servico q ON q.id_quadro=e.id_quadro
LEFT JOIN patentes p ON p.id_patente=e.id_patente
LEFT JOIN sexos sx ON sx.id_sexo=dp.id_sexo
LEFT JOIN dados_servico ds ON ds.id_especialista=e.id_especialista
LEFT JOIN situacoes_servico ss ON ss.id_situacao_servico=ds.id_situacao_servico;
