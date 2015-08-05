(function($, ApoutchikaMedia, Backbone, Mustache){
    'use strict';

    ApoutchikaMedia.FieldView = Backbone.View.extend({
        model: ApoutchikaMedia.Media,
        field: ApoutchikaMedia.Field,
        fieldId: null,
        template: $('#apoutchika-media-view-field').html(),
        initialize: function(options){
            this.field = options.field;
            this.fieldId = options.fieldId;

            this.model.on('destroy', this.removeMedia, this);
            this.model.on('change', this.render, this);
            this.model.on('all', this.render, this);
        },
        events: {
            'click .unselect': 'unselectMedia'
        },
        render: function() {
            var that = this;
            this.$el.html(Mustache.render(this.template, {
                fieldId: this.fieldId,
                media: this.model.toJSON(),
                field: this.field.toJSON(),
                isOne: (this.field.get('type') === 'one'),
                isImage: function(){
                    var mime = that.model.get('mime_type').split('/');
                    return (mime[0] === 'image');
                }
            }));
        },
        unselectMedia: function(e) {
            e.preventDefault();
            console.log('toto');
            this.field.get('medias').remove(this.model);
            this.remove();
        },
        removeMedia: function(){
            this.remove();
        }
    });

    ApoutchikaMedia.FieldsView = Backbone.View.extend({
        model: ApoutchikaMedia.Field,
        fieldId: 0,
        template: $('#apoutchika-media-view-fields').html(),
        initialize: function(){
            this.model.get('medias').on('add', this.addMedia, this);
            this.model.get('medias').on('remove', this.render, this);
        },
        render: function() {
            this.$el.html(Mustache.render(this.template, {
                field: this.model.toJSON()
            }));

            var that = this;
            this.model.get('medias').each(function(media){
                that.renderRow(media);
            });
        },
        addMedia: function(media) {
            if (this.model.get('type') === 'one') {
                this.model.get('medias').reset(media);
                this.render();
            }
            else {
                this.renderRow(media);
            }
        },
        renderRow: function(media) {

            var fieldView = new ApoutchikaMedia.FieldView({
                model: media,
                field: this.model,
                fieldId: this.fieldId
            });

            fieldView.render();

            this.fieldId += 1;

            this.$el.find('.medias').append(fieldView.el);
        }
    });
})(window.jQuery, window.ApoutchikaMedia, window.Backbone, window.Mustache);
