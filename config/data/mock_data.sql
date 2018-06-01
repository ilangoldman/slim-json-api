INSERT INTO empresa(nome,uid,criado) values
    ('Goldman Ltda','1',Now()),
    ('Rado & CIA','2',Now()),
    ('Shelby Inc.','3',Now()),
    ('Digdin, Dongdon e Agregados','4',Now()),
    ('UpCASH','5',Now()),
    ('Hello World','6',Now());

INSERT INTO emprestimos(valor,taxa,prazo,status, parcelas, valorParcela,criado,empresa_eid) values
    (50000,0.32,24,'',24,140,Now(),1),
    (100000,0.12,24,'',24,190,Now(),2),
    (80000,0.45,12,'',12,120,Now(),5),
    (1000000,0.10,48,'',48,80,Now(),3);


INSERT INTO infoemprestimo(descricao,motivo,emprestimos_empid,emprestimos_empresa_eid,criado) values
    ('Nascida em 1991 na cidade de Ribeirão Preto - SP, a SVC Laser sempre esteve focada em trazer as melhores soluções de acordo com o perfil de cada cliente.Atuando inicialmente junto ao segmento gráfico, a empresa se destacou com os produtos das marcas HP e Risograph, conquistando a confiança deste mercado que se caracteriza por uma exigência muito alta de qualidade nas impressões e uma sensibilidade aguçada quanto aos custos de investimento e manutenção das máquinas.','Será utilizado na aquisição de novos equipamentos para show room, com ampliação do mesmo e consequente incremento da capacidade de demonstração de nossos produtos e serviços para novos clientes.Além disso, parte do recurso será destinada a implantação de novo sistema de gestão de locação e gestão de help desk com consequente melhora do atendimento do departamento de serviços.',1,1, Now()),
    ('Descricao BACANA','FICA RICO',2,2,Now()),
    ('HA HA HA','Compra um carro corporativo',3,5,Now()),
    ('DIG DIM DIM DON DON DON','Expansão da empresa',4,3,Now());
    
    
INSERT INTO investidor(nome,sobrenome,uid,criado,pontos) values
    ('Ilan','Goldman','10',Now(),230),
    ('Gabriel','Radomysler','20',Now(),542),
    ('Thomas','Shelby','30',Now(),10),
    ('Frank','Castle','40',Now(),9999),
    ('Leonardo','Tonello','50',Now(),400),
    ('Hello','World','60',Now(),620);


INSERT INTO pontuacao(nivel,pontos,titulo,simbolo) values 
    (1,30,'Recruit','android'),
    (2,60,'Recruit','android'),
    (3,100,'Private','android'),
    (4,150,'Private','android'),
    (5,210,'Corporal','android'),
    (6,280,'Corporal','android'),
    (7,360,'Sergeant','android'),
    (8,450,'Sergeant','android'),
    (9,550,'Staff Sergeant','android'),
    (10,660,'Staff Sergeant','android'),
    (11,780,'Second Lieutenant','android'),
    (12,910,'Second Lieutenant','android'),
    (13,1050,'Second Lieutenant','android'),
    (14,1200,'Second Lieutenant','android'),
    (15,1360,'Lieutenant','android'),
    (16,1530,'Lieutenant','android'),
    (17,1710,'Lieutenant','android'),
    (18,1900,'Lieutenant','android'),
    (19,2100,'Captain','android'),
    (20,2310,'Captain','android'),
    (21,2530,'Captain','android'),
    (22,2760,'Captain','android'),
    (23,3000,'Captain','android'),
    (24,3250,'Captain','android'),
    (25,3510,'Major','android'),
    (26,3780,'Major','android'),
    (27,4060,'Major','android'),
    (28,4350,'Major','android'),
    (29,4650,'Major','android'),
    (30,4960,'Major','android'),
    (31,5280,'Lieutenant Colonel','android'),
    (32,5610,'Lieutenant Colonel','android'),
    (33,5950,'Lieutenant Colonel','android'),
    (34,6300,'Lieutenant Colonel','android'),
    (35,6660,'Lieutenant Colonel','android'),
    (36,7030,'Lieutenant Colonel','android'),
    (37,7410,'Lieutenant Colonel','android'),
    (38,7800,'Lieutenant Colonel','android'),
    (39,8200,'Colonel','android'),
    (40,8610,'Colonel','android'),
    (41,9030,'Colonel','android'),
    (42,9480,'Colonel','android'),
    (43,9900,'Colonel','android'),
    (44,10350,'Colonel','android'),
    (45,10810,'Colonel','android'),
    (46,11280,'Colonel','android'),
    (47,11760,'Colonel','android'),
    (48,12260,'Colonel','android'),
    (49,12761,'Senior Colonel','android'),
    (50,13261,'Senior Colonel','android');


INSERT INTO beneficios(titulo,descricao,pontuacao_pid) values
    ('Vale chocolate','1 chocolate pra vc!', 40),
    ('Vale abraço','1 abraco pra vc!', 10),
    ('Camisa','Parabéns vc ganhou uma camisa!!', 5);

INSERT INTO investimentos(taxa,valor,status,parcelas,valorParcela,criado,emprestimos_empid,emprestimos_empresa_eid,investidor_iid) values
    (0.25,2000,'2',24,140,Now(),1,1,1),
    (0.25,6000,'2',24,140,Now(),1,1,2),
    (0.25,10000,'2',24,140,Now(),1,1,3),
    (0.25,500,'2',24,140,Now(),1,1,4),
    (0.25,2000,'1',24,140,Now(),1,1,5),

    (0.10,2000,'5',24,140,Now(),2,2,1),
    (0.40,1000,'3',12,140,Now(),3,5,1),
    (0.05,4000,'4',48,140,Now(),4,3,1);
 

-- 1 = 'Lista de Espera',
-- 2 = 'Oferta Aceita',
-- 3 = 'Transferencias',
-- 4 = 'Financiada',
-- 5 = 'Pago',
-- 6 = 'Atraso'

INSERT INTO parcelas(valor,criado,emprestimos_empid,emprestimos_empresa_eid,investimentos_invid) values
    (150,Now(),2,2,6);