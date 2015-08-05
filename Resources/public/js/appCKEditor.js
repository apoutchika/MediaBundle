(function($, ApoutchikaMedia, Backbone){
    'use strict';

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


    var url = location.search.substring(1).split('&');
    for (var i in url) {
        if (url[i].match(/^CKEditorFuncNum/)) {
            var getParam = url[i].split('=');
            var funcNum = getParam[1];
        }
    }

    ApoutchikaMedia.openList = function () {
        var field = new ApoutchikaMedia.Field({
            type: $('#apoutchika-media').attr('data-apoutchika-media-type'),
            fullName: $('#apoutchika-media').attr('data-apoutchika-media-full-name'),
            allowedExtensions: $('#apoutchika-media').attr('data-apoutchika-media-allowed_extensions').split('|'),
            filter: $('#apoutchika-media').attr('data-apoutchika-media-filter'),
            id: 'apoutchika-media'
        });

        field.set('medias', ApoutchikaMedia.medias);
        ApoutchikaMedia.fields.reset(field);

        $('#apoutchika-media-container').html('');
        $('#apoutchika-media-container').append('<div class="apoutchika-media"></div>');
        ApoutchikaMedia.lists = new ApoutchikaMedia.Lists({
            medias: ApoutchikaMedia.medias,
            field: field,
            el: $('#apoutchika-media-container div').eq(0)
        });
        ApoutchikaMedia.lists.render();


        $('.change, .name').click(function(e){
            e.preventDefault();
            var id = $(this).closest('li').attr('data-apoutchika-media-id');
            var urls = ApoutchikaMedia.medias.find(id).get('urls');
            window.opener.CKEDITOR.tools.callFunction(funcNum, urls.original);
            window.close();
        });
       
        $('.close').hide();
    };

    ApoutchikaMedia.medias.fetch({
        'data': {
            'filters': filters.join(',')
        },
        'success': function() {
            ApoutchikaMedia.openList();
        }
    });

    ApoutchikaMedia.router = new ApoutchikaMedia.Router();
    ApoutchikaMedia.router.navigate('#', {trigger: true});

})(window.jQuery, window.ApoutchikaMedia, window.Backbone);
