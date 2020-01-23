(function($, window) {

    var simpleAlert = function(options) {
        this.options = options;

        this.init();
        this.show();
    };

    simpleAlert.prototype = {
        _defaults:{
            title:"Title",
            content:"Content",
            pattern:'<div class="simple-alert-overlay"><div class="simple-alert"><h3>{{title}}<span class="simple-alert-close">&times;</span></h3><div class="simple-alert-content">{{content}}</div></div></div>',
            overlayId:".simple-alert-overlay",
            closeBtnId:".simple-alert-close",
        },

        init:function() {
            this.config = $.extend({}, this._defaults, this.options);
        },

        build:function() {
            var html = this.config.pattern;

            html = html.replace("{{title}}", this.config.title);
            html = html.replace("{{content}}", this.config.content);

            return html;
        },

        show:function() {
            var self = this;

            if($(self.config.overlayId) !== null) {
                $(self.config.overlayId).remove();
            }

            $("body").append(self.build());
            $(self.config.overlayId).hide().fadeIn(100);

            $(self.config.closeBtnId).on("click", function() {
                $(self.config.overlayId).fadeOut(100);
            });
        }
    };

    window.sAlert = window.simpleAlert = simpleAlert;

})(jQuery, window);