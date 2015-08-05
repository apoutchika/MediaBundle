(function($, ApoutchikaMedia, Backbone){
    'use strict';

    ApoutchikaMedia.Router = Backbone.Router.extend({
        'routes': {
            'apoutchika/media/:fieldId/list': 'mediaList',
            'apoutchika/media/:fieldId/add': 'mediaAdd',
            'apoutchika/media/edit/:mediaId': 'mediaEditor',
            '*actions': 'defaultRoute'
        },
        'defaultRoute': function(){
            $('#apoutchika-media-container').html('');
        },
        'getEl': function(){
            $('#apoutchika-media-container').html('');
            $('#apoutchika-media-container').append('<div class="apoutchika-media"></div>');
            return $('#apoutchika-media-container div').eq(0);
        },
        'mediaList': function(fieldId){
            var field = ApoutchikaMedia.fields.get(fieldId);
            if (field !== undefined) {
                var lists = new ApoutchikaMedia.Lists({
                    'medias': ApoutchikaMedia.medias,
                    'field': field,
                    'el': this.getEl()
                });

                lists.render();
            }
        },
        'mediaAdd': function(fieldId){
            var field = ApoutchikaMedia.fields.get(fieldId);
            if (field !== undefined) {
                var add = new ApoutchikaMedia.Add({
                    'model': field,
                    'el': this.getEl()
                });

                add.render();
            }
        },
        'mediaEditor': function(mediaId){
            var media = ApoutchikaMedia.medias.get(mediaId);
            if (media !== undefined) {
                var editor = new ApoutchikaMedia.Editor({
                    'el': this.getEl(),
                    'model': media
                });

                editor.render();
            }
        }
    });

    Backbone.history.start();

})(window.jQuery, window.ApoutchikaMedia, window.Backbone);
