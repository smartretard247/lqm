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
    if(file_exists($mp4Folder)) {
      $mergedWebLinks = array_diff(scandir($mp4Folder), array('..', '.', 'MP4', 'Onion'));
    }
      
    if(file_exists($mp4Folder . "/MP4")) {
      $webLinks = array_diff(scandir($mp4Folder . "/MP4"), array('..', '.', 'MP4', 'Onion'));
      foreach($webLinks as $link) {
        array_push($mergedWebLinks, "/MP4/$link");
      }
    }
    
    $onionFolder = "../files/!Low Quality Movies/Onion";
    if(file_exists($onionFolder)) {
      $mergedOnionLinks = array_diff(scandir($onionFolder), array('..', '.', 'MP4', 'Onion'));
    }
    
    if(file_exists($onionFolder . "/MP4")) {
      $onionLinks = array_diff(scandir($onionFolder . "/MP4"), array('..', '.', 'MP4', 'Onion'));
      foreach($onionLinks as $link) {
        array_push($mergedOnionLinks, "/MP4/$link");
      }
    }
    
    if($mergedWebLinks && $mergedOnionLinks) {
      //sort($mergedWebLinks);
      //sort($mergedOnionLinks);
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
            
            <?php if($mergedWebLinks && $mergedOnionLinks) :
              $linkContentsA = array();
              $onionContentsA = array();
              $imdbLinkA = array();
              
              foreach($mergedWebLinks as $link) :
                $linkContents = str_ireplace("IDList=","",file_get_contents("$mp4Folder/$link", 0, NULL, 79));
                
                $name = str_replace("/MP4/", "", substr($link, 0, strpos($link, " -")));
                $imdbLink = str_replace(" (720p)", "", "https://www.imdb.com/find?s=all&q=$name&ref_=nv_sr_sm");

                $title = str_replace("/MP4/", "", str_replace(".url","",$link));
                $linkContentsA["$title"] = $linkContents;
                $imdbLinkA["$title"] = $imdbLink;
              endforeach;
              
              foreach($mergedOnionLinks as $link) :
                $title = str_replace("/MP4/", "", str_replace(".url","",$link));
                $onionContents = str_ireplace("IDList=","",file_get_contents("$onionFolder/$link", 0, NULL, 78));
                $onionContentsA["$title"] = $onionContents;
              endforeach;
            endif;
            
            if($linkContentsA) :
              asort($linkContentsA);
              asort($onionContentsA);
              asort($imdbLinkA);
            
              foreach($linkContentsA as $key => $link) : ?>
                <tr>
                  <td><?php echo "<a href='$linkContentsA[$key]' target='_blank'>" . $key . "</a>"; ?></td>
                  <td><?php echo "<a href='$onionContentsA[$key]' target='_blank'>" . $key . "</a>"; ?></td>
                  <td><?php echo "<a href='$imdbLinkA[$key]' target='_blank'>" . $key . "</a>"; ?></td>
                </tr>
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