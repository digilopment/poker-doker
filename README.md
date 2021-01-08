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
- počítanie **poradia hráčov v danom kole** (pri rovnosti figúry, bodov v danej figúry a takzvaných piatych kariet sa kľudne stať, že kolo bude mať dvoch víťazov, to isté platí aj pre poradie na nižžších miestach v rankingu)
- **poradie hráčov v turnaji** po N kolách

## Inštalácia

Git clone

```bash
git clone https://tomas-doubek@bitbucket.org/digilopment/poker-doker.git ./poker-doker
```
http://localhost/poker-doker

Docker

```bash
#TO DO
```

## Použitie GET parametrov

- **rounds** => (int) nastaví počet kôl turnaja, default => RAND()
- **mod** => (int) nastaví mód hry, default => 1
- **file** => (string) načíta konfiguračný súbor s hráčmi v **json/{file}.json**, default => 'default' 

