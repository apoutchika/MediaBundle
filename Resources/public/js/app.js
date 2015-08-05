(function($, ApoutchikaMedia, Backbone){
    'use strict';

    ApoutchikaMedia.router = new ApoutchikaMedia.Router();
    ApoutchikaMedia.router.navigate('#', {trigger: true});

    Backbone.JJRelational.registerCollectionTypes({
        'Fields': ApoutchikaMedia.Fields,
        'Medias': ApoutchikaMedia.Medias
    });

    ApoutchikaMedia.medias = new ApoutchikaMedia.Medias({
        'url': $('#apoutchikaMediaApiUrl').val()
    });

    ApoutchikaMedia.fields = new ApoutchikaMedia.Fields();

    // Get all filter
    var filters = [];
    $('.apoutchika-media').map(function(){
        filters.push($(this).attr('data-apoutchika-media-filter'));
    });

    ApoutchikaMedia.medias.fetch({
        'data': {
            'filters': filters.join(',')
        },
        'success': function() {
            $('.apoutchika-media').map(function(){

                var field = new ApoutchikaMedia.Field({
                    'type': $(this).attr('data-apoutchika-media-type'),
                    'fullName': $(this).attr('data-apoutchika-media-full-name'),
                    'allowedExtensions': $(this).attr('data-apoutchika-media-allowed_extensions').split('|'),
                    'filter': $(this).attr('data-apoutchika-media-filter'),
                    'id': $(this).attr('id')
                });

                $(this).find('input').map(function(){
                    var media = ApoutchikaMedia.medias.get($(this).val());

                    if (media !== undefined) {
                        field.get('medias').add(media);
                    }
                });

                var fieldsView = new ApoutchikaMedia.FieldsView({
                    'el': $(this).find('.medias').eq(0),
                    'model': field
                });

                fieldsView.render();

                ApoutchikaMedia.fields.add(field);
            });
        }
    });
})(window.jQuery, window.ApoutchikaMedia, window.Backbone);
