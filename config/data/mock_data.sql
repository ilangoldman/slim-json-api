
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


INSERT INTO conquista(titulo,descricao,pontos) values
    ('R$1.000 investidos','++1000 reais', 10),
    ('10 empresas investidas','++10 empresas', 15),
    ('Amigo convidado','++1 amigo', 20);


INSERT INTO beneficio(titulo,descricao,conquista) values
    ('Vale chocolate','1 chocolate pra vc!', 1),
    ('Vale abraço','1 abraco pra vc!', 2),
    ('Camisa','Parabéns vc ganhou uma camisa!!', 3);



-- 1 = 'Lista de Espera',
-- 2 = 'Oferta Aceita',
-- 3 = 'Transferencias',
-- 4 = 'Financiada',
-- 5 = 'Pago',
-- 6 = 'Atraso'
