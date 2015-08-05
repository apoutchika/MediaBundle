(function($, ApoutchikaMedia, Backbone){
    'use strict';

    ApoutchikaMedia.Media = Backbone.JJRelationalModel.extend({
        'urlRoot': $('#apoutchikaMediaApiUrl').val(),
        // JJRelational
        'storeIdentifier': 'ApoutchikaMedia.Media',
        'relations': [{
            'type': 'many_many',
            'relatedModel': 'ApoutchikaMedia.Field',
            'key': 'fields',
            'reverseKey': 'medias',
            'collectionType': 'ApoutchikaMedia.Fields',
            'includeInJSON': ['id']
        }],
        'defaults': {
            'id': null,
            'name': null,
            'height': null,
            'width': null,
            'focus_left': 50,
            'focus_top': 50,
            'size': null,
            'reference': null,
            'mime_type': null,
            'type': null,
            'created_at': null,
            'updated_at': null,
            'is_image': null,
            'rotate': null,
            'mirror': null
        }
    });

    ApoutchikaMedia.Field = Backbone.JJRelationalModel.extend({

        // JJRelational
        'storeIdentifier': 'ApoutchikaMedia.Field',
        'relations': [{
            'type': 'many_many',
            'relatedModel': 'ApoutchikaMedia.Media',
            'key': 'medias',
            'reverseKey': 'fields',
            'collectionType': 'ApoutchikaMedia.Medias',
            'includeInJSON': ['id']
        }],
        'defaults': {
            'id': null,
            'type': null,
            'fullName': null,
            'allowedExtensions': [],
            'filter': null
        }
    });

})(window.jQuery, window.ApoutchikaMedia, window.Backbone);
