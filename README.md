BOTSON
------

Command-line tool for generating random KyleBot sentences

*Usage*
```
botson.php [-cjnprt]

  -c, --compound            Build a compound sentence
  -j, --use-conjunction     Use a conjunciton in the compound sentence
  -n, --num-sentences       Number of sentences (randomized)
  -p, --num-paragraphs      Number of paragraphs
  -r, --randomize           Randomize parameters
  -t, --type                Build a sentence of a specific type:
                                SV      Subject-Verb
                                SVO     Subject-Verb-Object
                                SVAO    Subject-Verb-Adjective-Object
                                SVP     Subject-Verb-PrepositionalPhrase
```

*Run using Docker Compose*
```
$ docker-compose up -d
$ docker-compose exec cli bash
$ ./botson.php
```


