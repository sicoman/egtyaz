<?php
/** 
 * Website Exporter - use to zip directories recursively
 * and export SQL database for WordPress Joomla Drupal
 * @author Artyom Babiy, Concierge Department, Namecheap Inc. 
 * v.0.4
 */

// Main config
ini_set('memory_limit','256M');
#error_reporting(0);
set_time_limit(0); // sets the script to run "forever"
ignore_user_abort(1); // sets the script to keep running in the background

// Main variables
$directory = getcwd();
$directory_regex = str_replace('/', '\/', $directory);
$wp_admin = $directory_regex . '\/wp-admin';
$wp_includes = $directory_regex . '\/wp-includes';
$wp_themes = $directory_regex . '\/wp-content\/themes';
$website_backup_name = $_SERVER['SERVER_NAME'] . '-' . time() . '_website.zip';

/*
 ********************************************************************
 **************************** Functions *****************************
 ********************************************************************
 */

/**
 * Zip directory recursively
 */
function zipData($source, $destination, $excludes1, $excludes2) {
    global $wp_admin;
    global $wp_includes;
    global $wp_themes;

    if (empty($excludes1) || trim($excludes1) == '') {
        $excludes1 = 'placeholder_string1';
    }
    if (empty($excludes2) || trim($excludes2) == '') {
        $excludes2 = 'placeholder/string1';
    }
    
    $excl_files_array_0 = trim($excludes1) . ' .zip_exclude_dirs' . ' .zip_exclude_files';
    $excl_files_array_1 = explode(' ', $excl_files_array_0);
    $excl_files_array_2 = str_replace('.', '\.', $excl_files_array_1);
    $excl_files_array = preg_replace('/$/', '$', $excl_files_array_2);

    $excl_dirs_array_1 = explode(' ', trim($excludes2));
    $excl_dirs_array = str_replace('/', '\/', $excl_dirs_array_1);

    $excl_array = array_merge($excl_files_array, $excl_dirs_array);
    $files_reg = implode("|", $excl_files_array);
    $dirs_reg = implode("|", $excl_dirs_array);
    $together = implode("|", $excl_array);
    
    $zip = new ZipArchive();
    if ($zip->open($destination, ZIPARCHIVE::CREATE)) {
        $exclude_files = array(realpath($destination), realpath('zip.php'));
        $source = realpath($source);
        $iterator = new RecursiveDirectoryIterator($source);
        $iterator->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);
        foreach ($files as $file) {
            $file = realpath($file);
            if (is_dir($file) === true && preg_match("/$wp_admin/i", $file)) {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            } elseif (is_dir($file) === true && preg_match("/$wp_includes/i", $file)) {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            } elseif (is_dir($file) === true && preg_match("/$wp_themes/i", $file)) {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));

            } elseif (is_dir($file) === true && !preg_match("/$wp_admin/i", $file) && !preg_match("/$wp_includes/i", $file) && !preg_match("/$wp_themes/i", $file) && !preg_match("/$dirs_reg/i", $file)) {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));

            } elseif (is_file($file) === true && !in_array($file, $exclude_files) && preg_match("/$wp_admin/i", $file)) {
                $zip->addFile($file, str_replace($source . '/', '', $file));
            } elseif (is_file($file) === true && !in_array($file, $exclude_files) && preg_match("/$wp_includes/i", $file)) {
                $zip->addFile($file, str_replace($source . '/', '', $file));
            } elseif (is_file($file) === true && !in_array($file, $exclude_files) && preg_match("/$wp_themes/i", $file)) {
                $zip->addFile($file, str_replace($source . '/', '', $file));

            } elseif (is_file($file) === true && !in_array($file, $exclude_files) && !preg_match("/$wp_admin/i", $file) && !preg_match("/$wp_includes/i", $file) && !preg_match("/$wp_themes/i", $file) && !preg_match("/$together/i", $file)) {
                $zip->addFile($file, str_replace($source . '/', '', $file));
            }
        }
    }
    return $zip->close();
}

/**
 * Transfer backup to remote host
 */
