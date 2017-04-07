// Filename: views/account
define([
    'jquery',
    'underscore',
    'backbone'
    ], function($, _, Backbone){
        var accountView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function(){
            },
            events: {
                // "click a.action-delete": "doDelete",
                //"click a.order": "showColumnByOrder"
            },
            accountManage: function(that){
                var action = $(that).attr("action");
                var html = action;
                // alert(2123);return;
                if(action != "logout"){
                    if(action == "login"){
                        html = '<form id="login-form" method="post">'+
                            '<p class="message error no-margin"></p>'+
                            '<label style="float: left; width: 90px; margin-left: 62px">'+trans("email")+'</label>'+
                            '<input type="text" name="identity" style="margin-bottom: 10px; width: 250px" /><br />'+
                            '<label style="float: left; width: 90px; margin-left: 62px">'+trans("password")+'</label>'+
                            '<input type="password" name="password" style="margin-bottom: 10px; width: 250px" /><br />'+
                            '<label style="float: left; width: 90px; margin-left: 62px">&nbsp;</label>'+
                            '<div style="margin-bottom: 10px"><input type="checkbox" name="remember" style="margin-left: 0" />'+trans("remmber_me")+'</div>'+
                            '<label style="float: left; width: 90px; margin-left: 62px">&nbsp;</label>'+
                            '<div style="margin-bottom: 10px"><button type="submit" name="submit" class="ui-button">' + action + '</button></div>'+
                            '<a class="forgotten_password" href="#">'+trans("forgot_pass")+'</a></form>';
                        width = 500;
                        height = 'auto';
                    }
                    if(action == 'register'){
                        html = '<iframe style="width: 100%; height: 100%; overflow: hidden" src="' + $base_url + 'account/register"></iframe>';
                        width = 900;
                        height = 400;
                    }
                    if(action == 'change-pass'){
                        html = '<iframe style="width: 100%; height: 100%; overflow: hidden" src="' + $base_url + 'account/change_password"></iframe>';
                        width = 500;
                        height = 300;
                    }
                    if(action == 'update-info'){
                        html = '<iframe url="' + location.href + '" style="width: 100%; height: 100%; overflow: hidden" src="' + $base_url + 'account/update"></iframe>';
                        width = 900;
                        height = 400;
                    }
                    $("#account-dialog").dialog({
                        modal: true,
                        title: action,
                        width: width,
                        height: height,
                        open: function(event, ui){
                            $(this).html(html);
                            $("button[name='submit']").click(function(){
                                $.ajax({
                                    url: $base_url + 'auth/vfdb_auth/' + action,
                                    type: 'post',
                                    data: $("#login-form").serialize(),
                                    async: false,
                                    success: function(rs){
                                        url = "";
                                        if(rs != ""){
                                            rs = JSON.parse(rs);
                                            if(rs.error != ''){
                                                err = rs.error;
                                                if(action == "login"){
                                                    $.ajax({
                                                        url: $base_url + 'auth/auth/' + action,
                                                        type: 'post',
                                                        data: $("#login-form").serialize(),
                                                        async: false,
                                                        success: function(rs1){
                                                            if(rs1 != ""){
                                                                rs1 = JSON.parse(rs1);
                                                                if(rs1.error != ''){
                                                                    err = rs1.error;
                                                                }
                                                                if(typeof rs1.url != "undefined"){
                                                                    url = rs1.url;
                                                                }
                                                                console.log(rs1);
                                                            }
                                                        }
                                                    });
                                                }
                                                $("#login-form .error").html(err);
                                            }
                                            if(typeof rs.url != "undefined"){
                                                url = rs.url;
                                            }
                                            console.log(rs);
                                            console.log("url:" + url);
                                            if(url != ""){
                                                location.href = $base_url;
                                            }
                                        }
                                    }
                                });
                                return false;
                            });
                        }
                    });
                }
            },
            render: function(){
                if(typeof this[$app.action] != 'undefined'){
                    new this[$app.action];
                }
            }
        });
        return accountView = new accountView;
    });

            