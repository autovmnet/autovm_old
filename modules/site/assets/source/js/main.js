function changeOs(target) {

    var id = target.data("id");

    $.ajax({
        url:baseUrl + "/site/vps/select-os",
        type:'POST',
        dataType:'HTML',
        data:{vpsId:id},
        success:function(data){
            new simpleAlert({title:'Select Operation System', content:data});
        },
        beforeSend:function() {
            new simpleAlert({title:'Loading', content:'Please wait a moment...'});
        }
    });
}

function loadHostName(target) {

    var id = target.data("id");

    $.ajax({
        url:baseUrl + "/site/vps/load-host",
        type:'POST',
        dataType:'HTML',
        data:{vpsId:id},
        success:function(data){
            new simpleAlert({title:'Change Host Name', content:data});
        },
        beforeSend:function() {
            new simpleAlert({title:'Loading', content:'Please wait a moment...'});
        }
    });
}

function loadIso(target) {

    var id = target.data("id");

    $.ajax({
        url:baseUrl + "/site/vps/select-iso",
        type:'POST',
        dataType:'HTML',
        data:{vpsId:id},
        success:function(data){
            new simpleAlert({title:'Select Iso', content:data});
        },
        beforeSend:function() {
            new simpleAlert({title:'Loading', content:'Please wait a moment...'});
        }
    });
}

function loadShot(target) {

    var id = target.data("id");

    $.ajax({
        url:baseUrl + "/site/vps/select-shot",
        type:'POST',
        dataType:'HTML',
        data:{vpsId:id},
        success:function(data){
            new simpleAlert({title:'Snapshot', content:data});
        },
        beforeSend:function() {
            new simpleAlert({title:'Loading', content:'Please wait a moment...'});
        }
    });
}

function resetOs(target) {

    var id = target.data("id");

    $.ajax({
        url:baseUrl + "/site/vps/restart",
        type:'POST',
        dataType:'JSON',
        data:{vpsId:id},
        success:function(data){
            if(data.status == 1) {
                new simpleAlert({title:'Action Status', content:'The VM has been successfully restarted.'});
            } else {
                if (data.message) {
                    new simpleAlert({title:'Action Status', content:data.message});
                } else {
                    new simpleAlert({title:'Action Status', content:'There is an error, please try again'});
                }
            }
        },
        beforeSend:function() {
            new simpleAlert({title:'Loading', content:'Please wait a moment...'});
        }
    });
}

function stopVps(target) {

    var id = target.data("id");

    $.ajax({
        url:baseUrl + "/site/vps/stop",
        type:'POST',
        dataType:'JSON',
        data:{vpsId:id},
        success:function(data){
            if(data.status == 1) {
                new simpleAlert({title:'Action Status', content:'The VM has been successfully powered off.'});
            } else {
                if (data.message) {
                    new simpleAlert({title:'Action Status', content:data.message});
                } else {
                    new simpleAlert({title:'Action Status', content:'There is an error, please try again'});
                }
            }
        },
        beforeSend:function() {
            new simpleAlert({title:'Loading', content:'Please wait a moment...'});
        }
    });
}

function startVps(target) {

    var id = target.data("id");

    $.ajax({
        url:baseUrl + "/site/vps/start",
        type:'POST',
        dataType:'JSON',
        data:{vpsId:id},
        success:function(data){
            if(data.status == 1) {
                new simpleAlert({title:'Action Status', content:'The VM has been successfully powered on.'});
            } else {
                if (data.message) {
                    new simpleAlert({title:'Action Status', content:data.message});
                } else {
                    new simpleAlert({title:'Action Status', content:'There is an error, please try again'});
                }
            }
        },
        beforeSend:function() {
            new simpleAlert({title:'Loading', content:'Please wait a moment...'});
        }
    });
}

function statusVps(target) {

    var id = target.data("id");

    $.ajax({
        url:baseUrl + "/site/vps/advanced-status",
        type:'POST',
        dataType:'JSON',
        data:{vpsId:id},
        success:function(data){
            if(data.ok) {
                new simpleAlert({title:'Action Status', content:'The VM is ' + data.power + ' and the network is ' + data.network});
            } else {
                new simpleAlert({title:'Action Status', content:'There is an error, please try again'});
            }
        },
        beforeSend:function() {
            new simpleAlert({title:'Loading', content:'Please wait a moment...'});
        }
    });
}