function remoteFTP() {
    $remote_file = $_POST["file"];
    $ftp_host = $_POST["server"]; 
    $ftp_user_name = $_POST["user"];
    $ftp_user_pass = $_POST["passw"];
    $local_file = $_POST["file"];
    $connect_it = ftp_connect($ftp_host);
    $login_result = ftp_login($connect_it, $ftp_user_name, $ftp_user_pass);
    ftp_pasv($connect_it, true);
    if ( ftp_put($connect_it, $remote_file, $local_file, FTP_BINARY) ) {
        echo "Successfully transferred $local_file ";
    }
    else {
        echo "Transfer failed. Double-check the details ";
    }
    ftp_close($connect_it);
}

/**
 * Get folder size
 */
function dirSize($directory) {
    global $wp_admin;
    global $wp_includes;
    global $wp_themes;
    $size = 0;
    $excl_size = 0;
    $excl_inodes = 0;
    $excl_array = array();
    $excl_size += filesize(realpath('zip.php'));
    $excl_inodes += 1;
    $excl_array[] = realpath('zip.php');
    
    if (file_exists('.zip_exclude_files')) {
        $check_files = file_get_contents('.zip_exclude_files');
        if (empty($check_files) || trim($check_files) == '') {
            $default_files = 'placeholder_string1 .zip_exclude_files .zip_exclude_dirs';
        } else {
             $default_files = trim(file_get_contents('.zip_exclude_files')) . ' placeholder_string1 .zip_exclude_files .zip_exclude_dirs';
        }    
    } else {
        $default_files = ".tar.gz .gz .tar .zip .wpress error_log cache-config.php advanced-cache.php .zip_exclude_files .zip_exclude_dirs";
    }
    
    if (file_exists('.zip_exclude_dirs')) {
        $check_dirs = file_get_contents('.zip_exclude_dirs');
        if (empty($check_dirs) || trim($check_dirs) == '') {
            $default_dirs = 'placeholder/string1';
        } else {
            $default_dirs = trim(file_get_contents('.zip_exclude_dirs')) . ' placeholder/string1';
        }
    } else {
        $default_dirs = "wp-content/ai1wm-backups wp-content/cache wp-content/w3tc-config wp-content/upgrade wp-content/updraft wp-content/plugins/all-in-one-wp-migration wp-content/plugins/jetpack wp-content/plugins/updraftplus wp-content/plugins/w3-total-cache wp-content/plugins/wp-super-cache wp-content/plugins/backwpup wp-content/plugins/wp-clone wp-content/plugins/duplicator wp-content/plugins/wp-clone-by-wp-academy wp-content/uploads/wp-clone wp-snapshots";
    }

    $excl_files_array_1 = explode(' ', $default_files);
    $excl_files_array_2 = str_replace('.', '\.', $excl_files_array_1);
    $excl_files_array = preg_replace('/$/', '$', $excl_files_array_2);

    $excl_dirs_array_1 = explode(' ', $default_dirs);
    $excl_dirs_array = str_replace('/', '\/', $excl_dirs_array_1);

    $all_excl_array = array_merge($excl_files_array, $excl_dirs_array);
    $files_reg = implode("|", $excl_files_array);
    $dirs_reg = implode("|", $excl_dirs_array);
    $together = implode("|", $all_excl_array);

    $iterator = new RecursiveDirectoryIterator($directory);
    $iterator->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);
    foreach(new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST) as $file) {
        $size += $file->getSize();
        $file = realpath($file);
        if (is_dir($file) === true && !preg_match("/$wp_admin/i", $file) && !preg_match("/$wp_includes/i", $file) && !preg_match("/$wp_themes/i", $file) && preg_match("/$dirs_reg/i", $file)) {
            $excl_size += filesize($file);
            $excl_inodes += 1;
            $excl_array[] = $file;
        } elseif (is_file($file) === true && !preg_match("/$wp_admin/i", $file) && !preg_match("/$wp_includes/i", $file) && !preg_match("/$wp_themes/i", $file) && preg_match("/$together/i", $file)) {
            $excl_size += filesize($file);
            $excl_inodes += 1;
            $excl_array[] = $file;
        }
    }
    return array($size, $excl_size, $excl_inodes, $excl_array);
}

