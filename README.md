# Poker Doker

Poker Doker je PHP aplikácia (hra), ktorá vygeneruje n hier pre n hráčov pre daný turnaj a vo finále vypíše víťaza.

## Features

- hra na **N** kôl
- hra pre **N** hráčov
- generácia **52 kariet**
- **miešanie** kariet
- zobrazené **karty hráča**
- zobrazené **karty na stole**
- zobrazené takzvané **"piate karty"** to sú karty, ktoré pri rovnosti figúry a súčasne bodovej rovnosti rozhodnú o **poradí v danom kole**
- počítanie **poradia hráčov v danom kole** (pri rovnosti figúry, bodov v danej figúry a takzvaných piatych kariet sa môže stať, že jedno kolo bude mať dvoch víťazov, to isté platí aj pre poradie na nižších miestach v rankingu)
- **poradie hráčov v turnaji** po N kolách

## Inštalácia

### Git

```bash
git clone https://tomas-doubek@bitbucket.org/digilopment/poker-doker.git ./poker-doker

```
otvor localhost so smerovaním do rootu **http://localhost/poker-doker**

### Docker Compose

```bash
git clone https://tomas-doubek@bitbucket.org/digilopment/poker-doker.git ./poker-doker
cd poker-doker
docker-compose up
```
otvor adresu z konzoly, pravdepodobne **http://172.18.0.2** alebo **http://localhost:8001**

### Docker Pull

```bash
docker pull digilopment/poker-doker:latest
docker run digilopment/poker-doker
```
otvor adresu z konzoly, pravdepodobne **http://172.18.0.2**

## Použitie GET parametrov

- **rounds** => (int) nastaví počet kôl turnaja, default => RAND(), ak nie je konfigurácia striktne definovaná v JSON-e
- **mod** => (int) nastaví mód hry, default => 1, ak nie je konfigurácia striktne definovaná v JSON-e
- **file** => (string) načíta konfiguračný súbor s hráčmi v **json/{file}.json**, default => 'default' 
