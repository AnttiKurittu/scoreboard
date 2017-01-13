<!DOCTYPE html>
<!--

ABOUT

This is the NCSC-FI Hackathon scoreboard script. Create a file under ./teams/ for each competing team.
See example teams for instructions.

If you comment out the first line (team name) that file will be ignored. (see teams/example.txt).
Judges may edit these text files as they see fit. The scoreboard will auto-update as required.
Please edit the counter and set the deadline for the competition.

// Antti Kurittu

-->

<html>
<head>
<title>Scoreboard</title>
	<meta charset="UTF-8">
	<!-- Configuration: Set reload delay -->
	<meta http-equiv="refresh" content="60">
	<link rel="stylesheet" href="resources/bootstrap.css">
	<link rel="stylesheet" href="resources/cyborg.css">
	<link rel="stylesheet" href="resources/css/font-awesome.min.css">
	</head>
<body style="margin-top:40px;margin-bottom:20px;">
	<!-- Configuration: Title image -->
	<img src="resources/tietoturvahaaste.png" style="width:100%;">

	<div class="col-md-1 col-sm-1">
		<!--spacer -->
	</div>
	<div class="col-md-10 col-sm-10">
		<div class="well well-sm" style="-webkit-box-shadow: 0px 0px 111px 0px rgba(38,82,130,0.84);-moz-box-shadow: 0px 0px 111px 0px rgba(38,82,130,0.84);box-shadow: 0px 0px 111px 0px rgba(38,82,130,0.84);">
			<table class="table table-striped">
				<tr>
					<th>NAME</th>
					<th><span class="pull-right">FINDINGS</span></th>
					<th style="width:1px;"><span class="pull-right">SCORE</span></th>
				</tr>

<?php 
// Get the team files, one per team.
$team_files = scandir('./teams/', 0);
// Declare an array to hold all the teams.
$teams = array();
// Open the team file for processing.
foreach($team_files as $file)
{
	// Skip the hidden files, present and parent folders.
	if (substr($file, 0, 1) === ".")
	{
		continue;
	}
	else
	{
		// Get the team file and convert it to an array.
		$file_data = array_slice(file('./teams/'.$file), 0);
		// Skip processing the file if the first line is commented out.
		if (substr($file_data[0], 0, 1) === "#") 
		{
			continue;
		}
		else
		{
			// Set the first line of the file as the team name
			$team_name = $file_data[0];
			$teams[$team_name]['name'] = $team_name;
			$teams[$team_name]['score'] = "0";
			$teams[$team_name]['findings_count'] = "0";
			$teams[$team_name]['findings'] = array();
			// Count findings and scores.
			foreach($file_data as $line)
			{
					// Skip the team name line and comment lines.
					if (($line === $team_name) || (substr($line, 0, 1) === "#"))
					{
						continue;
					}
					// Process non-empty lines (more than 2 characters)
					elseif (strlen($line) > 2)
					{
						// Add one to findings total sum
						$teams[$team_name]['findings_count']++;
						// Tally the score, use ";" as a separator. "score;objective;comment".
						$result_row = explode(";",$line);
						// Sum the scores and add the findings to the team array.
						array_push($teams[$team_name]['findings'], $result_row);
						$teams[$team_name]['score'] = $teams[$team_name]['score'] + preg_replace("/[^0-9]/", "", $result_row[0]);
					}
					else
					{
						continue;
					}
			}
		}
	}
}

// Sort the teams-array by score.
function compare($a, $b) {
    return ($a['score'] < $b['score']);
}
usort($teams, 'compare');

$i = 0;
// Print teams on scoreboard
foreach($teams as $team)
{
	$a = 0;
	$trophies = "";
	
	// Show findings as graphics.
	foreach($team['findings'] as $item)
	{
		if ($item[0] >= 1000)
		{
			$trophies = $trophies . ' <i class="fa fa-diamond" style="color:#AAF;"></i>';
		}
		elseif ($item[0] >= 500)
		{
			$trophies = $trophies . ' <i class="fa fa-trophy" style="color:gold;"></i>';
		}
		elseif ($item[0] >= 200)
		{
			$trophies = $trophies . ' <i class="fa fa-star" style="color:silver;"></i>';
		}
		else
		{
			$trophies = $trophies . ' <i class="fa fa-heart" style="color:pink;"></i>';
		}

		$a++;
	}

	$i++;
	// Print the table rows for teams.
	if ($i === 1)
	{
		print '<tr><td><span style="font-size:1.4em;">' . trim($team['name']) . '</span></td><td><span class="pull-right" style="font-size:1.5em;">' . $trophies . '</span></td><td><span class="pull-right" style="font-size:1.6em;">' . number_format($team['score']) . '</span></td></tr>' . "\r\n";
	}
	else
	{
		print '<tr><td><span style="font-size:1.4em;">' . trim($team['name']) . '</span></td><td><span class="pull-right" style="font-size:1.5em;">' . $trophies . '</span></td><td><span class="pull-right" style="font-size:1.6em;">' . number_format($team['score']) . '</span></td></tr>' . "\r\n";
	}
	// Print scoring details if get-parameter present.
	if (isset($_GET['details']))
	{
		foreach($team['findings'] as $item)
		{
			// Print the rows for found artefacts.
			$row_trophy = "";
			if ($item[0] >= 1000)
			{
				$row_trophy = '<i class="fa fa-diamond" style="color:#AAF;"></i>';
			}
			elseif ($item[0] >= 500)
			{
				$row_trophy = '<i class="fa fa-trophy" style="color:gold;"></i>';
			}
			elseif ($item[0] >= 200)
			{
				$row_trophy = '<i class="fa fa-star" style="color:silver;"></i>';
			}
			else
			{
				$row_trophy = '<i class="fa fa-heart" style="color:pink;"></i>';
			}
			print '<tr><td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;<b> '.$row_trophy.' ' . $item[0] . ' points:</b> '.$item[1].'</td></tr>';
		}
	}
}

// Get the time counter.
// Configuration: Set competition deadline or comment out the counter.
$deadline = new DateTime('2016-11-27T12:00:00');
$now = new DateTime(date("c"));
$diff = $now->diff($deadline);
$time_left = $diff->format('Competition time left: %a days %h hours %i minutes');
echo '<tr><td colspan="3"><span style="font-size:1.2em;color:#F55;">' . $time_left . '</span><span class="pull-right">';

// Show scoring details.
if (isset($_GET['details']))
{
	print '&nbsp;&nbsp;<a href="index.php" style="color:#444;font-size:1.3em;"><i class="fa fa-minus-circle"></i></a>';
}
else
{
	print '&nbsp;&nbsp;<a href="index.php?details" style="color:#444;font-size:1.3em;"><i class="fa fa-plus-circle"></i></a>';
}

echo '</span></td></tr>';

?>
	</table>
</div>
<div class="text-center">	
	<!-- Configuration: Partner logos, uncomment -->
<!--
	<img src="resources/partner1.png" style="width:90px;margin:10px;">
	<img src="resources/partner2.png" style="width:90px;margin:10px;">
-->	
	
</div>
</div>

</body>
</html>
