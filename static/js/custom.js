$(function () {
    favorite();
    var displayImage = $('img.display-image');
    displayImage.lazyload({threshold: 200});
    lightbox();
    deleteComment();
    followUnFollow();
    deleteReply();
    reply();
    voteComment();
    voteReply();
    keyboardNavigation();
    autosize(document.querySelectorAll('textarea'));
    var time = $('abbr.timeago');
    time.timeago();
    $('div.flash_message').not('.flash_important').delay(2000).slideUp();
    if ($('.mainImage').length) {
        colorChange();
    }
    $("[data-toggle='tooltip']").tooltip();
    $('#dp1').datepicker({
        format: 'yyyy-mm-dd'
    });
});

function lightbox() {
    $('#galley').lightGallery({
        selector: '.links',
        thumbnail: false,
        hash: false,
        download: false
    });
    $('.main-image').lightGallery({
        selector: '.image',
        thumbnail: false,
        hash: false,
        download: false
    });
}

function colorChange() {
    var myImage = $('.mainImage');
    var img = new Image();
    img.onload = function () {
        var colorThief = new ColorThief();
        var cp = colorThief.getPalette(img, 6);
        for (var i = 0; i < cp.length; i++) {
            $('.colorPalettes').append('<div class="colorPalette" style="background-color:rgb(' + cp[i][0] + ',' + cp[i][1] + ',' + cp[i][2] + ')"></div>');
        }
    };
    img.crossOrigin = 'Anonymous';
    img.src = myImage.attr('src');
}
function reply() {
    var c = $(".replybutton");
    var b = $(".closebutton");
    var a = $(".replytext");
    c.on("click", function () {
        var d = $(this).attr("id");
        $(this).hide();
        $("#open" + d).show();
        a.focus()
    });
    b.on("click", function () {
        var d = $(this).attr("id");
        $("#open" + d).hide();
        c.show()
    });
    $(".replyMainButton").click(function () {
        var e = $(this).attr("id");
        var f = $("#textboxcontent" + e).val();
        var d = "textcontent=" + f + "&reply_msgid=" + e;
        if (f === "") {
            a.stop().css("background-color", "#FFFF9C")
        } else {
            $.ajax({
                type: "POST",
                url: "../../reply",
                data: d,
                success: function (h) {
                    var transform = [
                        {"tag": "hr", "html": ""},
                        {
                            "tag": "div", "class": "media", "children": [
                            {
                                "tag": "a", "class": "pull-left", "href": "${profile_link}", "children": [
                                {"tag": "img", "class": "media-object img-circle", "src": "${profile_avatar}", "alt": "${fullname}", "html": ""}
                            ]
                            },
                            {
                                "tag": "div", "class": "media-body", "children": [
                                {
                                    "tag": "h4", "class": "media-heading", "children": [
                                    {"tag": "a", "href": "${profile_link}", "html": "${fullname}"},
                                    {
                                        "tag": "span", "class": "pull-right", "children": [
                                        {"tag": "i", "class": "comment-time fa fa-clock-o fa-fw", "html": ""},
                                        {"tag": "abbr", "class": "timeago comment-time", "title": "${time}", "html": "${time}"}
                                    ]
                                    }
                                ]
                                },
                                {"tag": "p", "html": "${reply}"}
                            ]
                            }
                        ]
                        }
                    ];
                    var data = [h];
                    $(".reply-add-" + e).json2html(data, transform);
                    $("#openbox-" + e).hide(300);
                }
            })
        }
        return false
    })
}

function voteComment() {
    $(".vote-comment").on("click", function () {
        var c = $(this);
        var data = c.attr("data-id");
        var b = "id=" + data;
        var txt = $("#data-comment-" + data);
        $.ajax({
            type: "POST",
            url: "../../votecomment",
            data: b,
            success: function (a) {
                $.when(c.fadeOut()).done(function () {
                    if (c.hasClass("comment-voted")) {
                        c.removeClass("comment-voted");
                    } else {
                        c.removeClass('vote-comment').addClass('comment-voted');
                    }
                    c.fadeIn();
                    txt.text(a);
                })
            }
        });
        return false
    })
}

function voteReply() {
    $(".vote-reply").on("click", function () {
        var c = $(this);
        var data = c.attr("data-id");
        var b = "id=" + data;
        var txt = $("#data-reply-" + data);
        $.ajax({
            type: "POST",
            url: "../../votereply",
            data: b,
            success: function (a) {
                $.when(c.fadeOut()).done(function () {
                    if (c.hasClass("comment-voted")) {
                        c.removeClass("comment-voted");
                    } else {
                        c.removeClass('vote-comment').addClass('comment-voted');
                    }
                    c.fadeIn();
                    txt.text(a);
                })
            }
        });
        return false
    })
}


function followUnFollow() {
    $(".follow").on("click", function () {
        var c = $(this);
        var b = "id=" + c.attr("id");
        $.ajax({
            type: "POST", url: "../../follow", data: b, success: function (a) {
                $.when(c.fadeOut(300).promise()).done(function () {
                    if (c.hasClass("btn")) {
                        c.removeClass("btn-default").addClass("btn-success").text(a).fadeIn()
                    } else {
                        c.replaceWith('<span class="notice_mid_link">' + a + "</span>")
                    }
                })
            }
        });
        return false
    })
}

function deleteComment() {
    var a = $("button.delete-comment");
    a.on("click", function () {
        var c = $(this);
        var e = c.attr("data-content");
        var b = "id=" + e;
        $.ajax({
            type: "POST", url: "../../deletecomment", data: b, success: function (d) {
                $("#comment-" + e).hide(500)
            }
        })
    })
}

function deleteReply() {
    var a = $("button.delete-reply");
    a.on("click", function () {
        var c = $(this);
        var e = c.attr("data-content");
        var b = "id=" + e;
        $.ajax({
            type: "POST", url: "../../deletereply", data: b, success: function (d) {
                $("#reply-" + e).hide(500)
            }
        })
    })
}

function favorite() {
    $(".favoritebtn").on("click", function () {
        var c = $(this);
        var b = "id=" + c.attr("id");
        $.ajax({
            type: "POST", url: "../../favorite", data: b, success: function (a) {
                $.when(c.fadeOut(300).promise()).done(function () {
                    if (c.hasClass("btn")) {
                        c.removeClass("btn-default").addClass("btn-success").text(a).fadeIn()
                    } else {
                        c.replaceWith('<span class="notice_mid_link">' + a + "</span>")
                    }
                })
            }
        });
        return false
    })
}


function keyboardNavigation() {
    $(document).keydown(function (e) {
        if (e.keyCode == 37) {
            if ($("div.controlArrow a.fa-chevron-left:first-child").attr("href")) {
                window.location = $("div.controlArrow a.fa-chevron-left:first-child").attr("href");
            }
        }
        else if (e.keyCode == 39) {
            if ($("div.controlArrow a.fa-chevron-right:first-child").attr("href")) {
                window.location = $("div.controlArrow a.fa-chevron-right:first-child").attr("href");
            }
        }
    });
}


function run_pinmarklet() {
    var e = document.createElement('script');
    e.setAttribute('type', 'text/javascript');
    e.setAttribute('charset', 'UTF-8');
    e.setAttribute('src', 'http://assets.pinterest.com/js/pinmarklet.js?r=' + Math.random() * 99999999);
    document.body.appendChild(e);
}