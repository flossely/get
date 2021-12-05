<?php
$default = file_get_contents('get.cfg');
$host = ($_REQUEST['host']) ? $_REQUEST['host'] : bin2hex($default);
$key = $_REQUEST['key'];
$pkg = $_REQUEST['pkg'];
$repo = $_REQUEST['repo'];
$user = $_REQUEST['user'];
if ($key == 'i') {
    if ($pkg == "from" && $repo != "" && $user != "") {
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
        if (file_exists($repo)) {
            chmod($repo, 0777);
            rename($repo, $repo.'.d');
        }
        $request = hex2bin($host).'/'.$user.'/'.$repo;
        exec('git clone '.$request);
        chmod($repo, 0777);
        if (file_exists($repo.'/backup') && file_exists('backup')) {
            $backupInput = file_get_contents($repo.'/backup');
            $backupOutput = file_get_contents('backup');
            $backupOutput .= $backupInput;
            $backupOutput = implode(';', array_unique(explode(';', $backupOutput)));
            file_put_contents('backup', $backupOutput);
            chmod('backup', 0777);
            chmod($repo.'/backup', 0777);
            unlink($repo.'/backup');
        }
        exec('mv '.$repo.'/* $PWD');
        exec('chmod -R 777 .');
        exec('rm -rf '.$repo);
        if (file_exists($repo.'.d')) {
            chmod($repo.'.d', 0777);
            rename($repo.'.d', $repo);
        }
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
        foreach ($backup as $key=>$file) {
            if (file_exists($file.'.bak')) {
                rename($file.'.bak', $file);
                chmod($file, 0777);
            }
        }
    }
} elseif ($key == 'r') {
    if ($pkg != "" && $repo != "" && $user != "") {
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