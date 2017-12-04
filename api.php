<?
$flag = true;
$path = $_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/';

session_start();
if (file_exists('./config.php')) {
    include_once('./config.php');
    if (isset($passwordPage)) {
        if (isset($_SESSION['gitPanelHash'])) {
            if ($_SESSION['gitPanelHash'] == $passwordPage) {
                $flag = true;
            } else {
                $flag = false;
            }
        } else {
            $flag = false;
        }
    } else {
        $flag = true;
    }
}

if ($flag) {
    $output = [];
    switch ($_POST['type']) {
        case 'init':
            exec("cd $path; git init;", $output);
            echo json_encode($output);
            break;
        case 'status':
            exec("cd $path; git status;", $output);
            echo json_encode($output);
            break;
        case 'resetStatus':
            $list_files = $_POST['data'];
            foreach ($list_files as $file) {
                exec("cd $path;git reset HEAD $file;", $output);
            }
            echo json_encode(['result' => true, 'data' => $output]);
            break;
        case 'rmStatus':
            $list_files = $_POST['data'];

            foreach ($list_files as $file) {
                exec("cd $path;git rm --cached $file;", $output);
            }

            echo json_encode(['result' => true, 'data' => $output]);
            break;
        case 'updateStatus':
            if (isset($_POST['data'])) {
                $list_files = $_POST['data'];

                foreach ($list_files as $file) {
                    exec("cd $path;git add $file;", $output, $result);
                }

                echo json_encode(['result' => true]);
            } else {
                echo json_encode(['result' => false]);
            }
            break;
        case 'rmStatus':
            $list_files = $_POST['data'];

            foreach ($list_files as $file) {
                exec("cd $path;git rm $file;", $output, $result);
            }

            echo json_encode(['result' => true]);
            break;
        case 'checkoutStatus':
            $list_files = $_POST['data'];

            foreach ($list_files as $file) {
                exec("cd $path;git checkout -- $file;", $output);
            }

            echo json_encode(['result' => true]);
            break;
        case 'includeStatus':
            if (isset($_POST['data'])) {
                $list_files = $_POST['data'];

                foreach ($list_files as $file) {
                    exec("cd $path;git add $file;", $output);
                }
                $mess = "cd $path;git add $file;";
                echo json_encode(['result' => true, 'data' => $output, $mess]);
            } else {
                echo json_encode(['result' => false]);
            }
            break;
        case 'addGitignoreStatus':
            $list_files = $_POST['data'];

            foreach ($list_files as $file) {
                exec("cd $path; echo '$file' >> .gitignore;", $output);
            }

            echo json_encode(['result' => true, 'data' => $output]);
            break;
        case 'branch':
            exec("cd $path; git branch;", $output);
            echo json_encode($output);
            break;
        case 'createBranch':
            $name = $_POST['name'];
            exec("cd $path; git branch $name;", $output);
            echo json_encode($output);
            break;
        case 'deleteBranch':
            $name = $_POST['name'];
            exec("cd $path; git branch -D $name;", $output);
            echo json_encode($output);
            break;
        case 'checkoutBranch':
            $name = $_POST['name'];
            exec("cd $path; git checkout $name;", $output);
            echo json_encode($output);
            break;
        case 'commit':
            exec("cd $path; git status;", $output);
            echo json_encode($output);
            break;
        case 'commitMessage':
            $message = $_POST['message'];
            exec("cd $path; git commit -m '$message';", $output);
            echo json_encode($output);
            break;
        case 'push':
            $repo = str_replace('(*password*)', $passwordGitRemote, $_POST['repo']);
            $branch = str_replace([' ', '*'], '', $_POST['branch']);
            exec("cd $path; git push $repo $branch;", $output, $result);
            $output[] = $result == 0 ? 'Successfully' : 'Error';
            echo json_encode($output);
            break;
        case 'pull':
            $repo = str_replace('(*password*)', $passwordGitRemote, $_POST['repo']);
            $branch = str_replace([' ', '*'], '', $_POST['branch']);
            exec("cd $path; git pull $repo $branch;", $output, $result);
            $output ?: $output[] = $result == 0 ? 'Successfully' : 'Error';
            echo json_encode($output);
            break;
        case 'passwordPage':
            $password = md5($_POST['password']);
            if (file_exists('./config.php') && $file = file_get_contents('./config.php')) {
                $re = '/\$passwordPage=\'?(.+)\';/';

                if ($_POST['password']) {
                    $result = preg_replace($re, "\$passwordPage='$password';", $file, -1, $count);
                } else {
                    $result = preg_replace($re, "", $file, -1, $count);
                }

                if (!$count) {

                    $arFile = file("config.php");
                    $arFile = array_values(array_diff($arFile, ["\n"]));
                    $arFile[0] = "<?\n";
                    if (count($arFile) == 1) {
                        $arFile[1] = "\$passwordPage='$password';\n";
                        $arFile[2] = "?>";
                    } else {
                        $arFile[count($arFile)] = "?>";
                        $arFile[count($arFile) - 2] = "\$passwordPage='$password';\n";
                    }

                    $result = implode($arFile);
                }

                $f = fopen("config.php", "w");
                fwrite($f, $result);
                fclose($f);
            } else {
                $f = fopen("config.php", "w");
                fwrite($f, "<?\n\$passwordPage='$password';\n?>");
                fclose($f);
            }
            break;
        case 'documetRoot':
            $path = $_POST['path'];
            if (file_exists('./config.php') && $file = file_get_contents('./config.php')) {
                $re = '/\$path=\'?(.+)\';/';
                if ($path) {
                    $result = preg_replace($re, "\$path='$path';", $file, -1, $count);
                } else {
                    $result = preg_replace($re, "", $file, -1, $count);
                }

                if (!$count) {

                    $arFile = file("config.php");
                    $arFile = array_values(array_diff($arFile, ["\n"]));
                    $arFile[0] = "<?\n";
                    if (count($arFile) == 1) {
                        $arFile[1] = "\$path='$path';\n";
                        $arFile[2] = "?>";
                    } else {
                        $arFile[count($arFile)] = "?>";
                        $arFile[count($arFile) - 2] = "\$path='$path';\n";
                    }

                    $result = implode($arFile);
                }

                $f = fopen("config.php", "w");
                fwrite($f, $result);
                fclose($f);
            } else {
                $f = fopen("config.php", "w");
                fwrite($f, "<?\n\$path='$path';\n?>");
                fclose($f);
            }
            break;
        case 'gitConfigResult':
            $typeConfig = $_POST["typeConfig"];

            exec("cd $path; git config --$typeConfig --list;", $output, $result);
            $result == 0 ? '' : $output[] = 'Error';
            echo json_encode($output);


            break;
        case 'sendGitConfig':
            $typeConfig = $_POST["typeConfig"];
            $nameConfig = $_POST["nameConfig"];
            $valueConfig = $_POST["valueConfig"];

            exec("cd $path; git config --$typeConfig $nameConfig \"$valueConfig\";", $output, $result);
            $result == 0 ? '' : $output[] = 'Error';
            echo json_encode($output);
            break;
        case 'sendDeleteGitConfig':
            $typeConfig = $_POST["typeConfig"];
            $nameConfig = $_POST["nameConfig"];

            exec("cd $path; git config --$typeConfig --unset $nameConfig ;", $output, $result);
            $result == 0 ? '' : $output[] = 'Error';
            echo json_encode($output);
            break;
        case 'createGitHttpsRemote':
            $href = $_POST["href"];
            $password = $_POST["password"];

            if (file_exists('./config.php') && $file = file_get_contents('./config.php')) {
                $re1 = '/\$hrefGitRemote=\'?(.+)\';/';
                $re2 = '/\$passwordGitRemote=\'?(.+)\';/';

                if ($href) {
                    $result = preg_replace($re1, "\$hrefGitRemote='$href';", $file, -1, $count);
                } else {
                    $result = preg_replace($re1, "", $file, -1, $count);

                }

                if (!$count && $href!='') {
                    $arFile = file("config.php");
                    $arFile = array_values(array_diff($arFile, ["\n"]));
                    $arFile[0] = "<?\n";
                    if (count($arFile) == 1) {
                        $arFile[1] = "\$hrefGitRemote='$href';\n";
                        $arFile[2] = "?>";
                    } else {
                        $arFile[count($arFile)] = "?>";
                        $arFile[count($arFile) - 2] = "\$hrefGitRemote='$href';\n";
                    }

                    $result = implode($arFile);
                }

                $f = fopen("config.php", "w");
                fwrite($f, $result);
                fclose($f);

                $file = $result;

                if ($password) {
                    $result = preg_replace($re2, "\$passwordGitRemote='$password';", $file, -1, $count);
                } else {
                    $result = preg_replace($re2, "", $file, -1, $count);
                }

                if (!$count && $password!='') {

                    $arFile = file("config.php");
                    $arFile = array_values(array_diff($arFile, ["\n"]));
                    $arFile[0] = "<?\n";
                    if (count($arFile) == 1) {
                        $arFile[1] = "\$passwordGitRemote='$password';\n";
                        $arFile[2] = "?>";
                    } else {
                        $arFile[count($arFile)] = "?>";
                        $arFile[count($arFile) - 2] = "\$passwordGitRemote='$password';\n";
                    }

                    $result = implode($arFile);
                }


                $f = fopen("config.php", "w");
                fwrite($f, $result);
                fclose($f);

            } else {
                $f = fopen("config.php", "w");
                fwrite($f, "<?\n\$hrefGitRemote='$href'\n\$passwordGitRemote='$password';\n?>");
                fclose($f);
            }

            echo json_encode(['result' => $result]);
            break;
    }
}
switch ($_POST['type']) {
    case 'authorization':
        if (md5($_POST['password']) == $passwordPage) {
            $_SESSION['gitPanelHash'] = $passwordPage;
            echo json_encode(['result' => true]);
        } else {
            echo json_encode(['result' => false]);
        }
        break;
    case 'exit':
        unset($_SESSION['gitPanelHash']);
        break;
}
?>