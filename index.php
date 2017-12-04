<!DOCTYPE html>
<html>
<head>
    <title>Git panel</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="css/main.css" rel="stylesheet">
    <script>
        urlApi = 'api.php';
        method = 'POST';
    </script>
</head>

<?
session_start();
$flag = true;
$path = '';

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
    ?>
    <body>

    <div class="container-fluid page">
        <div class="row page">
            <div class="col-md-2 menu">
                <div class="row">
                    <div class="col-md-11 col-md-offset-1">
                        <h1>Git panel</h1>
                    </div>
                </div>
                <div id="menu" class="col-md-11 col-md-offset-1">
                    <div id="statusRequest" data-type="status" class="form-control text-left btn-panel">
                        <i class="icon icon-status"></i>
                        <span>Status</span>
                    </div>
                    <div id="branchRequest" data-type="branch"
                         class="form-control text-left btn-panel"><i class="icon icon-branch"></i><span>Branch</span>
                    </div>
                    <div id="commitRequest" data-type="commit"
                         class="form-control text-left btn-panel"><i class="icon icon-commit"></i><span>Commit</span>
                    </div>
                    <div id="pushRequest" data-type="push"
                         class="form-control text-left btn-panel"><i class="icon icon-push"></i><span>Push</span></div>
                    <div id="pullRequest" data-type="pull"
                         class="form-control text-left btn-panel"><i class="icon icon-pull"></i><span>Pull</span></div>
                    <!-- <div id="diffRequest" data-type="diff" class="form-control text-left btn-panel"><i class="icon icon-diff"></i><span>Diff</span></div>-->
                    <div id="settingRequest" data-type="setting"
                         class="form-control text-left btn-panel"><i
                                class="icon icon-setting"></i><span>Setting +</span></div>
                    <? if (isset($passwordPage)) { ?>
                        <div id="exit" data-type="exit"
                             class="form-control text-left btn-panel"><i class="icon icon-exit"></i><span>Exit</span>
                        </div>
                    <? } ?>
                </div>
            </div>
            <div class="wraper col-md-10 container">
                <div>
                    <h1 class="title" data-type="status" style="display:none">Status</h1>
                    <h1 class="title" data-type="branch" style="display:none">Branch</h1>
                    <h1 class="title" data-type="commit" style="display:none">Commit</h1>
                    <h1 class="title" data-type="push" style="display:none">Push</h1>
                    <h1 class="title" data-type="pull" style="display:none">Pull</h1>
                    <h1 class="title" data-type="diff" style="display:none">Diff</h1>
                    <h1 class="title" data-type="setting" style="display:none">Settings</h1>
                </div>
                <div>
                    <div id="result" class="col-md-6 " style="display: none">

                    </div>
                    <div id="status" class="action-area col-md-6" style="display: none">
                        <div>
                            <div class="form-group">
                                <button id="statusBtn" data-type="resetStatus" class="btn btn-default">git reset HEAD
                                </button>
                            </div>
                            <div class="form-group">
                                <button id="statusBtn" data-type="updateStatus" class="btn btn-default">git add (update)
                                </button>
                            </div>
                            <div class="form-group">
                                <button id="statusBtn" data-type="rmStatus" class="btn btn-default">git rm</button>
                            </div>
                            <div class="form-group">
                                <button id="statusBtn" data-type="checkoutStatus" class="btn btn-default">git checkout
                                    --
                                </button>
                            </div>
                            <div class="form-group">
                                <button id="statusBtn" data-type="includeStatus" class="btn btn-default">git add
                                    (include)
                                </button>
                            </div>
                            <div class="form-group">
                                <button id="statusBtn" data-type="addGitignoreStatus" class="btn btn-default">add in
                                    gitignore
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="branch" class="action-area col-md-6" style="display: none">
                        <div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label>Create branch</label>
                                    <input type="text" id="branchCreate" class="form-control" name="name"
                                           placeholder="Name branch">
                                </div>
                                <input id="branchBtn" data-type="create" type="submit" class="btn btn-default"
                                       value="Create branch">
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label>Delete branch</label>
                                    <select name="name" id="branchDelete" class="form-control">

                                    </select>
                                </div>
                                <input id="branchBtn" data-type="delete" type="submit" class="btn btn-default"
                                       value="Delete branch">
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label>Checkout branch</label>
                                    <select name="name" id="branchCheckout" class="form-control">

                                    </select>
                                </div>
                                <input id="branchBtn" data-type="checkout" type="submit" class="btn btn-default"
                                       value="Select branch">
                            </div>
                        </div>
                    </div>
                    <div id="commit" class="action-area col-md-6" style="display: none">
                        <div class="form-group">
                            <div class="form-group">
                                <label>Message for commit</label>
                                <textarea name="commitMessage" id="commitTextarea" class="form-control"
                                          rows="5"></textarea>
                            </div>
                            <input id="commitBtn" type="submit" class="btn btn-default" value="Send commit">
                        </div>
                    </div>
                    <div id="push" class="action-area col-md-6" style="display: none">
                        <div class="form-group">
                            <label>Href/Name repo:</label>
                            <div>
                                <input type="text" class="form-control" id="pushRepo" class="col-md-6 form-control" <?if($hrefGitRemote){?>value="<?=$hrefGitRemote?>"<?}?>>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Branch for push</label>
                            <select name="name" id="pushBranchName" class="form-control">

                            </select>
                        </div>
                        <div class="form-group">
                            <input id="pushBtn" type="submit" class="btn btn-default" value="Push">
                        </div>
                    </div>
                    <div id="pull" class="action-area col-md-6" style="display: none">
                        <div class="form-group">
                            <label>Href/Name repo:</label>
                            <div>
                                <input type="text" class="form-control" id="pullRepo" class="col-md-6 form-control" <?if($hrefGitRemote){?>value="<?=$hrefGitRemote?>"<?}?> >
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Branch for pull</label>
                            <select name="name" id="pullBranchName" class="form-control">

                            </select>
                        </div>
                        <div class="form-group">
                            <input id="pullBtn" type="submit" class="btn btn-default" value="Pull">
                        </div>
                    </div>
                    <div id="diff" class="action-area col-md-6" style="display: none">

                    </div>
                    <div id="setting" class="action-area col-md-12 setting" style="display: none">
                        <div class="col-md-2">
                            <div class="setting-list row">
                                <div class="form-group">
                                    <input type="button" value="Set password" alt="Set password fow this page"
                                           id="setPasswordRequest" data-type="setPassword" class="btn btn-default"/>
                                </div>
                                <div class="form-group">
                                    <input type="button" value="Set directory root"
                                           id="setDirectoryRootRequest" data-type="setDirectoryRoot"
                                           class="btn btn-default"/>
                                </div>
                                <div class="form-group">
                                    <input type="button" value="Set git config" id="setGitConfigRequest"
                                           data-type="setGitConfig"
                                           class="btn btn-default"/>
                                </div>
                                <div class="form-group">
                                    <input type="button" value="Set repo" id="setRepoRequest" data-type="setRepo"
                                           class="btn btn-default"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="setting-list" id="settings-area">
                                <div id="setPassword" class="area" style="display: none">

                                    <div class="form-group">
                                        <label>Set password for this page</label>
                                        <input type="password" id="passwordPage" class="form-control" name="name"
                                               placeholder="password">
                                    </div>
                                    <input id="passwordBtn" data-type="create" type="submit" class="btn btn-default"
                                           value="Set password">
                                </div>
                                <div id="setDirectoryRoot" class="area" style="display: none">

                                    <div class="form-group">
                                        <label>Set directory root</label>
                                        <input type="text" id="documetRoot" class="form-control" name="path"
                                               placeholder="<?= $_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/' ?>"
                                               value="<?= $path ?>">
                                    </div>
                                    <input id="documetRootBtn" type="submit" class="btn btn-default"
                                           value="Set documet root">
                                </div>
                                <div id="setGitConfig" class="area" style="display: none">
                                    <div class="col-md-6">
                                        <label>Git config:</label>
                                        <div id="gitConfigResultType" class="btn-group" data-toggle="buttons">
                                            <label class="btn active"><input type="radio" name="gitConfigResultType"
                                                                             value="global"
                                                                             autocomplete="off" checked> Global</label>
                                            <label class="btn"><input type="radio" name="gitConfigResultType"
                                                                      value="local"
                                                                      autocomplete="off"> Local</label>
                                            <label class="btn"><input type="radio" name="gitConfigResultType"
                                                                      value="system"
                                                                      autocomplete="off"> System</label>
                                        </div>
                                        <div id="gitConfigResult">

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Add/update git config</label>
                                        <div class="form-group">
                                            <div id="gitConfigType" class="btn-group" data-toggle="buttons">
                                                <label class="btn btn-primary active">
                                                    <input type="radio" name="gitConfigType" value="global"
                                                           autocomplete="off"
                                                           checked> Global
                                                </label>
                                                <label class="btn btn-primary">
                                                    <input type="radio" name="gitConfigType" value="local"
                                                           autocomplete="off"> Local
                                                </label>
                                                <label class="btn btn-primary">
                                                    <input type="radio" name="gitConfigType" value="system"
                                                           autocomplete="off">
                                                    System
                                                </label>
                                            </div>
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input id="gitConfigName" type="text" class="form-control" name="name">
                                            </div>
                                            <div class="form-group">
                                                <label>Value</label>
                                                <input id="gitConfigValue" type="text" class="form-control"
                                                       name="value">
                                            </div>
                                        </div>
                                        <input id="sendGitConfig" type="submit" class="btn btn-default"
                                               value="Add / Update">
                                        <input id="sendDeleteGitConfig" type="submit" class="btn btn-default"
                                               value="Unset">
                                    </div>
                                </div>
                                <div id="setRepo" class="area" style="display: none">

                                    <div class="col-md-6 hide">
                                        <label>Set remote</label>
                                        <div id="gitRemoteResult">

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="clear-fix col-md-12">
                                            <label>Create/Update https repo for panel</label>
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label>Href</label>
                                                    <input id="setHrefGitHttpsRemote" type="text" class="form-control">
                                                    <p>use (*password*) for include password in href</p>
                                                </div>
                                                <div class="form-group">
                                                    <label>Password</label>
                                                    <input id="setPasswordGitHttpsRemote" type="password" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <input id="createGitHttpsRemote" type="submit" class="btn btn-default" value="Create">
                                            </div>
                                        </div>
                                        <div class="col-md-6 hide">
                                            <label>Add/Update git remote</label>
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label>Name</label>
                                                    <input id="gitRemoteName" type="text" class="form-control"
                                                           name="name">
                                                </div>
                                                <div class="form-group">
                                                    <label>Url</label>
                                                    <input id="gitRemoteUrl" type="text" class="form-control"
                                                           name="value">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <input id="addGitRemote" type="submit" class="btn btn-default"
                                                       value="Add">
                                                <input id="updateGitRemote" type="submit" class="btn btn-default"
                                                       value="Update">
                                            </div>
                                        </div>
                                        <div class="col-md-6 hide">
                                            <label>Rename git remote</label>
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label>Old name</label>
                                                    <input id="gitRemoteNameOld" type="text" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>New name</label>
                                                    <input id="gitRemoteNameNew" type="text" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <input id="renameGitRemote" type="submit" class="btn btn-default"
                                                       value="Add">
                                            </div>
                                        </div>
                                        <div class="clear-fix col-md-12 hide">
                                            <label>Remove git remote</label>
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label>Name</label>
                                                    <input id="gitRemoteRemoveName" type="text" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <input id="removeGitRemote" type="submit" class="btn btn-default" value="Remove">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="copyright">create <a href="//vk.com/ee_kovalevich_krutsan">Evgeni Kovalevich-Krutsan</a> -
                    <a href="//skillline.ru">Skillline</a></div>
            </div>
        </div>
    </div>
    </body>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"
            integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"
            integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn"
            crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
<?
} else {
?>
    <body>
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            flag = true;
            title = 'Enter the password';
            do {
                result = prompt(title, '');

                if (result) {
                    $.ajax({
                        url: urlApi,
                        method: method,
                        async: false,
                        data: {"type": 'authorization', "password": result},
                        success: function (data) {
                            console.log(data);
                            data = $.parseJSON(data);
                            if (data.result) {
                                flag = false;
                                window.location.reload();
                            } else {
                                title = 'Without the right password. Enter the password';
                            }

                        }
                    });
                }
            } while (flag)
        })
        ;
    </script>
    <?
}
?>
</html>