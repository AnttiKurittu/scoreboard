# scoreboard
The scoreboard script used in the NCSC-FI #tietoturvahaaste-hackathon.

Requires PHP. Copy the files and folders to the directory that serves your webpages. Open in browser and F11 for full screen.

The folder '''teams/''' holds the teams and scoring. Create a text file for each team. See example files for syntax, '''index.php''' source for configuration instructions.

For live scoring edit the text files under '''teams/''', the scoreboard will automatically refresh and show the changes at set intervals. The easiest way to do this is to set the scoreboard machine to show a browser with the page open and ssh into it to edit the team files. You can set the competition deadline from the source file.

![Screenshot](https://github.com/AnttiKurittu/scoreboard/blob/master/resources/screenshot.png)
