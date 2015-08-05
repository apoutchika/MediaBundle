(function($, ApoutchikaMedia, Backbone, Mustache, _, confirm){
    'use strict';

    ApoutchikaMedia.List = Backbone.View.extend({
        template: $('#apoutchika-media-view-list').html(),
        media: ApoutchikaMedia.Media,
        field: ApoutchikaMedia.Field,
        initialize: function(options){
            this.field = options.field;
            this.media = options.media;

            this.media.on('destroy', this.removeMedia, this);
            this.media.on('change', this.render, this);
            this.field.get('medias').on('add', this.render, this);
        },
        events: {
            'click .apoutchika-media-unselect': 'unselectMedia',
            'click .apoutchika-media-select': 'selectMedia',
            'click .apoutchika-media-remove': 'destroyMedia',
            'mouseover .name': 'hoverName',
            'mouseout .name': 'hoverName',
            'click .name': 'switchSelect'
        },
        hoverName: function(){
            var el = this.$el.find('.change .icon');
            if (this.mediaIsSelected()) {
                el.toggleClass('icon-select');
                el.toggleClass('icon-select-hover');
            }
            else {
                el.toggleClass('icon-unselect');
                el.toggleClass('icon-unselect-hover');
            }
        },
        mediaIsSelected: function() {
            return (this.field.get('medias').get(this.media.get('id')) !== undefined);
        },
        switchSelect: function(e){
            e.preventDefault();
            if (this.mediaIsSelected()) {
                this.unselectMedia(e);
            }
            else {
                this.selectMedia(e);
            }
        },
        render: function(){
            var that = this;
            this.$el.html(Mustache.render(this.template, {
                field: this.field.toJSON(),
                media: this.media.toJSON(),
                isActive: this.mediaIsSelected(),
                isImage: function(){
                    var mime = that.media.get('mime_type').split('/');
                    return (mime[0] === 'image');
                }
            }));
        },
        unselectMedia: function(e) {
            e.preventDefault();
            this.field.get('medias').remove(this.media);
            this.render();
        },
        selectMedia: function(e) {
            e.preventDefault();
            this.field.get('medias').add(this.media);
            this.render();
        },
        destroyMedia: function(e){
            e.preventDefault();
            if (confirm($('#trans_js_confirmDelete').val())){
                this.media.destroy({
                    data: {filter: this.media.get('filter')},
                    processData: true
                });
            }
        },
        removeMedia: function(){
            this.remove();
        }
    });

    ApoutchikaMedia.Lists = Backbone.View.extend({
        template: $('#apoutchika-media-view-lists').html(),
        searchResults: null,
        field: ApoutchikaMedia.Field,
        medias: ApoutchikaMedia.Medias,

        initialize: function(options){
            this.field = options.field;
            this.medias = options.medias;
        },
        events: {
            'click .modal-title .icon-close': 'closeAdd',
            'click .modal-bg': 'closeAdd',
            'keyup .apoutchika-media-search-text': 'search',
            'change .apoutchika-media-search-type': 'search',
            'click .apoutchika-media-search-search': 'search'
        },
        search: function () {
            var data = {
                q: this.$el.find('.apoutchika-media-search-text').val(),
                type: this.$el.find('.apoutchika-media-search-type').val(),
                filter: this.field.get('filter')
            };

            var that = this;
            $.get($('#apoutchikaMediaApiUrl_search').val(), data, function(response){
                that.searchResults = response;
                that.renderList();
            }, 'json');
        },
        render: function(){
            this.$el.html(Mustache.render(this.template, {
                field: this.field.toJSON()
            }));

            var that = this;
            var mediasSize = this.medias.size();

            this.medias.comparator = function (media) {
                var start = null;
                if (that.field.get('medias').get(media.get('id')) === undefined) {
                    start = mediasSize;

                } else {
                    start = 1;
                }

                return ((start * start) - media.get('id'));
            };

            this.medias.sort();
            this.renderList();
        },
        renderList: function(){
            this.$el.find('.medias').html('');
            var that = this;
            this.medias.each(function(media){

                if (_.indexOf(that.field.get('allowedExtensions'), media.get('extension')) === -1) {
                    return;
                }

                if (media.get('filter') !== that.field.get('filter')) {
                    return;
                }

                if (that.searchResults !== null && _.indexOf(that.searchResults, media.get('id')) === -1) {
                    return;
                }

                var list = new ApoutchikaMedia.List({
                    media: media,
                    field: that.field
                });

                list.render();

                that.$el.find('.medias').append(list.el);
            });
        },
        removeMedia: function(media){
            $('#apoutchika-media-view-list-' + this.field.get('id') + '-' + media.get('id')).remove();
        },
        toggleMedia: function (media){
            var list = new ApoutchikaMedia.List({
                media: media,
                field: this.field
            });
            $('#apoutchika-media-view-list-' + this.field.id + '-' + media.id).replaceWith(list.render());
        },
        closeAdd: function(e){
            e.preventDefault();
            ApoutchikaMedia.router.navigate('#', {trigger: true});
        }
    });

})(window.jQuery, window.ApoutchikaMedia, window.Backbone, window.Mustache, window._, window.confirm);
