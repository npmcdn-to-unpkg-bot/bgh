@extends('admin.master.plain')
@section('content')

    {!! HTML::style('static/plugins/grid-editor/dist/grideditor.css') !!}
    {!! HTML::script('static/plugins/grid-editor/dist/jquery.grideditor.min.js') !!}

    {!! HTML::style('static/plugins/ContentTools/build/content-tools.min.css') !!}
    {!! HTML::script('static/plugins/ContentTools/build/content-tools.js') !!}


    {!! HTML::style('static/css/articles.css') !!}

    <style>

        .progress.active .progress-bar {
            -webkit-transition: none !important;
            transition: none !important;
        }

        .loading{
            display: none;
        }

    </style>

    {!! Form::open(['method' => 'PATCH', 'files' => true, 'id' => 'mainForm']) !!}


        <div id="myGrid">
            <?php echo $page->html ?>
        </div>


        <article class="article">
            <section class="article__content"  >

                <h1 data-fixture data-name="article-title">
                    5 rules for naming variables
                </h1>

                <div data-editable data-name="article">
                    <p class="article__by-line">
                        by <b>Anthony Blackshaw</b> · 18th January 2015
                    </p>

                    <blockquote>
                        Writing poor quality code will dramatically reduce the time
                        you get to spend doing things you love, if you’re lucky
                        enough to leave a project shortly after such a contribution
                        everyone who subsequently has to work with your code will
                        think you an arsehole.
                    </blockquote>

                    <img src="{{ url('/tests/image.png') }}" alt="Example of bad variable names">

                    <p class="article__caption">
                        <b>Above:</b> This example is code found in
                        a live and commercial project. Clearly the developer thought
                        <b>user.n5</b> would still clearly point to the 5th
                        character of the user's name when used elsewhere.
                    </p>

                    <p>
                        The names we devise for folders, files, variables,
                        functions, classes and any other user definable construct
                        will (for the most part) determine how easily someone else
                        can read and understand what we have written.
                    </p>
                    <p>
                        To avoid repetition in the remainder of this section we use
                        the term variable to cover folders, files, variables,
                        functions, and classes.
                    </p>

                    <iframe
                        width="400"
                        height="300"
                        src="https://www.youtube.com/embed/I9jSKFk5Zp0"
                        frameborder="0"
                        ></iframe>

                    <h2>Be descriptive and concise</h2>

                    <p>
                        It’s the most obvious naming rule of all but it’s also
                        perhaps the most difficult. In my younger years I would
                        often use very descriptive names, I pulled the following
                        doozy from the archives:
                    </p>

                    <pre class="example  example--bad">design_inspiration_competition_promotion_code_list</pre>

                    <p>
                        At a whopping 50 characters it can hardly be described as
                        nondescript, however I’m sure anyone who came after would
                        have cursed me for using such a long name. A far better name
                        for my list of promotion codes would have been:
                    </p>

                    <p>
                        I’ve refrained from marking either example as good or bad as
                        they are both acceptable in my eyes, however colours would
                        be the preferred term purely on the base it is the most
                        concise and natural sounding.
                    </p>

                    <p>
                        Advocates for using <i>_list</i> often argue that it reduces
                        common spelling mistakes with words like category/categories
                        and hero/heroes. I would counter that in the same way I will
                        grammar and spell check this document before asking you to
                        read it you should take the same pride in the code you
                        write.
                    </p>

                    <h2>Read your code out loud</h2>

                    <p>
                        If you can read a line of code and comprehend it in one
                        that’s a good indication that it’s well written using good
                        variable names. If on the other hand if jars or you find
                        yourself re-reading it multiple times before it makes sense
                        then there’s room for improvement.
                    </p>

                    <p>
                        Reading your code out loud (or in your head if you’re
                        attracting too much attention) is a sure fire way to spot
                        badly named variables.
                    </p>

                    <h2>Above all else be consistent</h2>

                    <p>
                        When you work with old or externally written code chances
                        are there will be difference in the rules the author(s) used
                        when coming up with variable names. Unless you’re going to
                        rewrite and maintain all the code in future do not break
                        from the existing naming conventions.
                    </p>

                    <p>
                        If they prefix numbers with num_ or append _list to lists
                        then follow suite, whilst conventional wisdom tells us two
                        wrongs don’t make a right, mixing styles within a project
                        will only make things worse.
                    </p>

                    <table>
                        <thead>
                            <tr>
                                <th>Position</th>
                                <th>Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Chair</td>
                                <td>Helen Troughton</td>
                            </tr>
                            <tr>
                                <td>Vice-chair</td>
                                <td>Sarah Stone</td>
                            </tr>
                            <tr>
                                <td>Secretary</td>
                                <td>Bridget Brickley</td>
                            </tr>
                            <tr>
                                <td>Treasurer</td>
                                <td>Sarahann Holmes</td>
                            </tr>
                            <tr>
                                <td>Publicity officer</td>
                                <td>Zillah Cimmock</td>
                            </tr>
                        </tbody>
                    </table>

                    <p>
                        If you have to work with code that has a mixture of styles
                        or no clear style at all then I refer you to my opening
                        statement.
                    </p>

                    <h2>Further reading...</h2>

                    <ul>
                        <li>
                            Code complete 2 <i>(by Steve McConnell)</i>
                        </li>
                        <li>
                            The Art of Computer Programming: Volumes 1-4a
                            <i>(by Donald E. Knuth)</i>
                        </li>
                    </ul>
                </div>

            </section>
            <aside class="article__related">
                <div
                    data-editable
                    data-name="author"
                    class="[ article__author ]  [ author ]  [ editable ]"
                    >

                    <h3 class="author__about">About the author</h3>

                    <img
                        src="{{ url('/tests/author-pic.jpg') }}"
                        alt="Anthony Blackshaw"
                        width="80"
                        class="[ author__pic ]  [ align-right ]"
                        >

                    <p class="author__bio">
                        Anthony Blackshaw is a co-founder of Getme, an
                        employee owned company with a focus on web tech. He
                        enjoys writing and talking about tech, especially
                        code and the occasional Montecristo No.2s.
                    </p>

                </div>
                <div
                    data-editable
                    data-name="learn-more"
                    class="[ article__learn-more ]  [ learn-more ]  [ editable ]"
                    >

                    <h3 data-ce-tag="static">Want to learn more?</h3>

                </div>
            </aside>
        </article>


    {!! Form::close() !!}


    {{ Form::open(['method' => 'DELETE', 'route' => ['admin.pages.edit', $page->id], 'name' => 'delete']) }}
    {{ Form::close() }}


