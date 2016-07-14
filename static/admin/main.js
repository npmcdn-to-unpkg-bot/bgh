
function clearImageCache() {
    $("button.clearImageCache").on("click", function() {
        var t = $(this),
            e = t.data("image");
        return $.ajax({
            type: "POST",
            url: "../../images/clearcache",
            data: "id=" + e,
            success: function(e) {
                $.when(t.fadeOut(300).promise()).done(function() {
                    t.hasClass("btn") ? t.text(e).fadeIn() : t.replaceWith('<span class="notice_mid_link">' + e + "</span>")
                })
            }
        }), !1
    })
}

function imageApprove() {
    $(".image-approve").on("click", function() {
        var t = $(this),
            e = t.data("approve");
        return $("a[data-disapprove='" + e + "']").toggle(), $.ajax({
            type: "POST",
            url: "../../admin/images/approve",
            data: "id=" + e + "&approve=1",
            success: function(e) {
                $.when(t.fadeOut(300).promise()).done(function() {
                    t.hasClass("btn") ? t.text(e).fadeIn() : t.replaceWith('<span class="notice_mid_link">' + e + "</span>")
                })
            }
        }), !1
    })
}

function imageDisapprove() {
    $(".image-disapprove").on("click", function() {
        var t = $(this),
            e = t.data("disapprove");
        return $("a[data-approve='" + e + "']").toggle(), $.ajax({
            type: "POST",
            url: "../../admin/images/approve",
            data: "id=" + e + "&approve=0",
            success: function(e) {
                $.when(t.fadeOut(300).promise()).done(function() {
                    t.hasClass("btn") ? t.text(e).fadeIn() : t.replaceWith('<span class="notice_mid_link">' + e + "</span>")
                })
            }
        }), !1
    })
}

function userApprove() {
    $(".image-approve").on("click", function() {
        var t = $(this),
            e = t.data("approve");
        return $("a[data-disapprove='" + e + "']").toggle(), $.ajax({
            type: "POST",
            url: "../../admin/users/approve",
            data: "id=" + e + "&approve=1",
            success: function(e) {
                $.when(t.fadeOut(300).promise()).done(function() {
                    t.hasClass("btn") ? t.text(e).fadeIn() : t.replaceWith('<span class="notice_mid_link">' + e + "</span>")
                })
            }
        }), !1
    })
}

function userDisapprove() {
    $(".image-disapprove").on("click", function() {
        var t = $(this),
            e = t.data("disapprove");
        return $("a[data-approve='" + e + "']").toggle(), $.ajax({
            type: "POST",
            url: "../../admin/users/approve",
            data: "id=" + e + "&approve=0",
            success: function(e) {
                $.when(t.fadeOut(300).promise()).done(function() {
                    t.hasClass("btn") ? t.text(e).fadeIn() : t.replaceWith('<span class="notice_mid_link">' + e + "</span>")
                })
            }
        }), !1
    })
}