/**
 * Convert bytes to human numbers
 */
function humanFilesize($bytes, $decimals = 2) {
    $sz = 'BKMGTP';
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}

/**
 * Get inode usage
 */
function getFileCount($path) {
    $i_size = 0;
    $ignore = array('..', '.');
    $files = scandir($path);
    foreach($files as $t) {
        if(in_array($t, $ignore)) continue;
        if (is_dir(rtrim($path, '/') . '/' . $t)) {
            $i_size += getFileCount(rtrim($path, '/') . '/' . $t);
            $i_size++;
        } else {
            $i_size++;
        }   
    }
    return $i_size;
}

/**
 * Export database
 */
function exportDatabase() {
    define("DB_USER", $_POST["user"]);
    define("DB_PASSWORD", $_POST["password"]);
    define("DB_NAME", $_POST["name"]);
    define("DB_HOST", $_POST["host"]);
    define("BACKUP_DIR", '.');
    define("TABLES", '*');
    define("CHARSET", 'utf8');
    define("GZIP_BACKUP_FILE", false); 

    class Backup_Database {
        var $host;
        var $username;   
        var $passwd;   
        var $dbName;  
        var $charset;   
        var $conn; 
        var $backupDir;   
        var $backupFile;   
        var $gzipBackupFile;
    
        public function __construct($host, $username, $passwd, $dbName, $charset = 'utf8') {
            $this->host            = $host;
            $this->username        = $username;
            $this->passwd          = $passwd;
            $this->dbName          = $dbName;
            $this->charset         = $charset;
            $this->conn            = $this->initializeDatabase();
            $this->backupDir       = BACKUP_DIR ? BACKUP_DIR : '.';
            $this->backupFile      = $this->dbName . '-' . time() . '_db.sql';
            $this->gzipBackupFile  = defined('GZIP_BACKUP_FILE') ? GZIP_BACKUP_FILE : true;
        }

        protected function initializeDatabase() {
            try {
                if (!empty($_POST['port'])) {
                    $conn = mysqli_connect($this->host, $this->username, $this->passwd, $this->dbName, $_POST['port']);
                } elseif (empty($_POST['port'])) {
                    $conn = mysqli_connect($this->host, $this->username, $this->passwd, $this->dbName);
                }
                if (mysqli_connect_errno()) {
                    throw new Exception('ERROR connecting database: ' . mysqli_connect_error());
                    die();
                }
                if (!mysqli_set_charset($conn, $this->charset)) {
                    mysqli_query($conn, 'SET NAMES '.$this->charset);
                }
            } catch (Exception $e) {
                print_r($e->getMessage());
                die();
            }
            return $conn;
        }
   
        public function backupTables($tables = '*') {
            try {
                if($tables == '*') {
                    $tables = array();
                    $result = mysqli_query($this->conn, 'SHOW TABLES');
                    while($row = mysqli_fetch_row($result)) {
                        $tables[] = $row[0];
                    }
                } else {
                    $tables = is_array($tables) ? $tables : explode(',', str_replace(' ', '', $tables));
                }
                $sql = 'CREATE DATABASE IF NOT EXISTS `'.$this->dbName."`;\n\n";
                $sql .= 'USE `'.$this->dbName."`;\n\n";
                foreach($tables as $table) {
                    $this->obfPrint("Backing up `".$table."` table...".str_repeat('.', 50-strlen($table)), 0, 0);
                    $sql .= 'DROP TABLE IF EXISTS `'.$table.'`;';
                    $row = mysqli_fetch_row(mysqli_query($this->conn, 'SHOW CREATE TABLE `'.$table.'`'));
                    $sql .= "\n\n".$row[1].";\n\n";
                    $row = mysqli_fetch_row(mysqli_query($this->conn, 'SELECT COUNT(*) FROM `'.$table.'`'));
                    $numRows = $row[0];
                    $batchSize = 1000; 
                    $numBatches = intval($numRows / $batchSize) + 1; 
                    for ($b = 1; $b <= $numBatches; $b++) {
                        $query = 'SELECT * FROM `'.$table.'` LIMIT '.($b*$batchSize-$batchSize).','.$batchSize;
                        $result = mysqli_query($this->conn, $query);
                        $numFields = mysqli_num_fields($result);
                        for ($i = 0; $i < $numFields; $i++) {
                            $rowCount = 0;
                            while($row = mysqli_fetch_row($result)) {
                                $sql .= 'INSERT INTO `'.$table.'` VALUES(';
                                for($j=0; $j<$numFields; $j++) {
                                    if (isset($row[$j])) {
                                        $row[$j] = addslashes($row[$j]);
                                        $row[$j] = str_replace("\n","\\n",$row[$j]);
                                        $sql .= '"'.$row[$j].'"' ;
                                    } else {
                                        $sql.= 'NULL';
                                    }
                                    if ($j < ($numFields-1)) {
                                        $sql .= ',';
                                    }
                                }
                                $sql.= ");\n";
                            }
                        }
                        $this->saveFile($sql);
                        $sql = '';
                    }
                    $sql.="\n\n\n";
                    $this->obfPrint(" OK");
                }
                if ($this->gzipBackupFile) {
                    $this->gzipBackupFile();
                } else {
                    $this->obfPrint('Backup file succesfully saved to ' . $this->backupFile, 1, 1);
                }
            } catch (Exception $e) {
                print_r($e->getMessage());
                return false;
            }
            return true;
        }

        protected function saveFile(&$sql) {
            if (!$sql) return false;
            try {
                if (!file_exists($this->backupDir)) {
                    mkdir($this->backupDir, 0777, true);
                }
                file_put_contents($this->backupDir.'/'.$this->backupFile, $sql, FILE_APPEND | LOCK_EX);
            } catch (Exception $e) {
                print_r($e->getMessage());
                return false;
            }
            return true;
        }
 
        protected function gzipBackupFile($level = 9) {
            if (!$this->gzipBackupFile) {
                return true;
            }
            $source = $this->backupDir . '/' . $this->backupFile;
            $dest =  $source . '.gz';
            $this->obfPrint('Gzipping backup file to ' . $dest . '... ', 1, 0);
            $mode = 'wb' . $level;
            if ($fpOut = gzopen($dest, $mode)) {
                if ($fpIn = fopen($source,'rb')) {
                    while (!feof($fpIn)) {
                        gzwrite($fpOut, fread($fpIn, 1024 * 256));
                    }
                    fclose($fpIn);
                } else {
                    return false;
                }
                gzclose($fpOut);
                if(!unlink($source)) {
                    return false;
                }
            } else {
                return false;
            }
        
            $this->obfPrint('OK');
            return $dest;
        }

        public function obfPrint ($msg = '', $lineBreaksBefore = 0, $lineBreaksAfter = 1) {
            if (!$msg) {
                return false;
            }
            $output = '';
            if (php_sapi_name() != "cli") {
                $lineBreak = "<br />";
            } else {
                $lineBreak = "\n";
            }
            if ($lineBreaksBefore > 0) {
                for ($i = 1; $i <= $lineBreaksBefore; $i++) {
                    $output .= $lineBreak;
                }                
            }
            $output .= $msg;
            if ($lineBreaksAfter > 0) {
                for ($i = 1; $i <= $lineBreaksAfter; $i++) {
                    $output .= $lineBreak;
                }                
            }
            echo $output;
            if (php_sapi_name() != "cli") {
                ob_flush();
            }
            flush();
        }
    }

    error_reporting(E_ALL);
    if (php_sapi_name() != "cli") {
        echo '<div style="font-family: monospace;">';
    }
    $backupDatabase = new Backup_Database(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $result = $backupDatabase->backupTables(TABLES, BACKUP_DIR) ? 'OK' : 'KO';
    $backupDatabase->obfPrint('Backup result: ' . $result, 1);
    if (php_sapi_name() != "cli") {
        echo '</div>';
    }

}?>

<!-- ***************************************Menu****************************************** --> 

<!DOCTYPE html>

<html>

<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href='https://fonts.googleapis.com/css?family=Work Sans' rel='stylesheet'>
<link rel='icon' href='https://www.namecheap.com/favicon.ico' type='image/x-icon'>

<title>Website Exporter</title>

<style>

html {
    display: table;
    margin: auto;
    background-color: #F5F5F5;
}

body {
    font-family: 'Work Sans';
    display: table-cell;
    vertical-align: middle;
    background: linear-gradient(to right, #E8E8E8 , #C8C8C8);
    padding: 20px 60px 20px 60px;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
}

label {
    display:block;
    float:left;
    width:120px;
}

.btn {
    border: none; 
    color: white; 
    padding: 10px 28px;
    cursor: pointer;
    margin-left: auto;
    margin-right: auto;
}

.default { background-color: #4CAF50; } 

.default:hover { background-color: #46a049; }

.btn-container { text-align: center; }

.center {
    display: block;
    margin-left: auto;
    margin-right: auto;
}

fixed-width { width: 500px; }

</style>

<script type="text/javascript">

function toggle_visibility(id) {
var e = document.getElementById(id);
if(e.style.display == 'block')
e.style.display = 'none';
else
e.style.display = 'block';
}

</script>

</head>

<body>

<img src="https://02.files.namecheap.com/cdn/482/assets/img/logos/namecheap.png" alt="Namecheap" height="43" width="219" id="fixed-width" class="center"><br>
<hr>

<?php if (!empty($_POST['act1'])) {
    zipData($directory, $_POST['bname'], $_POST['excludes1'], $_POST['excludes2']);
    echo "Zip archive " . $_POST['bname'] . " has been created<br><br>";?>
    <div class="btn-container"><button type="button" class="btn default" onclick="javascript:window.location = document.referrer;">Back</button></div>

<?php } elseif (!empty($_POST['act3'])) {
    remoteFTP(); ?>
    <br><div class="btn-container"><button type="button" class="btn default" onclick="javascript:history.go(-1);">Back</button></div>

<?php } elseif (!empty($_POST['act2'])) {
    exportDatabase(); ?>
    <br><div class="btn-container"><button type="button" class="btn default" onclick="javascript:window.location = document.referrer;">Back</button></div>

<?php } elseif (!empty($_POST['act4'])) {
    file_put_contents('.zip_exclude_files', $_POST['excludes1']);
    file_put_contents('.zip_exclude_dirs', $_POST['excludes2']);
    header("Refresh:0"); ?>

<?php } elseif (!empty($_POST['act5'])) {
    unlink('.zip_exclude_files');
    unlink('.zip_exclude_dirs');
    header("Refresh:0"); ?>

<?php } else {
    echo "PHP: " . phpversion() . "<br>";

    try {
        echo "IP: " . $_SERVER['SERVER_ADDR'] . "<br>";
    } catch (Exception $e) {
        echo "Failed to get the host IP<br>";    
    }

    try {    
        echo "DocumentRoot: " . $directory . "<br>";
    } catch (Exception $e) {
        echo "Failed to get the DocumentRoot<br>";    
    }  

    try {    
        echo "Inodes: " . getFileCount($directory) . "<br>";
    } catch (Exception $e) {
        echo "Failed to get Inode Usage<br>";    
    } 
    
    try {
        list($firstItem) = dirSize($directory);
        list( , $secondItem) = dirSize($directory);
        list( , , $thirdItem) = dirSize($directory);
        list( , , , $fourthItem) = dirSize($directory);
        echo "Disk Usage: " . humanFilesize($firstItem);
        if ($secondItem != 0) {
            echo " (" . humanFilesize($secondItem) . " and " . $thirdItem . " inodes to be excluded ~ " . humanFilesize($firstItem - $secondItem) . ")<br>"; ?>
        <a href="#" onclick="toggle_visibility('foo');">Show excluded files</a>
        <div id="foo" style="display:none;">
        <?php foreach ($fourthItem as $iteration) { echo $iteration . "<br>"; } ?>
        </div><br> <?php } 
    } catch (Exception $e) {
        echo "Failed to get the Disk Usage<br>";    
    }  

    if (!extension_loaded('zip')) {
        echo "Zip PHP extension is not loaded<br><br><hr>";

    } else { ?>
        <form action="zip.php" method="post">
        <input type="hidden" name="bname" value="<?php echo $website_backup_name;?>">
        <a href="#" onclick="toggle_visibility('boo');">Modify exclude list</a><br>
        <div id="boo" style="display:none;">
        <p><label>Exclude Files </label><input type="text" name="excludes1" value="<?php 
        if (file_exists('.zip_exclude_files')){
            echo file_get_contents('.zip_exclude_files');
        } else {
            echo '.tar.gz .gz .tar .zip .wpress error_log advanced-cache.php wp-cache-config.php';
        }
        ?>" style='margin:auto; width:440px' /></p>
        <p><label>Exclude Dirs </label><input type="text" name="excludes2" value="<?php 
        if (file_exists('.zip_exclude_dirs')){
            echo file_get_contents('.zip_exclude_dirs');
        } else {
            echo 'wp-content/ai1wm-backups wp-content/cache wp-content/w3tc-config wp-content/upgrade wp-content/updraft wp-content/plugins/all-in-one-wp-migration wp-content/plugins/jetpack wp-content/plugins/updraftplus wp-content/plugins/w3-total-cache wp-content/plugins/wp-super-cache wp-content/plugins/backwpup wp-content/plugins/wp-clone wp-content/plugins/duplicator wp-content/plugins/wp-clone-by-wp-academy wp-content/uploads/wp-clone wp-snapshots'; 
        }
        ?>" style='margin:auto; width:440px' /></p>
        <div class="btn-container"><input type="submit" name="act4" class="btn default" value="Save Exclude Config"></div><br>
        <?php if (file_exists('.zip_exclude_files') || file_exists('.zip_exclude_dirs')) { ?>
        <div class="btn-container"><input type="submit" name="act5" class="btn default" value="Delete Exclude Config"></div><br>
        <?php } ?>
        </div>
        <?php echo "Expected backup: <i>$website_backup_name<br><br></i>"; ?>
        <div class="btn-container"><input type="submit" name="act1" class="btn default" value="Zip"></div>
        </form><br><hr>
    <?php }

    $wp_config_filename = 'wp-config.php';
    $joomla_config_filename = 'configuration.php';
    $drupal_config_filename = 'sites/default/settings.php';

    if (file_exists($wp_config_filename)) {
        
        define( 'SHORTINIT', true );
        require 'wp-config.php';
        list($hostpart,$portpart) = explode(':', DB_HOST);
        echo "<b>WordPress Database details:</b><br>"; ?>
        <form action="zip.php" method="post"> 
        <p><label>DB_NAME </label><input type="text" name="name" value="<?php echo DB_NAME; ?>" style='margin:auto; width:440px' /></p>
        <p><label>DB_USER </label><input type="text" name="user" value="<?php echo DB_USER; ?>" style='margin:auto; width:440px' /></p>
        <p><label>DB_PASS </label><input type="text" name="password" value="<?php echo DB_PASSWORD; ?>" style='margin:auto; width:440px' /></p>
        <p><label>DB_HOST </label><input type="text" name="host" value="<?php echo $hostpart; ?>" style='margin:auto; width:440px' /></p>
        <p><label>DB_PORT </label><input type="text" name="port" value="<?php echo $portpart; ?>" style='margin:auto; width:440px' /></p>
        <div class="btn-container"><input type="submit" class="btn default" value="Export"></div>
        <input type="hidden" name="act2" value="run">
        </form><br><hr>

    <?php } elseif (file_exists($joomla_config_filename)) {
    
        require 'configuration.php';
        $var_cls = new JConfig();
        list($hostpart,$portpart) = explode(':', $var_cls->host);      
        echo "<b>Joomla Database details:</b><br>"; ?>
        <form action="zip.php" method="post"> 
        <p><label>DB_NAME </label><input type="text" name="name" value="<?php echo $var_cls->db; ?>" style='margin:auto; width:440px' /></p>
        <p><label>DB_USER </label><input type="text" name="user" value="<?php echo $var_cls->user; ?>" style='margin:auto; width:440px' /></p>
        <p><label>DB_PASS </label><input type="text" name="password" value="<?php echo $var_cls->password; ?>" style='margin:auto; width:440px' /></p>
        <p><label>DB_HOST </label><input type="text" name="host" value="<?php echo $hostpart; ?>" style='margin:auto; width:440px' /></p>
        <p><label>DB_PORT </label><input type="text" name="port" value="<?php echo $portpart; ?>" style='margin:auto; width:440px' /></p>
        <div class="btn-container"><input type="submit" class="btn default" value="Export"></div>
        <input type="hidden" name="act2" value="run">
        </form><br><hr>

    <?php } elseif (file_exists($drupal_config_filename)) {
    
        require 'sites/default/settings.php';       
        echo "<b>Drupal Database details:</b><br>"; ?>
        <form action="zip.php" method="post"> 
        <p><label>DB_NAME </label><input type="text" name="name" value="<?php echo $databases['default']['default']['database']; ?>" style='margin:auto; width:440px' /></p>
        <p><label>DB_USER </label><input type="text" name="user" value="<?php echo $databases['default']['default']['username']; ?>" style='margin:auto; width:440px' /></p>
        <p><label>DB_PASS </label><input type="text" name="password" value="<?php echo $databases['default']['default']['password']; ?>" style='margin:auto; width:440px' /></p>
        <p><label>DB_HOST </label><input type="text" name="host" value="<?php echo $databases['default']['default']['host']; ?>" style='margin:auto; width:440px' /></p>
        <p><label>DB_PORT </label><input type="text" name="port" value="<?php echo $databases['default']['default']['port']; ?>" style='margin:auto; width:440px' /></p>
        <div class="btn-container"><input type="submit" class="btn default" value="Export"></div>
        <input type="hidden" name="act2" value="run">
        </form><br><hr>

    <?php } else {
        echo "No configuration file found - you will need to fill in the details manually<br><br>"; ?>
        <form action="db-export.php" method="post"> 
        <p><label>DB_NAME </label><input type="text" name="name" style='margin:auto; width:440px' /></p>
        <p><label>DB_USER </label><input type="text" name="user" style='margin:auto; width:440px' /></p>
        <p><label>DB_PASS </label><input type="text" name="password" style='margin:auto; width:440px' /></p>
        <p><label>DB_HOST </label><input type="text" name="host" style='margin:auto; width:440px' /></p>
        <p><label>DB_PORT </label><input type="text" name="port" style='margin:auto; width:440px' /></p>
        <div class="btn-container"><input type="submit" class="btn default" value="Export"></div>
        <input type="hidden" name="act2" value="run">
        </form><br><hr>
    <?php }

    $archives = array();       
    
    foreach (glob("*_website.zip") as $filename) {
        $archives[] = $filename;        
    }

    foreach (glob("*_db.sql") as $filename) {
        $archives[] = $filename; 
    }

    if (count($archives) > 0) {
        echo "<b>Available backups:</b><br>"; 
        foreach ($archives as $one_archive) { ?>
            <a href="<?php echo "$one_archive"?>"><?php echo "$one_archive"?></a> <?php echo " " . humanFilesize(filesize($one_archive)) . "<br>";
        }
    } ?>
    
    <form action="zip.php" method="post"> 
    <p><label>BACKUP </label><input type="text" name="file" style='margin:auto; width:440px;' /></p>
    <p><label>SERVER </label><input type="text" name="server" style='margin:auto; width:440px;' /></p>
    <p><label>USERNAME </label><input type="text" name="user" style='margin:auto; width:440px;' /></p>
    <p><label>PASSWORD </label><input type="text" name="passw" style='margin:auto; width:440px;' /></p>
    <div class="btn-container"><input type="submit" class="btn default" value="Transfer"></div>
    <input type="hidden" name="act3" value="run">
    </form><br>    

<?php } ?>

</body>

</html>