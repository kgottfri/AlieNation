<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Alien Nation</title>
        
        <meta charset="utf-8">
        <meta name="author" content="Kevin Gottfried">
        <meta name="description" content="You are the Alien" >
        <meta name ="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="alien.css">
        <link rel="icon" type ="image/jpg" href="//kgottfri.w3.uvm.edu/AlieNation/favicon.png">
    
        <?php
        $debug = false;
        
        //HI
        //HEY
        if (isset($_GET["debug"])){
            $debug = true;
        }
        
// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// PATH SETUP
//
        
        $domain = "//";
        
        $server = htmlentities($_SERVER['SERVER_NAME'], ENT_QUOTES, "UTF_8");
        
        $domain .= $server;
    
        $phpSelf = htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, "UTF-8");
        
        $path_parts = pathinfo($phpSelf);
        
        if ($debug) {
            print "<p>Domain: " . $domain;
            print "<p>php Self: " . $phpSelf;
            print "<p>Path Parts<pre>";
            print_r($path_parts);
            print "</pre></p>";
        }
        
// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// include all libraries
//
// 
//
//       
        print "<!-- include libraries -->";
        
        require_once('lib/security.php');
        
        
        if ($path_parts['filename'] == "report") {
            print "<!-- include form libraries -->";
            include "lib/validation-functions.php";
            include "lib/mail-message.php";
        }
        
        
        
        
        
        
        
        print "<!-- finished including libraries -->";
        ?>
        
    </head>
    <!-- ################ body section ######################### -->
    
    <?php
        
        
    print '<body id="' . $path_parts['filename'] . '">';
    include "nav.php";

    
    if ($debug) {
        print "<p>DEBUG MODE IS ON</p>";
    }
 ?>   