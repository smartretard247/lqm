<?php #$root = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT'); //get root folder for relative paths
    $lifetime = 60 * 60 * 3; //3 hours
    ini_set('session.use_only_cookies', true);
    ini_set('session.gc_probability', 1);
    ini_set('session.gc_divisor', 100);
    session_set_cookie_params($lifetime, '/'); //all paths, must be called before session_start()
    session_save_path(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/sessions'); session_start();
    date_default_timezone_set('America/New_York');
    
    if(empty($_SESSION['valid_user'])) { $_SESSION['valid_user'] = false; }
    $_SESSION['debug'] = false;
    
    #$_SESSION['rootDir'] = "/";
    $_SESSION['rootDir'] = "";
    
    $mp4Folder = "../files/!Low Quality Movies";
    if(file_exists($mp4Folder . "/MP4")) {
      $mp4Folder = "../files/!Low Quality Movies/MP4";
      $webLinks = scandir($mp4Folder);
    } else {
      if(file_exists($mp4Folder)) {
        $webLinks = scandir($mp4Folder);
      }
    }
    
    $onionFolder = "../files/!Low Quality Movies/Onion";
    if(file_exists($onionFolder . "/MP4")) {
      $onionFolder = "../files/!Low Quality Movies/Onion/MP4";
      $onionLinks = scandir($onionFolder);
    } else {
      if(file_exists($onionFolder)) {
        $onionLinks = scandir($onionFolder);
      }
    }
?>

<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<html>
  <head>
    <meta charset="UTF-8">
    <title>Low Quality Movies</title>
  </head>
    <body>
      <div id="main">
        <?php if($_SESSION['valid_user']) : ?>
        
        <h2><a href="http://uj3wazyk5u4hnvtk.onion/">Low Quality Movies</a></h2>
        
        <table>
            <tr>
                <th>Web</th>
                <th>Onion</th>
                <th>IMDB</th>
            </tr>
            
            <?php
            $i = 0; 
            
            if($webLinks && $onionLinks) :
              foreach($webLinks as $link) : ?>
                <?php if($link != "." && $link != ".." && $link != "Onion") :
                  $name = substr($link, 0, strpos($link, " -"));
                  $linkContents = str_ireplace("IDList=","",file_get_contents("$mp4Folder/$link", 0, NULL, 79));
                  $onionContents = str_ireplace("IDList=","",file_get_contents("$onionFolder/$onionLinks[$i]", 0, NULL, 78));
                  $imdbLink2 = "https://www.imdb.com/find?s=all&q=$name&ref_=nv_sr_sm";
                  $imdbLink = str_replace(" (720p)", "", $imdbLink2);
                  ?>
                  <tr>
                    <td><?php echo "<a href='$linkContents' target='_blank'>" . str_replace(".url","",$link) . "</a>"; ?></td>
                    <td><?php echo "<a href='$onionContents' target='_blank'>" . str_replace(".url","",$onionLinks[$i]) . "</a>"; ?></td>
                    <td><?php echo "<a href='$imdbLink' target='_blank'>" . str_replace(".url","",$onionLinks[$i]) . "</a>"; ?></td>
                  </tr>
                <?php endif;
                
                if($link != "Onion") {
                  $i++;
                }
                ?>
              <?php endforeach;
            else : ?>
              <tr>
                <td colspan="3">No low quality movies found.</td>
              </tr>
            <?php endif; ?>
            
            <tr>
                <td></td>
                <td></td>
            </tr>
        </table>
        
        <?php else : ?>
            <form action="../core/login.php?return=lqm" method="post">
                <table id="login">
                    <tr>
                        <th colspan="2">Login Information</th>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: right;">
                            Username: <input name="Username" type="text"><br/>
                            Password: <input name="ThePassword" type="password"><br/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" ><input type="submit" value="Login"/></td>
                    </tr>
                </table>
            </form>
        <?php endif; ?>
    </div>
  </body>
</html>