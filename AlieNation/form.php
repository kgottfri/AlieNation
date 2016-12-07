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
$gender = "Female";

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1d form error flags
//
// Initialize Error Flags one for each form element we validate
// in the order they appear in section 1c.

$firstNameERROR = false;
$emailERROR = false;
$genderERROR = false;

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
// have we mailed the information to the user?
$mailed = false;
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
    $gender = htmlentities($_POST["radGender"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $gender;
    
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
        $errorMsg = "Your first name appears to have some extra characters.";
        $firstNameError = true;
    }

    if ($email == "") {
        $errorMsg[] = "Please enter your email address";
        $emailERROR = true;
    } elseif (!verifyEmail($email)) {
        $errorMsg[] = "Your email address appears to be incorrect";
        $emailERROR = true;
    }
//    if($gender != "Male" OR $gender != "Female"){
//        $errorMsg[] = "Please choose a gender";
//        $genderERROR = true;
//    }

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
        $myFileName = "data/registration";
        $filename = $myFileName . $fileExt;
        if ($debug) {
            print "\n\n<p>filename is" . $filename;
        }
        // now we just open the file for append
        $file = fopen($filename, 'a');

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
            $message = "Glad you decided to join the nation.  Stay tuned for more information";
            
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
    <h2>Don't be under-prepared for the invasion.  </h2>
<?php
//####################################
//
    // SECTION 3a. 
// 
// If its the first time coming to the form or there are errors we are going
// to display the form.
if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) {

    print "<h2>Thank you for providing your information.</h2>";

    print "<p>For your records a copy of this data has ";




    if (!$mailed) {
        print "not ";
    }
    print "been sent:</p>";
    print "<p>To: " . $email . "</p>";








    print $message;
} else {

    print "<h2>Join The Army</h2>";
    print "<p>Think.  Research.  Follow.</p>";

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

                <label class="required" for="txtFirstName">First Name
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


                <label class="required" for="txtEmail">Email
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
            <fieldset class ="radio <?php if ($genderERROR) print 'mistake'; ?>">
                <legend>What is your gender?</legend>    
                <label><input <?php if ($gender == "Male") print 'checked '; ?>  
                        id="radGenderMale"
                        name="radGender"
                        tabindex="330"
                        type="radio"
                        value="Male">Male</label>
                <label><input <?php if ($gender == "Female") print 'checked '; ?>  
                        id="radGenderFemale"
                        name="radGender"
                        tabindex="340"
                        type="radio"
                        value="Female">Female</label>
                <label><input <?php if ($gender == "Other") print 'checked '; ?>  
                        id="radGenderOther"
                        name="radGender"
                        tabindex="340"
                        type="radio"
                        value="Other">Other</label>
            </fieldset> 
            <fieldset class="buttons">
                <legend></legend>
                <input class="button" id="btnSubmit" name="btnSubmit" tabindex="900" type="submit" value="Join">

            </fieldset><!-- ends buttons -->
        </form>

    <?php
}//end submit
?>


</article>

<?php include "footer.php"; ?>

</body>
</html>