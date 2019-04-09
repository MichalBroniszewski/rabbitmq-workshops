# Gildia PHP - RabbitMQ #

### Wymagania ###

Jeśli nie korzystasz z Dockera musisz spełnić poniższe wymagania (zgodnie z ``composer.json``): 

* PHP 7.3
* Zainstalowana i działająca instancja RabbitMQ

### Zanim zaczniesz ###

Wejdź do kontenera Dockera do katalogu z projektem w 3 różnych terminalach. Jeden będzie służył do
publikowania wiadomości, a dwa od odbierania.
Dodatkowo otwórz w przeglądarce adres: http://localhost:15672 (user/pass). W nim będziesz mógł 
obserwować działanie Rabbita.

### Zadanie 1 - exchange typu Direct ###

Ten exchange przesyła wiadomości tylko do kolejek, których binding key jest zgodny z binding key 
przekazanym podczas publikowania wiadomości. Przeanalizuj kod komend, a następnie wykonaj ćwiczenia 
w 3 konsolach i poglądaj działanie w panelu RabbitMQ.

##### 1a #####

1. W pierwszej konsoli uruchom ```bin/console direct:consume one```
2. W drugiej konsoli uruchom ```bin/console direct:consume two```
3. Wysyłaj wiadomości z trzeciej konsoli korzystając z 
```bin/console direct:consume <<binding_key>> <<message>>```

##### 1b #####

1. W pierwszej i drugiej konsoli uruchom ```bin/console direct:consume one```
2. Wysyłaj wiadomości z trzeciej konsoli korzystając z 
   ```bin/console direct:consume <<binding_key>> <<message>>```
   
### Zadanie 2 - exchange typu Fanout ###

Ten exchange przesyła wiadomości do wszystkich przypisanych kolejek.Przeanalizuj kod komend, 
a następnie wykonaj ćwiczenia w 3 konsolach i poglądaj działanie w panelu RabbitMQ.

1. W pierwszej i drugiej konsoli uruchom ```bin/console fanout:subscribe```
2. Wysyłaj wiadomości z trzeciej konsoli korzystając z 
   ```bin/console fanout:publish <<message>>```

### Zadanie 3 - exchange typu Topic ###

Ten exchange przesyła wiadomości tylko do kolejek, których wzór binding key jest zgodny z binding key 
przekazanym podczas publikowania wiadomości. Przeanalizuj kod komend, a następnie wykonaj ćwiczenia 
w 3 konsolach i poglądaj działanie w panelu RabbitMQ.

Korzystając z komend:
* ```bin/console topic:produce <<message>> <<binding_key>>```
* ```bin/console topic:subscribe <<binding_key>>```

oraz pamiętając, że:
* ```*``` oznacza n dowolnych słów
* ```#``` oznacza jedno słowo
* binding keys używamy w konwencji ```car.green.cheap```

przetestuj działanie.