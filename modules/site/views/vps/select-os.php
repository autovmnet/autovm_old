<?php
use yii\helpers\Url;
use yii\helpers\Html;

$bundle = \app\modules\site\assets\Asset::register($this);

$this->registerJs('

ij=0;
function passwordStrength(password)
{
	var desc = new Array();
	desc[0] = "Very Weak";
	desc[1] = "Weak";
	desc[2] = "Better";
	desc[3] = "Medium";
	desc[4] = "Strong";
	desc[5] = "Strongest";
	var score   = 0;
	//if password bigger than 6 give 1 point
	if (password.length >= 6) score++;
	//if password has both lower and uppercase characters give 1 point
	if ( ( password.match(/[a-z]/) ) && ( password.match(/[A-Z]/) ) ) score++;
	//if password has at least one number give 1 point
	if (password.match(/\d+/)) score++;
	//if password has at least one special caracther give 1 point
	if ( password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/) )	score++;
	//if password bigger than 12 give another 1 point
	if (password.length >= 8) score++;
	return desc[score];
}
String.prototype.shuffle = function () {
    var a = this.split(""),
        n = a.length;

    for(var i = n - 1; i > 0; i--) {
        var j = Math.floor(Math.random() * (i + 1));
        var tmp = a[i];
        a[i] = a[j];
        a[j] = tmp;
    }
    return a.join("");
}
function randomPassword(length) {
    var chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYX1234567890";
    var pass = "";
    for (var x = 0; x < length-3; x++) {
        var i = Math.floor(Math.random() * chars.length);
        pass += chars.charAt(i);
    }
    var i = Math.floor(Math.random() * 26);
    pass += chars.charAt(i);

    var i = Math.floor(Math.random() * 26 + 26);
    pass += chars.charAt(i);

    var i = Math.floor(Math.random() * 10 + 52);
    pass += chars.charAt(i);
    return pass.shuffle();
}
(function ($) {
    $.toggleShowPassword = function (options) {
        var settings = $.extend({
            field: "#password",
            control: "#toggle_show_password",
        }, options);

        var control = $(settings.control);
        var field = $(settings.field)

        control.bind(\'click\', function () {
            if (control.is(\':checked\')) {
                field.attr(\'type\', \'text\');
            } else {
                field.attr(\'type\', \'password\');
            }
        })
    };
}(jQuery));

$(document).ready(function() {
$(".strength").keyup(function(e){
if($(this).val().match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/) ){
alert("Only use [a-z],[A-Z],[0-9]!");
$(this).val("");
}
 $(".pwstrength_viewport").html(passwordStrength($(this).val()));
});
$(".btn-red").click(function(e){
 $(".strength").val(randomPassword(10));
 $(".pwstrength_viewport").html(passwordStrength($(".strength").val()));
});
$.toggleShowPassword({
    field: ".strength",
    control: "#showpassword"
});
    $("#install").click(function(e){

        self = $(this);

        var password = $("#password").val();

        if(password == "") {
            new simpleAlert({title:"Error", content:"Please try again and enter password"});
            return false;
        }

        if (!password.match(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/)) {
            new simpleAlert({title:"Error", content:"Password is too week! use [A-Z, a-z, 0-9]"});
            return false;
        }

        var _username = "administrator";

        var vpsId = ' . $vpsId . ';
        var osId = $("input[name=osId]:checked").val();

        if(osId == "" || osId == undefined) {
            new simpleAlert({title:"Error", content:"Please try again and select an operation system"});
            return false;
        }

        url = self.data("os") + "?id=" + osId;

        $.getJSON(url, function(data) {

            if (data.ok && data.status == 1) {
                _username = "administrator";
            } else if (data.ok && data.status == 2) {
                _username = "root";
            } else {
                _username = "administrator (OR) root";
            }
        });

        extend = $("input[name=extend]").prop("checked");

        function getStatus() {

        	i = setInterval(function() {

	        	url = "' . Yii::$app->urlManager->createUrl(['/site/vps/step', 'id' => $vpsId]) . '";

	        	$.getJSON(url, function(data) {

	        		if (data.ok) {

                    if (data.percent) {
                        $(".percent").text(data.percent);
                    }

					if (data.step == 1) {
						$(".steps1").find("i").css("visibility", "visible").addClass("green-text");
					}

					if (data.step >= 2) {
						$(".steps1").find("i").css("visibility", "visible").addClass("green-text");
						$(".steps2").find("i").css("visibility", "visible").addClass("green-text");
					}

					if (data.step >= 3) {
						$(".steps1").find("i").css("visibility", "visible").addClass("green-text");
						$(".steps2").find("i").css("visibility", "visible").addClass("green-text");
						$(".steps3").find("i").css("visibility", "visible").addClass("green-text");
					}

					if (data.step >= 4) {
						$(".steps1").find("i").css("visibility", "visible").addClass("green-text");
						$(".steps2").find("i").css("visibility", "visible").addClass("green-text");
						$(".steps3").find("i").css("visibility", "visible").addClass("green-text");
						$(".steps4").find("i").css("visibility", "visible").addClass("green-text");

					text = "<span><i class=\"material-icons green-text\">done</i></span>Your VM has sucessfully installed<br>";
					text += "<span><i class=\"material-icons pink-text\">perm_identity</i></span>Username: " + _username + "<br>";
					text += "<span><i class=\"material-icons pink-text\">lock</i></span>Password: " + password + "<br>";


					new simpleAlert({title:"Action Status", content:text });

						clearInterval(i);
					}

	        		}
	        	});
        	}, 2000);


        }

        extend = extend ? 1 : 0;

        $.ajax({
            type:"POST",
            dataType:"JSON",
            url:"' . Yii::$app->urlManager->createUrl('/site/vps/install') . '",
            data:{password:password, vpsId:vpsId, osId:osId, extend:extend},

            beforeSend:function() {
            	html = $(".pending").html();
            	new simpleAlert({title:"Installing", content:html});
            },

            success:function(data) {
                if (data.error == "limit") {
                    new simpleAlert({"title": "Limit", content:"You have reached the limit of change operating system"});
                } else {
                    getStatus();
                }
            }
        });
    });
});
');

?>

<style type="text/css">
    .select-os-table {
        box-shadow: none !important;
        border: 0 !important;
    }

    .select-os-table td {
        border: 0 !important;
    }
</style>



<table class="table select-os-table">
    <tbody>
    <tr>
        <td width="100%" colspan="2">
            <div class="row">
                <?php foreach ($operationSystems as $os) { ?>
                    <div class="col s6">
                        <label class="checkbox"><input type="radio" name="osId" value="<?php echo $os->id; ?>">
                            <span></span> <?php echo Html::encode($os->name); ?></label>
                    </div>
                <?php } ?>
            </div>
        </td>
    </tr>
    <tr>
        <td width="60%">
            <input type="password" name="password" id="password" placeholder="<?php echo Yii::t('app', 'Password');?>" class="form-control strength">
            <span><small>use [A-Z, a-z, 0-9]</small></span>
            <div class="pwstrength_viewport"></div>
        </td>
        <td width="40%">
            <input type="checkbox" id="showpassword" />
            <label for="showpassword"><?php echo Yii::t('app', 'Show password');?></label>
        </td>
    </tr>
    <tr>
        <td>
            <label class="checkbox"><input type="checkbox" name="extend" checked><span></span> Automatic extend disk</label>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <button class="label btn-red btn teal waves-effect waves-light" style="margin-right: 20px"><?php echo Yii::t('app', 'Random Password');?></button>
            <button class="btn waves-effect waves-light amber" type="button" id="install" data-os="<?php echo Url::toRoute(['os']);?>"><?php echo Yii::t('app', 'Install');?></button>
        </td>
    </tr>
    </tbody>
</table>

<div class="pending" style="display:none;">
	<div class="osInstalling">
	  <div class="row">
	    <div class="col s12">
          <p style="text-align:center;font-size:20px;font-weight:bold;"><span class="percent">0</span>%</p>
	      <p class="grey-text steps1"><i class="material-icons">done</i><?php echo Yii::t('app', 'Prepairing OS Files');?></p>
	      <p class="grey-text steps2"><i class="material-icons">done</i><?php echo Yii::t('app', 'Getting Files Ready For Installation');?></p>
	      <p class="grey-text steps3"><i class="material-icons">done</i><?php echo Yii::t('app', 'Installing Features');?></p>
	      <p class="grey-text steps4"><i class="material-icons">done</i><?php echo Yii::t('app', 'Finishing UP');?></p>
          <p style="text-align:center;"><img src="<?php echo $bundle->baseUrl;?>/img/prog.gif"></p>
	    </div>
	  </div>
	</div>
</div>

<div class="complete" style="display:none;">
<p class="text-center"><span><i class="material-icons" class="green-text">done</i></span><?php echo Yii::t('app', 'Your VM has been created successfully');?></p>
<p><span><i class="material-icons" class="pink-text">perm_identity</i></span>Username:<b>root</b></p>
<p><span><i class="material-icons" class="pink-text">lock</i></span>Password: <b>{{password}}</b></p>
</div>
<style>
.simple-alert{
top:8%!important;
}
</style>