<?php
include "top.php";
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1 Initialize variables
//
// SECTION: 1a.
// We print out the post array so that we can see our form is working.
if ($debug) {  // later you can uncomment the if statement
    print "<p>Post Array:</p><pre>";
    print_r($_POST);
    print "</pre>";
}

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1b Security
//
// define security variable to be used in SECTION 2a.
$thisURL = $domain . $phpSelf;
//
//
//
////%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1c form variables
//
// Initialize variables one for each form element
// in the order they appear on the form

$firstName = "";
$email = "";
$witnesses = "witnesses";
$probed = true;
$brain_washed = false;
$sleeping = false;
$totalChecked = 1;
$encounter = "UFO";

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1d form error flags
//
// Initialize Error Flags one for each form element we validate
// in the order they appear in section 1c.

$firstNameERROR = false;
$emailERROR = false;
$commentERROR = false;
$locationERROR = false;
$ufoERROR = false;
$activityERROR = false;
$encounterError = false;

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1e misc variables
//
// create array to hold error messages filled (if any) in 2d displayed in 3c.
$errorMsg = array();
$dataRecord = array();
// array used to hold form values that will be written to a CSV file
// 


//
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2 Process for when the form is submitted
//
if (isset($_POST["btnSubmit"])) {

    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    // SECTION: 2a Security

    if (!securityCheck($thisURL)) {
        $msg = "<p>Sorry you cannot access this page.";
        $msg.= " Security breach detected and reportd.<p>";
        die($msg);
    }





    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
     // SECTION: 2b Sanitize (clean) data 
    // remove any potential JavaScript or html code from users input on the
    // form. Note it is best to follow the same order as declared in section 1c.



    $firstName = htmlentities($_POST["txtFirstName"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $firstName;
    $email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL);
    $dataRecord[] = $email;
    $description = htmlentities($_POST["txtDescription"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $description;
    $location = htmlentities($_POST["txtLocation"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $location;
    $witnesses = htmlentities($_POST["radwitnesses"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $witnesses;
    if (isset($_POST["chkprobed"])) {
        $probed = true; 
        $dataRecord[] = "probing";
        $totalChecked++;
    } else {
        $probed = false;
    }
    
    if (isset($_POST["chkbrainwashed"])) {
        $brain_washed = true;
        $dataRecord[] = "brain washing";
        $totalChecked++;
    } else {
        $brain_washed = false;
    }
    if (isset($_POST["chkSleeping"])) {
        $sleeping = true;
        $dataRecord[] = "they were sleeping";
        $totalChecked++;
    } else {
        $sleeping = false;
    }
    if(isset($_POST["chkNone"])){
        $none = true;
        $dataRecord[] = " nothing";
        $totalChecked++;
    }else{
        $none = false;
    }
    $encounter = htmlentities($_POST["lstEncounter"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $encounter;
    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    // SECTION: 2c Validation
    //
    // Validation section. Check each value for possible errors, empty or
    // not what we expect. You will need an IF block for each element you will
    // check (see above section 1c and 1d). The if blocks should also be in the
    // order that the elements appear on your form so that the error messages
    // will be in the order they appear. errorMsg will be displayed on the form
    // see section 3b. The error flag ($emailERROR) will be used in section 3c.
    

    if ($firstName == "") {
        $errorMsg[] = "Please enter your first name";
        $firstNameERROR = true;
    } elseif (!verifyAlphaNum($firstName)) {
        $errorMsg = "Your first name appears to have some extra characters";
        $firstNameError = true;
    }

    if ($email == "") {
        $errorMsg[] = "Please enter your email address";
        $emailERROR = true;
    } elseif (!verifyEmail($email)) {
        $errorMsg[] = "Your email address appears to be incorrect";
        $emailERROR = true;
    }
    if (!strlen(trim($description))){
        $errorMsg[] = "Please include a description of your encounter";
        $commentERROR = true;
    }
    else if ($description != "") {
        if (!verifyAlphaNum($description)) {
            $errorMsg[] = "Your comments appear to have extra characters that are not allowed";
            $commentsERROR = true;
        }
    }
    if(!strlen(trim($location))){
        $errorMsg[] = "Please include a location of your encounter";
        $locationERROR = true;
    }
    else if (!verifyAlphaNum($location)){
        $errorMsg[] = "Your location appears to have extra characters that are not allowed";
        $locationERROR = true;
        
    }
    if ($totalChecked < 1) {
        $errorMsg[] = "Please choose at least one thing that happened during your encounter";
        $activityERROR = true;
    }
    if ($encounter == "") {
        $errorMsg[] = "Please choose the kind of encounter";
        $encounterError = true;
    }


//    print $email;
    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    // SECTION: 2d Process Form - Passed Validation
    //
    // Process for when the form passes validation (the errorMsg array is empty)
    //

    if (!$errorMsg) {
        if ($debug)
            print "<p>Form is valid</p>";



        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        // SECTION: 2e Save Data
        //
        // This block saves the data to a CSV file.   







        $fileExt = ".csv";
        $myFileName = "data/encounter";
        $filename = $myFileName . $fileExt;
        if ($debug) {
            print "\n\n<p>filename is" . $filename;
        }
        // now we just open the file for append
        $file = fopen($filename, 'a');
//        function array_2_csv($dataRecord) {
//            $csv = array();
//            foreach ($dataRecord as $item) {
//                if (is_array($item)) {
//                    $csv[] = array_2_csv($item);
//                } else {
//                   $csv[] = $item;
//                }
//            }
//            return implode(',', $csv);
//        } 
//        $newArray = array_2_csv($dataRecord);
//        print_r($newArray);
        // write the forms informations
        fputcsv($file, $dataRecord);

        // close the file
        fclose($file);


        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        // SECTION: 2f Create message
        //
        // build a message to display on the screen in section 3a and to mail
        // to the person filling out the form (section 2g).

        $message = '<h2>Your information.</h2>';

        foreach ($_POST as $key => $value) {
            $message .= "<p>";

            $camelCase = preg_split('/(?=[A-Z])/', substr($key, 3));

            foreach ($camelCase as $one) {
                $message .= $one . " ";
            }

            $message .= " = " . htmlentities($value, ENT_QUOTES, "UTF-8") . "</p>";
            $message = "Thank you for sharing your encounter with us.  We hope that you are able to find the resources you need.  There will "
                    . "be more information to come.";
            
            
        }











        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        // SECTION: 2g Mail to user
        //
        // Process for mailing a message which contains the forms data
        // the message was built in section 2f.
        $to = $email;
        $cc = "";
        $bcc = "";

        $from = "Alien Nation kgott541@aol.com";

        $todaysDate = strftime("%x");
        $subject = ": " . $todaysDate;

        $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);
    } //end form is valid
}     //end if form was submitted.
//#############################################################################
//
// SECTION 3 Display Form
//
?>

<article>
    <h2>Have you encountered the extraterrestrial kind?  Report your sighting here.  Please be as detailed as possible.  The more reports that we 
    can acquire, the closer we can come to intellectual contact.</h2>
<?php
//####################################
//
    // SECTION 3a. 
// 
// If its the first time coming to the form or there are errors we are going
// to display the form.
if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) {

    print "<h2>Thank you for detailing your encounter.</h2>";

    print "<p>We are now one step closer to contact!</p>";

} else {

    print "<h2>Please detail your encounter</h2>";

    //####################################
    //
        // SECTION 3b Error Messages
    //
        // display any error messages before we print out the form





    if ($errorMsg) {
        print '<div id="errors">' . "\n";
        print "<h2>Your form has the following mistakes that need to be fixed.</h2>\n";
        print "<ol>\n";

        foreach ($errorMsg as $err) {
            print "<li>" . $err . "</li>\n";
        }

        print "</ol>\n";
        print "</div>\n";
    }








    //####################################
    //
        // SECTION 3c html Form
    //
        /* Display the HTML form. note that the action is to this same page. $phpSelf
      is defined in top.php
      NOTE the line:
      value="<?php print $email; ?>
      this makes the form sticky by displaying either the initial default value (line ??)
      or the value they typed in (line ??)
      NOTE this line:
      <?php if($emailERROR) print 'class="mistake"'; ?>
      this prints out a css class so that we can highlight the background etc. to
      make it stand out that a mistake happened here.
     */
    ?>    
        <form action="<?php print $phpSelf; ?>"
              id ="frmRegister"
              method="post">

        <fieldset class="contact">
                <legend>Contact Information</legend>        

                <label for="txtFirstName">First Name
                    <input
        <?php if ($firstNameERROR) print 'class = "mistake"'; ?>
                           id ="txtFirstName"
                           maxlength="45"
                           name="txtFirstName"
                           onfocus="this.select()"
                           placeholder="Enter your first name"
                           tabindex="100"
                           type="text"
                           value="<?php print $firstName; ?>"
                           >
                </label>
                

                <label for="txtEmail">Email
                    <input
    <?php if ($emailERROR) print 'class = "mistake"'; ?>
                        id ="txtEmail"
                        maxlength="45"
                        name="txtEmail"
                        onfocus="this.select()"
                        placeholder="Enter a valid email addresss"
                        tabindex="120"
                        type="text"
                        value="<?php print $email; ?>"
                        >
                </label>
            </fieldset>
            <fieldset class ="textarea">
                <label class="required" for="txtDescription">Description of your Encounter</label>
                <textarea placeholder="I was walking to the teddy bear store when I saw a bright flash of light..."
                    <?php if ($commentERROR) print 'mistake'; ?>
                    id="txtDescription"
                    name="txtDescription"
                    onfocus="this.select()"
                    style="width: 30em; height: 8em;"
                    tabindex="200"><?php print $description; ?></textarea>
            </fieldset>
            <fieldset class ="textarea">
                <label class="required" for="txtLocation">Location of your Encounter</label>
                <textarea placeholder="South-side of Compton on the corner of Alien st. & Nation ave. "
                    <?php if ($locationERROR) print 'mistake'; ?>
                    id="txtLocation"
                    name="txtLocation"
                    onfocus="this.select()"
                    style="width: 30em; height: 8em;"
                    tabindex="250"><?php print $location; ?></textarea>
            </fieldset>
            <fieldset class ="radio <?php if ($ufoERROR) print 'mistake'; ?>">
                <legend>How many witnesses were there? </legend>    
                <label><input <?php if ($witnesses == "zero") print 'checked '; ?>  
                        id="rad0"
                        name="radwitnesses"
                        tabindex="330"
                        type="radio"
                        value="zero">0</label>
                <label><input <?php if ($witnesses == "one") print 'checked '; ?>  
                        id="radone"
                        name="radwitnesses"
                        tabindex="340"
                        type="radio"
                        value="one">1</label>
                <label><input <?php if ($witnesses == "two") print 'checked '; ?>  
                        id="radtwo"
                        name="radwitnesses"
                        tabindex="340"
                        type="radio"
                        value="two to five">2-5</label>
                <label><input <?php if ($witnesses == "three") print 'checked '; ?>  
                        id="radthree"
                        name="radwitnesses"
                        tabindex="340"
                        type="radio"
                        value="6 or more">6+</label>
            </fieldset> 
            <fieldset class="checkbox <?php if ($activityERROR) print 'mistake'; ?>">
                <legend>Did any of the following occur during your encounter?:</legend>
                <label><input <?php if ($probed) print " checked "; ?>
                        id="chkprobed"
                        name="chkprobed"
                        tabindex="420"
                        type="checkbox"
                        value="probed"> Probed</label>
                <label><input <?php if ($brain_washed) print " checked "; ?>
                        id="chkbrain_washed"
                        name="chkbrain_washed"
                        tabindex="430"
                        type="checkbox"
                        value="brain_washed"> Brain Washed</label>
                <label><input <?php if ($sleeping) print " checked "; ?>
                        id="chkSleeping"
                        name="chkSleeping"
                        tabindex="440"
                        type="checkbox"
                        value="Sleeping"> I was sleeping</label>
                <label><input <?php if ($none) print " checked "; ?>
                        id="chkNone"
                        name="chkNone"
                        tabindex="440"
                        type="checkbox"
                        value="None"> Nothing Happened</label>
            </fieldset>
            <fieldset class="listbox <?php if ($encounterERROR) print 'mistake'; ?>">
                <label class="required" for="lstEncounter">What Kind of encounter did you have?</label>
                <select id="lstEncounter"
                        name="lstEncounter"
                        tabindex="520" >
                        <option <?php if($encounter =="UFO") print "selected"; ?>
                        value ="UFO">UFO</option>
                    <option <?php if($encounter=="Physical Encounter") print "selected"; ?>
                        value="Physical Encounter">Physical Encounter</option>
                    <option <?php if($encounter=="Observation") print " selected"; ?>
                        value="Observation">Observation of animate beings</option>
                    <option <?php if($encounter=="Abduction") print " selected"; ?>
                        value="Abduction">Abduction</option>
                </select>
            </fieldset>

            <fieldset class="buttons">
                <legend></legend>
                <input class="button" id="btnSubmit" name="btnSubmit" tabindex="900" type="submit" value="Report">

            </fieldset><!-- ends buttons -->
            
        </form>

    <?php
}//end submit
?>


</article>

<?php include "footer.php"; ?>

</body>
</html>