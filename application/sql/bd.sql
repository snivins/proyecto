drop table if exists mensajes cascade;

create table mensajes (
  id  bigserial constraint pk_mensajes primary key,
  usuario varchar(30) not null,
  fecha timestamp default current_timestamp,
  contenido varchar(100) not null
);

drop table if exists usuarios cascade;
create table usuarios (
  id bigserial constraint pk_usuarios primary key,
  nick varchar(30) constraint uq_usuarios unique,
  password varchar(30) not null
);
insert into usuarios(nick,password) values
  ('sniv','sniv'),
  ('juan','juan'),
  ('pepe','pepe'),
  ('mimi','mimi');


drop table if exists partidas cascade;
create table partidas (
  id_partida  bigserial constraint pk_partidas primary key,
  estado varchar(30), /* creada, jugando, terminada, cancelada*/
  creada_el timestamp default current_timestamp,
  "jug_1"  bigint constraint fk_partidas_1_usuarios references usuarios(id),
  "jug_2"  bigint constraint fk_partidas_2_usuarios references usuarios(id),
  "jug_3"  bigint constraint fk_partidas_3_usuarios references usuarios(id),
  "jug_4"  bigint constraint fk_partidas_4_usuarios references usuarios(id)
);
/*
creo partida, soy jug_1, estado 'creada' (pueden unirse a la partida)


*/


insert into partidas(estado,jug_1, jug_2, jug_3) values ('creada', 1,2,3);
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
  turno bigserial not null,
  ronda int not null, /* para saber cuantas cartas repartir / ronda%3=0 => 3 cartas, ronda +1 % 3 => 2 cartas else 1 carta*/
  dealer_id bigint constraint fk_jugadas_usuarios references usuarios(id),/*jug1, jug2 ,jug3 o jug4*/
  baraja json, /*cartas barajadas/ array cartas tipo 1espada , 3basto, 4copas,12oro */
  vida varchar(20),/* "3oro" */
  cartas_jugadas json,/*solo las cartas jugadas y activas en el turno se renueva tras una ronda con 3 cartas*/
  puntos_ronda numeric(30) not null default 1,/*en caso de que envie y acepte, se guarda aqui el total*/
  puntos_totales numeric(80),
  jug_1 json not null,
  jug_2 json not null,
  jug_3 json not null,
  jug_4 json not null
);

  /*

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
