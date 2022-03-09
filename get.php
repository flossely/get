<?php

// GETTING REQUEST DATA
$host = ($_REQUEST['host']) ? $_REQUEST['host'] : '68747470733a2f2f6769746875622e636f6d';
$key = $_REQUEST['key'];
$pkg = $_REQUEST['pkg'];
$repo = $_REQUEST['repo'];
$branch = ($_REQUEST['branch']) ? $_REQUEST['branch'] : '';
$user = $_REQUEST['user'];

// IN CASE YOU WANT TO INSTALL OR UPDATE PACKAGE
if ($key == 'i') {
    if ($pkg == "from" && $repo != "" && $user != "") {
        // REMOVE PACKAGE IF EXISTING
        if (file_exists($repo.'.pkg')) {
            $cont = file_get_contents($repo.'.pkg');
            if (strpos($cont, '=|1|=') !== false) {
                $contExp1 = explode('=|1|=', $cont);
                $contHead = $contExp1[0];
                $list = $contExp1[1];
            } else {
                $list = $cont;
            }
            $files = explode(';', $list);
            foreach ($files as $key=>$file) {
                if (file_exists($file)) {
                    chmod($file, 0777);
                    unlink($file);
                }
            }
            chmod($repo.'.pkg', 0777);
            unlink($repo.'.pkg');
        }
        
        // BACKING UP FILES
        if (file_exists('backup')) {
            $backlist = file_get_contents('backup');
            $backup = explode(';', $backlist);
            foreach ($backup as $key=>$file) {
                if (file_exists($file)) {
                    rename($file, $file.'.bak');
                    chmod($file.'.bak', 0777);
                }
            }
        }
        
        // SOLVING DIRECTORY CONFLICT
        if (file_exists($repo)) {
            chmod($repo, 0777);
            rename($repo, $repo.'.d');
        }
        
        // READY TO INSTALL PACKAGE
        $request = hex2bin($host).'/'.$user.'/'.$repo;
        if ($branch != '') {
            exec('git clone -b '.$branch.' '.$request);
        } else {
            exec('git clone '.$request);
        }
        chmod($repo, 0777);
        
        // MOVING ALL FILES FROM REPO TO CURRENT PATH
        exec('mv '.$repo.'/* $PWD');
        exec('chmod -R 777 .');
        exec('rm -rf '.$repo);
        
        // GETTING CONFLICTING DIRECTORY BACK
        if (file_exists($repo.'.d')) {
            chmod($repo.'.d', 0777);
            rename($repo.'.d', $repo);
        }
        
        // REMOVING UNWANTED FILES
        if (file_exists('ignore')) {
            $ignorlist = file_get_contents('ignore');
            $ignore = explode(';', $ignorlist);
            foreach ($ignore as $key=>$file) {
                if (file_exists($file)) {
                    chmod($file, 0777);
                    unlink($file);
                }
            }
        }
        
        // RESTORING FILES FROM BACKUP
        foreach ($backup as $key=>$file) {
            if (file_exists($file.'.bak')) {
                rename($file.'.bak', $file);
                chmod($file, 0777);
            }
        }
    }
    
// IN CASE YOU WANT TO REPLACE PACKAGE WITH NEW
} elseif ($key == 'r') {
    if ($pkg != "" && $repo != "" && $user != "") {
        // REMOVING THE FORMER PACKAGE
        if (file_exists($pkg.'.pkg')) {
            $cont = file_get_contents($pkg.'.pkg');
            if (strpos($cont, '=|1|=') !== false) {
                $contExp1 = explode('=|1|=', $cont);
                $contHead = $contExp1[0];
                $list = $contExp1[1];
            } else {
                $list = $cont;
            }
            $files = explode(";", $list);
            foreach ($files as $key=>$file) {
                if (file_exists($file)) {
                    chmod($file, 0777);
                    unlink($file);
                }
            }
            chmod($pkg.'.pkg', 0777);
            unlink($pkg.'.pkg');
        }
        header('Location: get.php?key=i&pkg=from&repo='.$repo.'&user='.$user);
    }

// IN CASE YOU WANT TO REMOVE PACKAGE
} elseif ($key == 'd') {
    if ($pkg != "" && $repo == 'from' && $user == 'here') {
        if (file_exists($pkg.'.pkg')) {
            $cont = file_get_contents($pkg.'.pkg');
            if (strpos($cont, '=|1|=') !== false) {
                $contExp1 = explode('=|1|=', $cont);
                $contHead = $contExp1[0];
                $list = $contExp1[1];
            } else {
                $list = $cont;
            }
            $files = explode(";", $list);
            foreach ($files as $key=>$file) {
                if (file_exists($file)) {
                    chmod($file, 0777);
                    unlink($file);
                }
            }
            chmod($pkg.'.pkg', 0777);
            unlink($pkg.'.pkg');
        }
    }
}