function monitorVps(target) {

    var id = target.data("id");

    $.ajax({
        url:baseUrl + "/site/vps/monitor",
        type:'POST',
        dataType:'HTML',
        data:{vpsId:id},
        success:function(data){
            if (data == "") {
                new simpleAlert({title:'Action Status', content:'There is an error, please try again'});
            } else {
                new simpleAlert({title:'Monitor', content:data});
            }
        },
        beforeSend:function() {
            new simpleAlert({title:'Loading', content:'Please wait a moment...'});
        }
    });
}

function extendVps(target) {

    var id = target.data("id");

    $.ajax({
        url:baseUrl + "/site/vps/extend-form",
        type:'POST',
        dataType:'HTML',
        data:{vpsId:id},
        success:function(data){
            new simpleAlert({title:'Extend hard', content:data});
        },
        beforeSend:function() {
            new simpleAlert({title:'Loading', content:'Please wait a moment...'});
        }
    });
}

function logVps(target) {

    var id = target.data("id");

    $.ajax({
        url:baseUrl + "/site/vps/action-log",
        type:'POST',
        dataType:'HTML',
        data:{vpsId:id},
        success:function(data){
            new simpleAlert({title:'Action logs', content:data});
        },
        beforeSend:function() {
            new simpleAlert({title:'Loading', content:'Please wait a moment...'});
        }
    });
}

function consoleVps(target) {

    var id = target.data("id");

    $.ajax({
        url:baseUrl + "/site/vps/console-form",
        type:'POST',
        dataType:'HTML',
        data:{vpsId:id},
        success:function(data){
            new simpleAlert({title:'Console', content:data});
        },
        beforeSend:function() {
            new simpleAlert({title:'Loading', content:'Please wait a moment...'});
        }
    });
}

function loadServer(target) {

    id = target.data("id");

    jQuery.ajax({
        url: baseUrl + "/site/vps/index",
        type: "GET",
        data:{id : id},
        success: function(data) {
            target.find(".collapsible-body").html(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('failed');
        }
    });
}

function reloadPage() {
    location.reload();
}


$(function() {

    $(".collapsible li a.btn").on("click",function(e){

        setTimeout(function() {
        	//var indexCol = $(this).parent().parent().parent().index();
        	//$('.collapsible').collapsible('close', indexCol-1);
        	$(".active").removeClass("active");
		$(".collapsible-body").removeAttr("style").hide();
  	}, 100);

    });

    //$(".collapsible").collapsible();

    $(".access a").click(function(e) {

        target = $(this);

        action = target.data("action");

        if (action == 1) {
            changeOs(target);
        } else if (action == 2) {
            resetOs(target);
        } else if (action == 3) {
            stopVps(target);
        } else if (action == 4) {
            startVps(target);
        } else if(action == 5) {
            statusVps(target);
        } else if (action == 6) {
            monitorVps(target);
        } else if (action == 7) {
            extendVps(target);
        } else if (action == 8) {
            logVps(target);
        } else if (action == 9) {
            consoleVps(target);
        } else if (action == 10) {
            loadIso(target);
        } else if (action == 11) {
            loadShot(target);
        } else if (action == 12) {
            loadHostName(target);
        }
    });

    $(".collapsible li").click(function(e) {

        e.preventDefault();

        self = $(this);
        target = $(e.target);

        action = target.data("action");

        if (action == 1) {
            changeOs(target);
        } else if (action == 2) {
            resetOs(target);
        } else if (action == 3) {
            stopVps(target);
        } else if (action == 4) {
            startVps(target);
        } else if(action == 5) {
            statusVps(target);
        } else if (action == 6) {
            monitorVps(target);
        } else if (action == 7) {
            extendVps(target);
        } else if (action == 8) {
            logVps(target);
        } else if (action == 9) {
            consoleVps(target);
        } else if (action == 10) {
            loadIso(target);
        } else if (action == 11) {
            loadShot(target);
        } else if (action == 12) {
            loadHostName(target);
        } else {
            loadServer(self);
        }
    });
});
