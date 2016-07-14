function _init() {
    $.AdminLTE.layout = {
        activate: function() {
            var t = this;
            t.fix(), t.fixSidebar(), $(window, ".wrapper").resize(function() {
                t.fix(), t.fixSidebar()
            })
        },
        fix: function() {
            var t = $(".main-header").outerHeight() + $(".main-footer").outerHeight(),
                e = $(window).height(),
                i = $(".sidebar").height();
            if ($("body").hasClass("fixed")) $(".content-wrapper, .right-side").css("min-height", e - $(".main-footer").outerHeight());
            else {
                var n;
                e >= i ? ($(".content-wrapper, .right-side").css("min-height", e - t), n = e - t) : ($(".content-wrapper, .right-side").css("min-height", i), n = i);
                var s = $($.AdminLTE.options.controlSidebarOptions.selector);
                "undefined" != typeof s && s.height() > n && $(".content-wrapper, .right-side").css("min-height", s.height())
            }
        },
        fixSidebar: function() {
            return $("body").hasClass("fixed") ? ("undefined" == typeof $.fn.slimScroll && console && console.error("Error: the fixed layout requires the slimscroll plugin!"), void($.AdminLTE.options.sidebarSlimScroll && "undefined" != typeof $.fn.slimScroll && ($(".sidebar").slimScroll({
                destroy: !0
            }).height("auto"), $(".sidebar").slimscroll({
                height: $(window).height() - $(".main-header").height() + "px",
                color: "rgba(0,0,0,0.2)",
                size: "3px"
            })))) : void("undefined" != typeof $.fn.slimScroll && $(".sidebar").slimScroll({
                destroy: !0
            }).height("auto"))
        }
    }, $.AdminLTE.pushMenu = {
        activate: function(t) {
            var e = $.AdminLTE.options.screenSizes;
            $(t).on("click", function(t) {
                t.preventDefault(), $(window).width() > e.sm - 1 ? $("body").hasClass("sidebar-collapse") ? $("body").removeClass("sidebar-collapse").trigger("expanded.pushMenu") : $("body").addClass("sidebar-collapse").trigger("collapsed.pushMenu") : $("body").hasClass("sidebar-open") ? $("body").removeClass("sidebar-open").removeClass("sidebar-collapse").trigger("collapsed.pushMenu") : $("body").addClass("sidebar-open").trigger("expanded.pushMenu")
            }), $(".content-wrapper").click(function() {
                $(window).width() <= e.sm - 1 && $("body").hasClass("sidebar-open") && $("body").removeClass("sidebar-open")
            }), ($.AdminLTE.options.sidebarExpandOnHover || $("body").hasClass("fixed") && $("body").hasClass("sidebar-mini")) && this.expandOnHover()
        },
        expandOnHover: function() {
            var t = this,
                e = $.AdminLTE.options.screenSizes.sm - 1;
            $(".main-sidebar").hover(function() {
                $("body").hasClass("sidebar-mini") && $("body").hasClass("sidebar-collapse") && $(window).width() > e && t.expand()
            }, function() {
                $("body").hasClass("sidebar-mini") && $("body").hasClass("sidebar-expanded-on-hover") && $(window).width() > e && t.collapse()
            })
        },
        expand: function() {
            $("body").removeClass("sidebar-collapse").addClass("sidebar-expanded-on-hover")
        },
        collapse: function() {
            $("body").hasClass("sidebar-expanded-on-hover") && $("body").removeClass("sidebar-expanded-on-hover").addClass("sidebar-collapse")
        }
    }, $.AdminLTE.tree = function(t) {
        var e = this,
            i = $.AdminLTE.options.animationSpeed;
        $("li a", $(t)).on("click", function(t) {
            var n = $(this),
                s = n.next();
            if (s.is(".treeview-menu") && s.is(":visible")) s.slideUp(i, function() {
                s.removeClass("menu-open")
            }), s.parent("li").removeClass("active");
            else if (s.is(".treeview-menu") && !s.is(":visible")) {
                var r = n.parents("ul").first(),
                    o = r.find("ul:visible").slideUp(i);
                o.removeClass("menu-open");
                var a = n.parent("li");
                s.slideDown(i, function() {
                    s.addClass("menu-open"), r.find("li.active").removeClass("active"), a.addClass("active"), e.layout.fix()
                })
            }
            s.is(".treeview-menu") && t.preventDefault()
        })
    }, $.AdminLTE.controlSidebar = {
        activate: function() {
            var t = this,
                e = $.AdminLTE.options.controlSidebarOptions,
                i = $(e.selector),
                n = $(e.toggleBtnSelector);
            n.on("click", function(n) {
                n.preventDefault(), i.hasClass("control-sidebar-open") || $("body").hasClass("control-sidebar-open") ? t.close(i, e.slide) : t.open(i, e.slide)
            });
            var s = $(".control-sidebar-bg");
            t._fix(s), $("body").hasClass("fixed") ? t._fixForFixed(i) : $(".content-wrapper, .right-side").height() < i.height() && t._fixForContent(i)
        },
        open: function(t, e) {
            e ? t.addClass("control-sidebar-open") : $("body").addClass("control-sidebar-open")
        },
        close: function(t, e) {
            e ? t.removeClass("control-sidebar-open") : $("body").removeClass("control-sidebar-open")
        },
        _fix: function(t) {
            var e = this;
            $("body").hasClass("layout-boxed") ? (t.css("position", "absolute"), t.height($(".wrapper").height()), $(window).resize(function() {
                e._fix(t)
            })) : t.css({
                position: "fixed",
                height: "auto"
            })
        },
        _fixForFixed: function(t) {
            t.css({
                position: "fixed",
                "max-height": "100%",
                overflow: "auto",
                "padding-bottom": "50px"
            })
        },
        _fixForContent: function(t) {
            $(".content-wrapper, .right-side").css("min-height", t.height())
        }
    }, $.AdminLTE.boxWidget = {
        selectors: $.AdminLTE.options.boxWidgetOptions.boxWidgetSelectors,
        icons: $.AdminLTE.options.boxWidgetOptions.boxWidgetIcons,
        animationSpeed: $.AdminLTE.options.animationSpeed,
        activate: function(t) {
            var e = this;
            t || (t = document), $(t).find(e.selectors.collapse).on("click", function(t) {
                t.preventDefault(), e.collapse($(this))
            }), $(t).find(e.selectors.remove).on("click", function(t) {
                t.preventDefault(), e.remove($(this))
            })
        },
        collapse: function(t) {
            var e = this,
                i = t.parents(".box").first(),
                n = i.find("> .box-body, > .box-footer, > form  >.box-body, > form > .box-footer");
            i.hasClass("collapsed-box") ? (t.children(":first").removeClass(e.icons.open).addClass(e.icons.collapse), n.slideDown(e.animationSpeed, function() {
                i.removeClass("collapsed-box")
            })) : (t.children(":first").removeClass(e.icons.collapse).addClass(e.icons.open), n.slideUp(e.animationSpeed, function() {
                i.addClass("collapsed-box")
            }))
        },
        remove: function(t) {
            var e = t.parents(".box").first();
            e.slideUp(this.animationSpeed)
        }
    }
}

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



    $(function(t) {
        t.fn.boxRefresh = function(e) {
            function i(t) {
                t.append(r), s.onLoadStart.call(t)
            }

            function n(t) {
                t.find(r).remove(), s.onLoadDone.call(t)
            }
            var s = t.extend({
                    trigger: ".refresh-btn",
                    source: "",
                    onLoadStart: function(t) {},
                    onLoadDone: function(t) {}
                }, e),
                r = t('<div class="overlay"><div class="fa fa-refresh fa-spin"></div></div>');
            return this.each(function() {
                if ("" === s.source) return void(console && console.log("Please specify a source first - boxRefresh()"));
                var e = t(this),
                    r = e.find(s.trigger).first();
                r.on("click", function(t) {
                    t.preventDefault(), i(e), e.find(".box-body").load(s.source, function() {
                        n(e)
                    })
                })
            })
        }
    }),
    function(t) {
        t.fn.activateBox = function() {
            t.AdminLTE.boxWidget.activate(this)
        }
    }(jQuery),
    function(t) {
        t.fn.todolist = function(e) {
            var i = t.extend({
                onCheck: function(t) {},
                onUncheck: function(t) {}
            }, e);
            return this.each(function() {
                "undefined" != typeof t.fn.iCheck ? (t("input", this).on("ifChecked", function(e) {
                    var n = t(this).parents("li").first();
                    n.toggleClass("done"), i.onCheck.call(n)
                }), t("input", this).on("ifUnchecked", function(e) {
                    var n = t(this).parents("li").first();
                    n.toggleClass("done"), i.onUncheck.call(n)
                })) : t("input", this).on("change", function(e) {
                    var n = t(this).parents("li").first();
                    n.toggleClass("done"), i.onCheck.call(n)
                })
            })
        }
    }(jQuery), $(function() {
        clearImageCache(), $("div.flash_message").not(".flash_important").delay(2e3).slideUp()
    });