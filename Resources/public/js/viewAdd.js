(function($, ApoutchikaMedia, Backbone, Mustache, _, Dropzone){
    'use strict';

    ApoutchikaMedia.Add = Backbone.View.extend({
        template: $('#apoutchika-media-view-add').html(),
        errors: false,
        initialize: function(){
        },
        events: {
            'click .modal-title .icon-close': 'closeAdd',
            'click .modal-bg': 'closeAdd'
        },
        render: function(){
            this.$el.html(
                Mustache.render(this.template, {
                    'field': this.model.toJSON(),
                    'allowedExtensions': this.model.get('allowedExtensions').join(', '),
                    'filter': this.model.get('filter')
                })
            );

            var that = this;


            var successFunction = function(){
                var response = $('#my_iframe').contents().find('body').html();
                that.mediaAdd(null, response);
                that.closeAdd();
            };

            var redirect = function() {
                var callback = function(){
                    if (successFunction) {
                        successFunction();
                    }
                    $('#my_iframe').unbind('load', callback);
                };

                $('#my_iframe').bind('load', callback);
                $('.apoutchika-media-dropzone-form')
                    .append('<input type="hidden" name="iframe" value="true">');
                $('.apoutchika-media-dropzone-form').submit();
            };


            $('.fallback').hide();

            /**
             * Verify extension
             *
             * @param string filename
             *
             * @return object {ext: string, allowed: boolean}
             */
            function verifyExtension (filename) {
                    // create string with extensions : png
                    var ext = filename.toLowerCase().replace(/^.*\.([^.]+)$/, '$1');

                    // search if ext is in exts
                    return {
                        'ext': ext,
                        'allowed': (_.indexOf(that.model.get('allowedExtensions'), ext) > -1)
                    };
            }

            new Dropzone('.apoutchika-media-dropzone-form', { // eslint-disable-line
                url: $('.apoutchika-media-dropzone-form').attr('action'),
                previewTemplate: this.$el.find('#dz-view').html(),
                maxFileSize: 0,
                thumbnailHeight: 40,
                // forceFallback: true,
                thumbnailWidth: 40,
                fallback: function() {
                    $('.hide-if-fallback').hide();
                    $('.fallback').show();
                    $('.apoutchika-media-dropzone-form').attr('target', 'my_iframe');
                    $('.apoutchika-media-dropzone-form')
                        .find('input[type="button"]')
                        .click(function(e){
                            e.preventDefault();

                            var verify = verifyExtension($('.fallback input[type="file"]').val());

                            if (verify.allowed) {
                                $('.fallback input[type="button"]').prop('disabled', true);
                                redirect();
                            }
                            else {
                                alert($('#trans_js_extensionNotAllowed').val().replace('%extension%', verify.ext));
                            }
                        });
                },
                accept: function (file, done) {

                    var verify = verifyExtension(file.name);
                    if (verify.allowed) {
                        done();
                    }
                    else {
                        done($('#trans_js_extensionNotAllowed').val().replace('%extension%', verify.ext));
                    }
                },
                init: function(){
                    this.on('addedfile', function(file) {
                        $('.dropzone-text').hide();
                        var el = $(file.previewElement);

                        el.find('.dz-error').hide();
                        el.find('.dz-success').hide();
                        el.find('.dz-name').html(file.name);
                        el.show();
                    });
                    this.on('uploadprogress', function(file, progress) {
                        $(file.previewElement).find('.dz-upload').html(
                            $(file.previewElement)
                                .find('.loader-status')
                                .css('width', Math.round(progress) + '%')
                        );
                    });
                    this.on('thumbnail', function(file, dataUrl) {
                        $(file.previewElement).find('.dz-thumbnail').html(
                            '<img width="40" height="40" src="' + dataUrl + '" alt="thumbnail" />'
                        );
                    });

                    this.on('success', function(file, response) {
                        var el = $(file.previewElement);
                        el.find('.dz-status').addClass('success');
                        el.find('.loader-status').css('width', '100%');
                        that.mediaAdd(file, response);
                    });

                    this.on('error', function(file, response) {
                        that.errors = true;
                        var el = $(file.previewElement);
                        el.find('.dz-status').addClass('error');
                        el.find('.dz-error-message').html(response);
                    });

                    this.on('canceled', function(file) {
                        that.errors = true;
                        $(file.previewElement).hide();
                    });

                    this.on('removedfile', function(file) {
                        that.errors = true;
                        $(file.previewElement).hide();
                    });

                    this.on('queuecomplete', function() {
                        // close if no errors
                        if (that.errors === false) {
                            that.closeAdd();
                        }
                    });
                }
            });
        },
        mediaAdd: function(file, response){
            var media = new ApoutchikaMedia.Media($.parseJSON(response));
            ApoutchikaMedia.medias.add(media);
            this.model.get('medias').add(media);
        },
        closeAdd: function(e){
            if (e !== undefined) {
                e.preventDefault();
            }
            window.history.back();
        }
    });

})(window.jQuery, window.ApoutchikaMedia, window.Backbone, window.Mustache, window._, window.Dropzone);
