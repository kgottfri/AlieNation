 <!-- ######################     Main Navigation   ########################## -->
<nav>
    <ol>
<!--        <li>Alien Nation</li>-->
        <?php
//        include "header.php";
        
        //Home/Alien
        print '<li class="';
        if($path_parts['filename'] == "index"){
            print 'active page';
        }
        print '">';
        print "<a href='index.php'><h1>Alien Nation</h1></a>";
        print'</li>';
        
//        Home/Index
        print '<li class="';
        if($path_parts['filename'] == "index"){
            print 'active page';
        }
        print '">';
        print "<a href='index.php'><p>Home</p></a>";
        print'</li>';
        
//      Info Page  
        print '<li class="';
        if($path_parts['filename'] == "new"){
            print 'active page';
        }
        print '">';
        print "<a href='new.php'><p>What's New</p></a>";
        print'</li>';
        
//        Research Page
        print '<li class="';
        if($path_parts['filename'] == "research"){
            print 'active page';
        }
        print '">';
        print "<a href='research.php'><p>Research</p></a>";
        print'</li>';
        
//       Form Page
        print '<li class="';
        if($path_parts['filename'] == "form"){
            print 'active page';
        }
        print '">';
        print "<a href='form.php'><p>Join The Nation</p></a>";
        print'</li>';
         // Report page
        print '<li class="';
        if($path_parts['filename'] == "report"){
            print 'active page';
        }
        print '">';
        print "<a href='report.php'><p>Report an Encounter</p></a>";
        print'</li>';
        
//        About Page
        print '<li class="';
        if($path_parts['filename'] == "about"){
            print 'active page';
        }
        print '">';
        print "<a href='about.php'><p>About The Nation</p></a>";
        print'</li>';
    ?>
    </ol>
</nav>