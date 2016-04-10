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

drop table if exists partidas cascade;
create table partidas (
  id_partida  bigserial constraint pk_partidas primary key,
  estado varchar(30), /* creada, jugando, terminada, cancelada*/
  creada_el timestamp default current_timestamp
);

drop table if exists json cascade;
create table json (
  id  bigserial constraint pk_partidas primary key,
  prueba jsonb
);
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


/*
create table jugadas (
  turno bigint constraint pk_jugadas primary key,
  ronda int not null, / para saber cuantas cartas repartir / ronda%3=0 => 3 cartas, ronda +1 % 3 => 2 cartas else 1 carta
dealer /jug1, jug2 ,jug3 o jug4/
  baraja, /cartas barajadas/ json tipo {}
  vida, "3oro"
  cartas_jugadas /solo las cartas jugadas y activas en el turno
  puntos_ronda
  puntos_totales,

  jug_1 '{"id": "1", "carta1" : "4basto", "carta2" : "3basto"}' si solo tiene 2 cartas


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
