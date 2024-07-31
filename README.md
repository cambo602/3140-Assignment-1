# 3140-Assignment-3
Cameron Harrison
300233006

Reid Tull
300228749

# Running this
Setup a MySQL database, set the username to `root` and the password to `admin`.

add a database called `csi3140` and execute the sql commands in `setup.sql`

Ensure you have the mysql php extension enabled with the line `extension=php_mysqli.dll` in you `php.ini`

run `php -S localhost:8000` in the repo root and navigate to `http://localhost:8000/public/game.php`

# This game is a simple Towers of Hanoi implementation

The game now uses php to store the game state/score, and logic which is stored in a session variable

The game now has a leaderboard which is stored in a json file, and is displayed on the game page

The game uses api calls to update the game state and leaderboard, as well as to make changes to the game state and score

The front end of the game makes use of AJAX to make api calls to the backend to control the game state, increment/ reset score, control the logic of the game, and update the leaderboard