@endsection

@section('extra-js')

    <script>

        $(document).ready(function() {



            $('#myGrid').gridEditor({
                new_row_layouts: [[12], [6,6], [9,3], [4,4,4]],
                valid_col_sizes: [3, 4, 6, 9, 12],
                content_types: ['summernote'],
                 row_tools: [{
                    title: 'Set background image',
                    iconClass: 'glyphicon-picture',
                    on: {
                        click: function() {
                            $(this).closest('.row').css('background-image', 'url(http://placekitten.com/g/300/300)');
                        }
                    }
                }],
                col_tools: [{
                    title: 'Set background2 image',
                    iconClass: 'glyphicon-picture',
                    on: {
                        click: function() {
                            $(this).closest('.column').css({
                                'background-image': 'url(http://placekitten.com/g/300/300)',
                                'background-size': 'cover',
                            });
                        }
                    }
                }],
            });


            $('#mainForm').submit(function(e) {
                e.preventDefault();
                var $this = $(this);

                $(this).ajaxSubmit({
                    url: '{{ route('admin.pages.edit',['id' => $page->id]) }}',
                    type: 'post',
                    dataType: 'json',
                    beforeSubmit: function() {
                        console.log('beforeSubmit');
                        $(".progress").show();
                        $this.find('button, input[type=submit]').prop('disabled',true);
                        $this.find('.loading').show();
                    },
                    uploadProgress: function (event, position, total, percentComplete){
                        $(".progress-bar").animate({width: percentComplete + '%'},50).html(percentComplete + '%');
                    },
                    success:function (data){
                        // reb corre con el response 200 a 300 si esta el dataType json llega con los datos, sino hay que buscar if200 en el complete que corre siempre
                        console.log('success');
                        console.log(data);
                        toastr["success"](data);
                    },
                    complete:function (data){ // corre siempre cuando termina, por error o success
                        // console.log('complete');
                        $this.find('button, input[type=submit]').prop('disabled',false);
                        $this.find('.loading').hide();
                        $(".progress").hide();
                    },
                    error: function(data){
                        console.log('error');
                        if(data.status==422){
                            var errors = data.responseJSON;
                            $.each(errors, function(index, value) {
                                toastr["error"](value);
                            });
                        }
                        else{
                            toastr["error"]('error al enviar el formulario');
                            console.log(data.status);
                            console.log(data.responseText);
                        }

                    }

                });
                return false;

            });



            $('#btn_submit').on('click',function() {

                var html = $('#myGrid').gridEditor('getHtml');


                $('<input>').attr({
                    type: 'hidden',
                    id: 'html',
                    name: 'html'
                }).appendTo('#mainForm').val(html);


                $('#mainForm').submit();

            });


            $('#btn_delete').on('click',function() {

                BootstrapDialog.show({
                    message: 'Confirm Delete?',
                    buttons: [{
                        label: 'Confirm',
                        cssClass: 'btn-primary',
                        action: function(){
                            $('form[name="delete"]').submit();
                        }
                    }, {
                        label: 'Close',
                        action: function(dlg){
                            dlg.close();
                        }
                    }]
                });

            });



        });

    </script>



    <script type="text/javascript">

        (function() {
          var ImageUploader;

          ImageUploader = (function() {
            ImageUploader.imagePath = 'image.png';

            ImageUploader.imageSize = [600, 174];

            function ImageUploader(dialog) {
              this._dialog = dialog;
              this._dialog.addEventListener('cancel', (function(_this) {
                return function() {
                  return _this._onCancel();
                };
              })(this));
              this._dialog.addEventListener('imageuploader.cancelupload', (function(_this) {
                return function() {
                  return _this._onCancelUpload();
                };
              })(this));
              this._dialog.addEventListener('imageuploader.clear', (function(_this) {
                return function() {
                  return _this._onClear();
                };
              })(this));
              this._dialog.addEventListener('imageuploader.fileready', (function(_this) {
                return function(ev) {
                  return _this._onFileReady(ev.detail().file);
                };
              })(this));
              this._dialog.addEventListener('imageuploader.mount', (function(_this) {
                return function() {
                  return _this._onMount();
                };
              })(this));
              this._dialog.addEventListener('imageuploader.rotateccw', (function(_this) {
                return function() {
                  return _this._onRotateCCW();
                };
              })(this));
              this._dialog.addEventListener('imageuploader.rotatecw', (function(_this) {
                return function() {
                  return _this._onRotateCW();
                };
              })(this));
              this._dialog.addEventListener('imageuploader.save', (function(_this) {
                return function() {
                  return _this._onSave();
                };
              })(this));
              this._dialog.addEventListener('imageuploader.unmount', (function(_this) {
                return function() {
                  return _this._onUnmount();
                };
              })(this));
            }

            ImageUploader.prototype._onCancel = function() {};

            ImageUploader.prototype._onCancelUpload = function() {
              clearTimeout(this._uploadingTimeout);
              return this._dialog.state('empty');
            };

            ImageUploader.prototype._onClear = function() {
              return this._dialog.clear();
            };

            ImageUploader.prototype._onFileReady = function(file) {
              var upload;
              console.log(file);
              this._dialog.progress(0);
              this._dialog.state('uploading');
              upload = (function(_this) {
                return function() {
                  var progress;
                  progress = _this._dialog.progress();
                  progress += 1;
                  if (progress <= 100) {
                    _this._dialog.progress(progress);
                    return _this._uploadingTimeout = setTimeout(upload, 25);
                  } else {
                    return _this._dialog.populate(ImageUploader.imagePath, ImageUploader.imageSize);
                  }
                };
              })(this);
              return this._uploadingTimeout = setTimeout(upload, 25);
            };

            ImageUploader.prototype._onMount = function() {};

            ImageUploader.prototype._onRotateCCW = function() {
              var clearBusy;
              this._dialog.busy(true);
              clearBusy = (function(_this) {
                return function() {
                  return _this._dialog.busy(false);
                };
              })(this);
              return setTimeout(clearBusy, 1500);
            };

            ImageUploader.prototype._onRotateCW = function() {
              var clearBusy;
              this._dialog.busy(true);
              clearBusy = (function(_this) {
                return function() {
                  return _this._dialog.busy(false);
                };
              })(this);
              return setTimeout(clearBusy, 1500);
            };

            ImageUploader.prototype._onSave = function() {
              var clearBusy;
              this._dialog.busy(true);
              clearBusy = (function(_this) {
                return function() {
                  _this._dialog.busy(false);
                  return _this._dialog.save(ImageUploader.imagePath, ImageUploader.imageSize, {
                    alt: 'Example of bad variable names'
                  });
                };
              })(this);
              return setTimeout(clearBusy, 1500);
            };

            ImageUploader.prototype._onUnmount = function() {};

            ImageUploader.createImageUploader = function(dialog) {
              return new ImageUploader(dialog);
            };

            return ImageUploader;

          })();

          window.ImageUploader = ImageUploader;

          window.onload = function() {
            var FIXTURE_TOOLS, editor, req;
            ContentTools.IMAGE_UPLOADER = ImageUploader.createImageUploader;
            ContentTools.StylePalette.add([new ContentTools.Style('By-line', 'article__by-line', ['p']), new ContentTools.Style('Caption', 'article__caption', ['p']), new ContentTools.Style('Example', 'example', ['pre']), new ContentTools.Style('Example + Good', 'example--good', ['pre']), new ContentTools.Style('Example + Bad', 'example--bad', ['pre'])]);
            editor = ContentTools.EditorApp.get();
            editor.init('[data-editable], [data-fixture]', 'data-name');
            editor.addEventListener('saved', function(ev) {
              var saved;
              console.log(ev.detail().regions);
              if (Object.keys(ev.detail().regions).length === 0) {
                return;
              }
              editor.busy(true);
              saved = (function(_this) {
                return function() {
                  editor.busy(false);
                  return new ContentTools.FlashUI('ok');
                };
              })(this);
              return setTimeout(saved, 2000);
            });
            FIXTURE_TOOLS = [['undo', 'redo', 'remove']];
            ContentEdit.Root.get().bind('focus', function(element) {
              var tools;
              if (element.isFixed()) {
                tools = FIXTURE_TOOLS;
              } else {
                tools = ContentTools.DEFAULT_TOOLS;
              }
              if (editor.toolbox().tools() !== tools) {
                return editor.toolbox().tools(tools);
              }
            });


            // req = new XMLHttpRequest();
            // req.overrideMimeType('application/json');
            // req.open('GET', 'https://raw.githubusercontent.com/GetmeUK/ContentTools/master/translations/lp.json', true);
            // return req.onreadystatechange = function(ev) {
            //   var translations;
            //   if (ev.target.readyState === 4) {
            //     translations = JSON.parse(ev.target.responseText);
            //     ContentEdit.addTranslations('lp', translations);
            //     return ContentEdit.LANGUAGE = 'lp';
            //   }
            // };

          };

        }).call(this);


    </script>


@endsection