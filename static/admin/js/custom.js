$(function () {
    clearImageCache();
    $('div.flash_message').not('.flash_important').delay(2000).slideUp();
});
function clearImageCache() {
    $('button.clearImageCache').on('click', function () {
        var c = $(this);
        var b = c.data('image');
        $.ajax({
            type: "POST", url: "../../images/clearcache", data: 'id=' + b, success: function (a) {
                $.when(c.fadeOut(300).promise()).done(function () {
                    if (c.hasClass("btn")) {
                        c.text(a).fadeIn();
                    } else {
                        c.replaceWith('<span class="notice_mid_link">' + a + "</span>")
                    }
                })
            }
        });
        return false;
    });
}
function imageApprove() {
    $(".image-approve").on("click", function () {
        var c = $(this);
        var b = c.data('approve');
        $("a[data-disapprove='" + b + "']").toggle();
        $.ajax({
            type: "POST", url: "../../admin/images/approve", data: 'id=' + b + '&approve=' + 1, success: function (a) {
                $.when(c.fadeOut(300).promise()).done(function () {
                    if (c.hasClass("btn")) {
                        c.text(a).fadeIn();
                    } else {
                        c.replaceWith('<span class="notice_mid_link">' + a + "</span>")
                    }
                })
            }
        });
        return false;
    })
}

function imageDisapprove() {
    $(".image-disapprove").on("click", function () {
        var c = $(this);
        var b = c.data('disapprove');
        $("a[data-approve='" + b + "']").toggle();
        $.ajax({
            type: "POST", url: "../../admin/images/approve", data: 'id=' + b + '&approve=' + 0, success: function (a) {
                $.when(c.fadeOut(300).promise()).done(function () {
                    if (c.hasClass("btn")) {
                        c.text(a).fadeIn();
                    } else {
                        c.replaceWith('<span class="notice_mid_link">' + a + "</span>")
                    }
                })
            }
        });
        return false;
    });
}


function userApprove() {
    $(".image-approve").on("click", function () {
        var c = $(this);
        var b = c.data('approve');
        $("a[data-disapprove='" + b + "']").toggle();
        $.ajax({
            type: "POST", url: "../../admin/users/approve", data: 'id=' + b + '&approve=' + 1, success: function (a) {
                $.when(c.fadeOut(300).promise()).done(function () {
                    if (c.hasClass("btn")) {
                        c.text(a).fadeIn();
                    } else {
                        c.replaceWith('<span class="notice_mid_link">' + a + "</span>")
                    }
                })
            }
        });
        return false;
    })
}

function userDisapprove() {
    $(".image-disapprove").on("click", function () {
        var c = $(this);
        var b = c.data('disapprove');
        $("a[data-approve='" + b + "']").toggle();
        $.ajax({
            type: "POST", url: "../../admin/users/approve", data: 'id=' + b + '&approve=' + 0, success: function (a) {
                $.when(c.fadeOut(300).promise()).done(function () {
                    if (c.hasClass("btn")) {
                        c.text(a).fadeIn();
                    } else {
                        c.replaceWith('<span class="notice_mid_link">' + a + "</span>")
                    }
                })
            }
        });
        return false;
    });
}