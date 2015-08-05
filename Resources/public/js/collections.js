(function(ApoutchikaMedia, Backbone){
    'use strict';

    ApoutchikaMedia.Medias = Backbone.Collection.extend({
        'model': ApoutchikaMedia.Media,
        'initialize': function(options){
            if (options !== undefined && options.url !== undefined) {
                this.url = options.url;
                this.on('add', this.setIsImage, this);
            }
        },
        'setIsImage': function(media){
            if (media.get('mime_type') !== null) {
                var mime = media.get('mime_type').split('/');
                media.set('is_image', (mime[0] === 'image'));
            }
        }
    });

    ApoutchikaMedia.Fields = Backbone.Collection.extend({
        'model': ApoutchikaMedia.Field
    });

})(window.ApoutchikaMedia, window.Backbone);
