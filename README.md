# API Simples para Jogos de Futsal

### API Construída com Framework Laravel (PHP)

<br/>

## Funcionalidades:

* Usuário:
    * Registrar
    * Login (singin)
    * Logout*
    * Listar Usuário*
    > *Apenas se autenticado (login)

### Funcionalidades que necessitam de autenticação:
* Jogadores (player):
    * Registrar
    * Editar
    * Remover
    * Listar (um ou todos)
* Equipes (team):
    * Registrar
    * Editar
    * Excluir
    * Incluir Jogador na equipe
    * Remover Jogador da equipe
    * Listar (um ou todos)
* Jogos (team matches):
    * Registrar
    * Editar
    * Remover
    * Listar (um ou todos)

* Classificação:
    * Exibir Classificação

## Dados:
* Jogadores (player):
    * Nome
    * Número da camisa *
    > *Número da camisa quando jogador está em uma equipe
* Equipes (team):
    * Nome da Equipe
* Jogos (team matches):
    * Equipe mandante (home)
    * Equipe visitante (visitor)
    * Gols equipe mandante
    * Gols equipe visitante
    * Data do jogo
    * Horário de início
    * Horário do fim

* Classificação:
    * Nome do Time
    * Pontos
    * Vitórias
    * Derrotas
    * Empates
    * Gols pró
    * Gols contra
    * Saldo de gols
    * Número de partidas
    > Obs.: A listagem já está na ordem correta de classificação

<br/>

___
___

<br/>

## Endpoints e Requisições:

> base_url =  `http://localhost:8000/api`

<br/>

___

## Usuário:

### Registrar usário:

Endpoint: [POST] `base_url`/register

Header:

    Accept: application/json
    Content-Type: application/json

Body:
~~~json
{
    "name": "user_name",
	"email": "user@mail.com",
	"password": "000password"
}
~~~
___
### Login:

Endpoint: [POST] `base_url`/singin

Header:

    Accept: application/json
    Content-Type: application/json

Body:
~~~json
{
	"email": "user@mail.com",
	"password": "000password"
}
~~~
___
### Logout:

Endpoint: [POST] `base_url`/logout

Header:

    Accept: application/json
    Authorization: Bearer {{ _.user_token }}

Body: no
___

### List users:

Endpoint: [GET] `base_url`/users

Header:

    Accept: application/json
    Authorization: Bearer {{ _.user_token }}

Body: no

<br/>

___
___

<br/>

## Jogadores (players)

### List Players
Endpoint: [GET] `base_url`/players

Header:

    Accept: application/json
    Authorization: Bearer {{ _.user_token }}

Body: no
___
### Get player
Endpoint: [GET] `base_url`/players/{id}

Header:

    Accept: application/json
    Authorization: Bearer {{ _.user_token }}

Body: no
___

### Add Player:

Endpoint: [POST] `base_url`/players

Header:

    Content-Type: application/json
    Accept: application/json
    Authorization: Bearer {{ _.user_token }}

Body:
~~~json
{
	"name": "Jogador de Vidro"
}
~~~
___
### Edit Player:

Endpoint: [PUT] `base_url`/players/{id}

Header:

    Content-Type: application/json
    Accept: application/json
    Authorization: Bearer {{ _.user_token }}

Body:
~~~json
{
	"name": "Jogador de Vidro",
	"number": int || null,
	"team_id": int || null
}
~~~
___

### Delete player
Endpoint: [DELETE] `base_url`/players/{id}

Header:

    Accept: application/json
    Authorization: Bearer {{ _.user_token }}

Body: no
___
<br/>

___
___

<br/>

## Equipes (teams)
### List Teams
Endpoint: [GET] `base_url`/teams

Header:

    Accept: application/json
    Authorization: Bearer {{ _.user_token }}

Body: no
___
### Get team
Endpoint: [GET] `base_url`/teams/{id}

Header:

    Accept: application/json
    Authorization: Bearer {{ _.user_token }}

Body: no
___
### Add Teams
Endpoint: [POST] `base_url`/teams

Header:

    Content-Type: application/json
    Accept: application/json
    Authorization: Bearer {{ _.user_token }}

Body: 

~~~json
{
	"name": "team_name"
}
~~~
___
### Delete team
Endpoint: [DETELETE] `base_url`/teams/{id}

Header:

    Accept: application/json
    Authorization: Bearer {{ _.user_token }}

Body: no
___
### Team - Add player
Endpoint: [POST] `base_url`/teams/{team_id}/player/add/{player_id}

Header:

    Content-Type: application/json
    Accept: application/json
    Authorization: Bearer {{ _.user_token }}

Body: 

~~~json
{
	"number": 0 //number of player
}
~~~
### Team - Remove player
Endpoint: [POST] `base_url`/teams/{team_id}/player/remove/{player_id}

Header:

    Accept: application/json
    Authorization: Bearer {{ _.user_token }}

Body: no
___

<br/>

___
___

<br/>

## Jogos (team matches)

### List Matches
Endpoint: [GET] `base_url`/matches/

Header:

    Accept: application/json
    Authorization: Bearer {{ _.user_token }}

Body: no
___

### Get one Match
Endpoint: [GET] `base_url`/matches/{id}

Header:

    Accept: application/json
    Authorization: Bearer {{ _.user_token }}

Body: no
___

### Add Match
Endpoint: [POST] `base_url`/matches/

Header:

    Content-Type: application/json
    Accept: application/json
    Authorization: Bearer {{ _.user_token }}

Body: 
~~~json
{
	"match_date": "2022-10-01",
	"start_at": "21:30:00",
	"end_at": "23:15:00",
	"team_h": 1, // int - home team id
	"team_v": 2, // int - visitor team id
	"goals_h": 0, // int - home team number of goals
	"goals_v": 0 // int - visitor team number of goals
}
~~~
___
___

### Add Match
Endpoint: [DELETE] `base_url`/matches/{id}

Header:

    Accept: application/json
    Authorization: Bearer {{ _.user_token }}

Body: no
___

<br/>

___
___

<br/>

## Classificação

Endpoint: [GET] `base_url`/classification/

Header:

    Accept: application/json
    Authorization: Bearer {{ _.user_token }}

Body: no

<br/>

Extra:

>Response Ex.:
>~~~json
>[
>    {
>		"Team": "Time 1",
>		"points": 9,
>		"won": 3,
>		"lost": 0,
>		"draw": 0,
>		"goals_for": 6,
>		"goals_against": 2,
>		"goals_difference": 4,
>		"matches": 3
>	}
>]
>~~~
___