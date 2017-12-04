$(document).ready(function () {

    $('body').on('click', '#menu .btn-panel', function () {
        type = $(this).data('type');
        $('#menu .btn-panel').removeClass('action');
        $(this).addClass('action');
        if (type != 'exit') {
            $('.title').hide();
            $('.title[data-type="' + type + '"]').show();
            if (type != 'setting') {
                $('#result').show();
            } else {
                $('#result').hide();
            }
            $('.action-area').hide();
            $('#' + type).show();
        }
    })

    $('body').on('click', '#statusRequest', function () {
        $.ajax({
            url: urlApi,
            method: method,
            data: {type: $(this).data('type')},
            success: function (data) {
                data = $.parseJSON(data);
                $('#result').html('');
                $(data).each(function (i, val) {
                    if (val.match(/\(use "/)) {
                        if (val.match(/git reset HEAD/)) {
                            flag = 'reset';
                        }
                        if (val.match(/git rm --cached/)) {
                            flag = 'rm';
                        } else if (val.match(/git add <file>..." to update/)) {
                            flag = 'update/checkout';
                        } else if (val.match(/git checkout/)) {
                            flag = 'update/checkout';
                        } else if (val.match(/git add <file>..." to include/)) {
                            flag = 'include';
                        }
                    }

                    regex = /([#\t+](([^\s].+):\s+(.+)))|([#\t+]([^\s+].+))/g;
                    var m;

                    if ((m = regex.exec(val)) !== null) {

                        if (m[5] == undefined) {
                            $('#result').append('<label class="statusRow ' + m[3].replace(' ', '_') + '"><input type="checkbox" data-type="' + flag + '" value="' + m[4] + '">' + m[3] + ' : ' + m[4] + '</label>');
                        } else if (m[1] == undefined) {
                            $('#result').append('<label class="statusRow not_type"><input type="checkbox" data-type="' + flag + '" value="' + m[6] + '">' + m[6] + '</label>');
                        }
                    } else {
                        $('#result').append('<p>' + val + '</p>');
                    }
                });
                $('#result').scrollTop(999999999);
            }
        })
    });

    $('body').on('click', '#statusBtn', function () {
        type = $(this).data('type');

        var data = {};

        if (type == 'resetStatus') {
            $('#result input[data-type="reset"]:checked').each(function (i, val) {
                data[i] = $(this).val();
            })
        } else if (type == 'rmStatus') {
            $('#result input[data-type="update/checkout"]:checked').each(function (i, val) {
                data[i] = $(this).val();
            })
        } else if (type == 'updateStatus') {
            $('#result input[data-type="update/checkout"]:checked').each(function (i, val) {
                data[i] = $(this).val();
            })
        } else if (type == 'checkoutStatus') {
            $('#result input[data-type="update/checkout"]:checked').each(function (i, val) {
                data[i] = $(this).val();
            })
        } else if (type == 'includeStatus') {
            $('#result input[data-type="include"]:checked').each(function (i, val) {
                data[i] = $(this).val();
            })
        } else if (type == 'addGitignoreStatus') {
            $('#result input:checked').each(function (i, val) {
                data[i] = $(this).val();
            })
        }

        $.ajax({
            url: urlApi,
            method: method,
            data: {"type": type, "data": data},
            success: function (data) {
                data = $.parseJSON(data);
                $('#statusRequest').trigger('click');
                console.log(data);
            }
        })


    });

    $('body').on('click', '#branchRequest', function () {
        type = $(this).data('type');


        $.ajax({
            url: urlApi,
            method: method,
            data: {type: $(this).data('type')},
            success: function (data) {
                data = $.parseJSON(data);
                $('#result').html('');
                $('#branchCheckout').html('');
                $('#branchDelete').html('');
                $(data).each(function (i, val) {
                    $('#result').append('<p>' + val + '</p>');
                    $('#branchCheckout').append('<option>' + val + '</option>');
                    $('#branchDelete').append('<option>' + val + '</option>');
                });
                $('#result').scrollTop(999999999);
            }
        })
    });

    $('body').on('click', '#branchBtn', function () {
        type = $(this).data('type');

        var data = {};

        if (type == 'create') {
            type = 'createBranch';
            data = $('#branchCreate').val();
        } else if (type == 'delete') {
            type = 'deleteBranch';
            data = $('#branchDelete option:checked').html();
        } else if (type == 'checkout') {
            type = 'checkoutBranch';
            data = $('#branchCheckout option:checked').html();
        }

        $.ajax({
            url: urlApi,
            method: method,
            data: {"type": type, "name": data},
            success: function (data) {
                //data = $.parseJSON(data);
                $('#branchRequest').trigger('click');
            }
        })


    });

    $('body').on('click', '#commitRequest', function () {
        type = $(this).data('type');


        $.ajax({
            url: urlApi,
            method: method,
            data: {type: $(this).data('type')},
            success: function (data) {
                data = $.parseJSON(data);
                $('#result').html('');
                $(data).each(function (i, val) {
                    $('#result').append('<p>' + val + '</p>');
                });
                $('#result').scrollTop(999999999);
            }
        })
    });

    $('body').on('click', '#commitBtn', function () {
        message = $('#commitTextarea').val();
        $.ajax({
            url: urlApi,
            method: method,
            data: {"type": 'commitMessage', "message": message},
            success: function (data) {
                data = $.parseJSON(data);
                $('#commitTextarea').val('');
                $(data).each(function (i, val) {
                    $('#result').append('<p>' + val + '</p>');
                });

                $('#result').scrollTop(999999999);
            }
        })


    });

    $('body').on('click', '#pushRequest', function () {
        type = $(this).data('type');
        $('#result').html('');


        $.ajax({
            url: urlApi,
            method: method,
            data: {type: $('#branchRequest').data('type')},
            success: function (data) {
                data = $.parseJSON(data);
                $('#pushBranchName').html('');
                $(data).each(function (i, val) {
                    $('#pushBranchName').append('<option>' + val + '</option>');
                });
            }
        })
    });

    $('body').on('click', '#pushBtn', function () {
        repo = $('#pushRepo').val();
        branch = $('#pushBranchName option:checked').html();
        if (repo && branch) {
            $.ajax({
                url: urlApi,
                method: method,
                data: {"type": 'push', "repo": repo, "branch": branch},
                success: function (data) {
                    data = $.parseJSON(data);
                    $(data).each(function (i, val) {
                        $('#result').append('<p>' + val + '</p>');
                    });

                    $('#result').scrollTop(999999999);
                }
            })
        }
    });

    $('body').on('click', '#pullRequest', function () {

        type = $(this).data('type');
        $('#result').html('');


        $.ajax({
            url: urlApi,
            method: method,
            data: {type: $('#branchRequest').data('type')},
            success: function (data) {
                data = $.parseJSON(data);
                $('#pullBranchName').html('');
                $(data).each(function (i, val) {
                    $('#pullBranchName').append('<option>' + val + '</option>');
                });
            }
        })
    });

    $('body').on('click', '#pullBtn', function () {
        repo = $('#pullRepo').val();
        branch = $('#pullBranchName option:checked').html();
        if (repo && branch) {
            $.ajax({
                url: urlApi,
                method: method,
                data: {"type": 'pull', "repo": repo, "branch": branch},
                success: function (data) {
                    data = $.parseJSON(data);
                    $(data).each(function (i, val) {
                        $('#result').append('<p>' + val + '</p>');
                    });

                    $('#result').scrollTop(999999999);
                }
            })
        }
    });

    $('body').on('click', '#diffRequest', function () {

        type = $(this).data('type');
        $('#result').html('');

    });

    $('body').on('click', '#setPasswordRequest', function () {
        type = $(this).data('type');
        $('#settings-area .area').hide();
        $('#' + type).show();
    });

    $('body').on('click', '#passwordBtn', function () {
        $.ajax({
            url: urlApi,
            method: method,
            data: {"type": 'passwordPage', "password": $('#passwordPage').val()},
            success: function (data) {
                window.location.reload();
            }
        })
    })

    $('body').on('click', '#setDirectoryRootRequest', function () {
        type = $(this).data('type');
        $('#settings-area .area').hide();
        $('#' + type).show();
    });

    $('body').on('click', '#documetRootBtn', function () {
        $.ajax({
            url: urlApi,
            method: method,
            data: {"type": 'documetRoot', "path": $('#documetRoot').val()},
            success: function (data) {
                alert('completed');
            }
        })
    })

    $('body').on('click', '#setGitConfigRequest', function () {
        type = $(this).data('type');
        $('#settings-area .area').hide();
        $('#' + type).show();

        typeResult = $('#gitConfigResultType input:checked').val();
        $('#gitConfigResult').html('');
        $.ajax({
            url: urlApi,
            method: method,
            data: {"type": 'gitConfigResult', "typeConfig": typeResult},
            success: function (data) {
                data = $.parseJSON(data);
                $(data).each(function (i, val) {
                    $('#gitConfigResult').append('<p>' + val + '</p>');
                });
            }
        })

    });

    $('body').on('change', '#gitConfigResultType input', function () {
        typeResult = $('#gitConfigResultType input:checked').val();
        $('#gitConfigResult').html('');
        $.ajax({
            url: urlApi,
            method: method,
            data: {"type": 'gitConfigResult', "typeConfig": typeResult},
            success: function (data) {
                data = $.parseJSON(data);
                $(data).each(function (i, val) {
                    $('#gitConfigResult').append('<p>' + val + '</p>');
                });
            }
        })
    })

    $('body').on('click', '#sendGitConfig', function () {
        typeConfig = $('#gitConfigType input:checked').val();
        nameConfig = $('#gitConfigName').val();
        valueConfig = $('#gitConfigValue').val();
        if (typeConfig && nameConfig && valueConfig) {
            $.ajax({
                url: urlApi,
                method: method,
                data: {
                    "type": 'sendGitConfig',
                    "typeConfig": typeConfig,
                    "nameConfig": nameConfig,
                    "valueConfig": valueConfig,
                },
                success: function (data) {
                    data = $.parseJSON(data);
                    $('#gitConfigResultType input[value="' + typeConfig + '"]').trigger('change');
                }
            })
        }
    });

    $('body').on('click', '#sendDeleteGitConfig', function () {
        typeConfig = $('#gitConfigType input:checked').val();
        nameConfig = $('#gitConfigName').val();
        if (typeConfig && nameConfig) {
            $.ajax({
                url: urlApi,
                method: method,
                data: {
                    "type": 'sendDeleteGitConfig',
                    "typeConfig": typeConfig,
                    "nameConfig": nameConfig,
                },
                success: function (data) {
                    data = $.parseJSON(data);
                    $('#gitConfigResultType input[value="' + typeConfig + '"]').trigger('click');
                }
            })
        }
    });

    $('body').on('click', '#setRepoRequest', function () {

        type = $(this).data('type');
        $('#settings-area .area').hide();
        $('#' + type).show();

    });

    $('body').on('click', '#createGitHttpsRemote', function () {
        href = $('#setHrefGitHttpsRemote').val();
        password = $('#setPasswordGitHttpsRemote').val();

        $.ajax({
            url: urlApi,
            method: method,
            data: {
                "type": 'createGitHttpsRemote',
                "href": href,
                "password": password,
            },
            success: function (data) {
                console.log(data)
                //data = $.parseJSON(data);

            }
        });
    });

    $('body').on('click', '#exit', function () {
        $.ajax({
            url: urlApi,
            method: method,
            data: {"type": 'exit'},
            success: function (data) {
                window.location.reload();
            }
        })
    });
});

