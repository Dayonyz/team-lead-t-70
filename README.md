# Team Lead T-70 Application

## Laravel Deploy

```
make build
make install
```

## Run tests before

```
make ssh
vendor/bin/phpunit --testsuite "Unit Tests"
```

## Run Application

```
make restart
make ssh
php app.php

Welcome and have a fun :)
```
Code base folder
```
/src
```
Technical requirements
```
Teamlead - Terminator T-70
Implement the algorithm for automatic education of a junior developer

Teamlead T-70 can be in one of 4 states, the initial state can be any:
• Good mood
• Normal mood
• Bad mood
• State «don't catch my eye»

Depending on the work of the junior developer (signals 1 || 0), T-70 goes into another state:

Initial state
                             Does the job successfully - 1    Does a bad job - 0
Good mood                    Good mood                        Normal mood
Normal mood                  Good mood                        Bad mood
Bad mood                     Normal mood                      State «don't catch my eye»
State «don't catch my eye»   Bad mood                         State «don't catch my eye»

When transitioning between states, the T-70 generates a corresponding phrase (think up your own)

We have HR T-1000, she wants to know how many times T-70 has reprimanded the programmer
(occurs when T70 in the "don't catch my eye" state receives a 0 signal).

There is also Manager T-1001, she wants to know how many times the programmer was praised by T-70
(T-70 in the "Good mood" state receives signal 1).

Requirements:
1. Pure PHP (no frameworks)
2. The system should be scalable, i.e. when adding a new T-70 state, we don't need to refactor the entire system,
3. We should be able to easily change the system's behavior. For example: go from the "Good mood" state to "Bad" when the programmer is doing a bad job (signal 0).
4. We can easily add any number of additional entities that will monitor changes in the T-70 state.
```