drop table if exists mensajes cascade;

create table mensajes (
  id  bigserial constraint pk_mensajes primary key,
  id_partida bigint constraint fk_mensajes_partidas references partidas(id_partida),
  usuario varchar(30) not null,
  fecha timestamp default current_timestamp,
  privado varchar(10) default '',
  contenido varchar(100) not null
);

  drop table if exists usuarios cascade;
  create table usuarios (
    id bigserial constraint pk_usuarios primary key,
    nick                varchar(100) not null constraint uq_usuarios_nick unique,
    password            char(60)     not null constraint ck_password_valida
                                              check (length(password) = 60),
    email               varchar(100) not null,
    posicion varchar(30),
    foto_perfil varchar(50) default 'fotoPerfil.png',
    registrado timestamp default current_timestamp
  );
  insert into usuarios(nick,password,email,posicion) values
    ('sniv','$2y$10$QTIcrVfqdsEOL8DU1Aac5OgsIasIfNmgzQAZGDaNIrGAWWWka17ze','vicentejlopezballen@gmail.com','jug_1'),
      ('sniv2','$2y$10$QTIcrVfqdsEOL8DU1Aac5OgsIasIfNmgzQAZGDaNIrGAWWWka17ze','2vicentejlopezballen@gmail.com',null),
        ('sniv3','$2y$10$QTIcrVfqdsEOL8DU1Aac5OgsIasIfNmgzQAZGDaNIrGAWWWka17ze','12vicentejlopezballen@gmail.com',null),
          ('sniv4','$2y$10$QTIcrVfqdsEOL8DU1Aac5OgsIasIfNmgzQAZGDaNIrGAWWWka17ze','1vicentejlopezballen@gmail.com',null);


drop table if exists partidas cascade;
create table partidas (
  id_partida  bigserial constraint pk_partidas primary key,
  estado varchar(30),  /* creada, jugando, terminada, cancelada*/
  creada_el timestamp default current_timestamp,
  "jug_1"  bigint constraint fk_partidas_1_usuarios references usuarios(id),
  "jug_2"  bigint constraint fk_partidas_2_usuarios references usuarios(id),
  "jug_3"  bigint constraint fk_partidas_3_usuarios references usuarios(id),
  "jug_4"  bigint constraint fk_partidas_4_usuarios references usuarios(id)
);
/*
creo partida, soy jug_1, estado 'creada' (pueden unirse a la partida)

*/


/*insert into partidas(estado,jug_1) values ('creada', 1);*/
drop table if exists ci_sessions cascade;

create table "ci_sessions" (
    "id" varchar(40) not null primary key,
    "ip_address" varchar(45) not null,
    "timestamp" bigint default 0 not null,
    "data" text default '' not null
);

create index "ci_sessions_timestamp" on "ci_sessions" ("timestamp");
/*



json contiene usuario_id
prueba=> insert into json (prueba) VALUES ('{"nombre":"prueba", "carta" : "mortadela"}');
INSERT 0 1
prueba=> select * from json;
 id |                   prueba
----+--------------------------------------------
  1 | {"nombre":"prueba", "carta" : "mortadela"}
(1 row)

prueba=> select prueba->>'carta' from json; devuelve mortadela
*/

drop table if exists jugadas cascade;
create table jugadas (
  id bigserial constraint pk_jugadas primary key,
  id_partida bigint constraint fk_jugadas_partidas references partidas(id_partida),
  turno int not null,
  ronda int not null, /* para saber cuantas cartas repartir / ronda%3=0 => 3 cartas, ronda +1 % 3 => 2 cartas else 1 carta*/
  turno_ronda int not null,/*de 1 a 4*/
  dealer_id varchar(10),/*jug1, jug2 ,jug3 o jug4*/
  baraja json, /*cartas barajadas/ array cartas tipo 1espada , 3basto, 4copas,12oro */
  vida varchar(20),/* "3oro" */
  cartas_jugadas json,/*solo las cartas jugadas y activas en el turno se renueva tras una ronda con 3 cartas*/
  ultima_mano json,/*cartas en mesa pero que no cuentan*/
  puntos_pendientes numeric(30) not null default 0,
  puntos_ronda numeric(30) not null default 1,/*en caso de que envie y acepte, se guarda aqui el total*/
  puntos_equipo_1 numeric(80),
  puntos_equipo_2 numeric(80),
  turno_jug varchar(5),
  ultima_jugada varchar(50),
  jug_1 json not null,
  jug_2 json not null,
  jug_3 json not null,
  jug_4 json not null
);

  /*

id : 1
id_partida:2
jugada: 4_oros          |Varios casos posibles,


-caso de una carta bocarriba:
  cartas jugadas + 4 de oros
  turno ++
  turno_ronda++ //2 casos, si turno_ronda es <4 y si es 4
  -caso de <4:
      se cambia el estado de los jugadores, pasa de ataque a sperando, de defensa a ataque y d sperando a defensa
  -caso de 4:
      calculo cartas
      añadir puntuacion
      puntos_ronda = 0
      ronda++  //caso ronda con cartas nuevas o sin barajar
      -caso sin barajar:
          se actualiza el estado de los jugadores con cartas de la baraja tras las ya repartidas
      -caso con barajar:
          se actualiza la baraja, la vida y se reparte 1 o 3 cartas segun puntos

  dealer_id +1, s










  jug_1 '{ "estado": "esperando_turno","carta1" : "4basto", "carta2" : "3basto"}' si solo tiene 2 cartas
  estado: (ataque, defensa, esperando_turno)

  3,4,5,6,7,1,10,11,12,2 si es vira
  2,3,4,5,6,7,1,10,11,12 si no lo es



  De la carta jugada calculamos el valor

valor = 0;
if (!isset(paloCarta1)){
  paloCarta1 = palo;
}

  if (palo == paloVida) {
    valor+= 10;
    if (num == 2) valor += 13;
    else if (num == 1) valor += 8;
    else valor += num;
  }else if (palo == paloCarta1) {
    if  (num == 1) valor += 8;
    else valor += num;
  }



ronda 1
  tenemos vira espada
  baraja
  puntos_ronda =1;

  turno 1
    cartas repartidas
    jug1 juega 3basto  valor= 3
  turno 2
    jug2 pasa valor = 0
  turno 3
    jug3 envia,
    jug4 acepta, puntos_ronda = 3;
    jug3 juega 1espada valor = 18
  turno 4
    jug 4 juega 10espada valor = 20

  se calcula el mayor si nadie se va d la ronda si no gana el equipo q no se va
    equipo 2 gana
    puntuacion_total_equipo_1 += puntos_ronda
    ronda++
    puntos_ronda = 1
    reparte (ve que es ronda 2 y q puntos totales < q x) da 2 cartas


      turno 1
        jug1 juega 1 de vira
      turno 2
        jug2 envia,
        jug3 se niega

      equipo 2 gana
      puntuacion_total_equipo_2  += 1
      puntos_ronda = 1
      ronda ++





);*/
