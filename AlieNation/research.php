<?php
include "top.php";

$myFileName="data/encounter";

$fileExt=".csv";

$filename = $myFileName . $fileExt;

$file=fopen($filename, "r");

while(!feof($file)){
        $records[]=fgetcsv($file);
    }

foreach ($records as $key => $name) {
    if($name[0] == ""){
        return;
    }
    $id = preg_replace('/\s+/', '', $name[0]);;
    print "<article id ='".$id."'>";
    print "<h1>".$name[0]."'s encounter.</h1>";
    print "<div class='divider'>";
    $size = count($name);
    $count = 0;
    print "</div>";
    print "<div>";
    print $name[0] . " reported ";
    if($name[$size - 1] == "UFO") print "a UFO sighting located in " . $name [3] . ".  ";
    elseif($name[$size - 1] == "Physical Encounter") print "a physical encounter located in " . $name [3] . ".  ";
    elseif($name[$size - 1] == "Observation") print "an obsevation of an animate object located in " . $name [3] . ".  ";
    elseif($name[$size - 1] == "Abduction") print "an abduction located in " . $name [3] . ".  ";
    print $name[0] . " claims that there ";
    if ($name[4] != "one") print "were " . $name[4] . " witnesses"; else print "was " . $name[4] . " witness"; print " at the time.  ";
    print $name[0] . " quotes: <q>" . $name[2] . "</q>  ";
    if ($size == 6){
        print $name[0] . " says not a whole lot else happened.  ";
    }
    if ($size == 7){
        
        print $name[0] . " says that the encounter involved ". $name[5] . ".  ";
    }
    elseif($size == 8){
        print $name[0] . " says that the encounter involved ". $name[5] . " and " . $name[6] . ".  ";
    }
    elseif($size == 9){
        print $name[0] . " says that the encounter involved ". $name[5] . ", " . $name[6] . " and  " . $name[7] . ".  ";
    }
    print "<br>";
    print "If you care to contact " . $name[0] . " you can reach them at this email: " . "<a href='mailto:".$name[1] ."?Subject=Your_Alien_Nation_encounter' target='_top'>".$name[1]."</a>";
    print "</div>";
     print "</article>";
}


fclose($file);


