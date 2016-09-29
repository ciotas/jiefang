/*TMODJS:{}*/
!function() {
    function template(filename, content) {
        return (/string|function/.test(typeof content) ? compile : renderFile)(filename, content);
    }
    function toString(value, type) {
        return "string" != typeof value && (type = typeof value, "number" === type ? value += "" : value = "function" === type ? toString(value.call(value)) : ""), 
        value;
    }
    function escapeFn(s) {
        return escapeMap[s];
    }
    function escapeHTML(content) {
        return toString(content).replace(/&(?![\w#]+;)|[<>"']/g, escapeFn);
    }
    function each(data, callback) {
        if (isArray(data)) for (var i = 0, len = data.length; len > i; i++) callback.call(data, data[i], i, data); else for (i in data) callback.call(data, data[i], i);
    }
    function resolve(from, to) {
        var DOUBLE_DOT_RE = /(\/)[^\/]+\1\.\.\1/, dirname = ("./" + from).replace(/[^\/]+$/, ""), filename = dirname + to;
        for (filename = filename.replace(/\/\.\//g, "/"); filename.match(DOUBLE_DOT_RE); ) filename = filename.replace(DOUBLE_DOT_RE, "/");
        return filename;
    }
    function renderFile(filename, data) {
        var fn = template.get(filename) || showDebugInfo({
            filename: filename,
            name: "Render Error",
            message: "Template not found"
        });
        return data ? fn(data) : fn;
    }
    function compile(filename, fn) {
        if ("string" == typeof fn) {
            var string = fn;
            fn = function() {
                return new String(string);
            };
        }
        var render = cache[filename] = function(data) {
            try {
                return new fn(data, filename) + "";
            } catch (e) {
                return showDebugInfo(e)();
            }
        };
        return render.prototype = fn.prototype = utils, render.toString = function() {
            return fn + "";
        }, render;
    }
    function showDebugInfo(e) {
        var type = "{Template Error}", message = e.stack || "";
        if (message) message = message.split("\n").slice(0, 2).join("\n"); else for (var name in e) message += "<" + name + ">\n" + e[name] + "\n\n";
        return function() {
            return "object" == typeof console && console.error(type + "\n\n" + message), type;
        };
    }
    var cache = template.cache = {}, String = this.String, escapeMap = {
        "<": "&#60;",
        ">": "&#62;",
        '"': "&#34;",
        "'": "&#39;",
        "&": "&#38;"
    }, isArray = Array.isArray || function(obj) {
        return "[object Array]" === {}.toString.call(obj);
    }, utils = template.utils = {
        $helpers: {},
        $include: function(filename, data, from) {
            return filename = resolve(from, filename), renderFile(filename, data);
        },
        $string: toString,
        $escape: escapeHTML,
        $each: each
    }, helpers = template.helpers = utils.$helpers;
    template.get = function(filename) {
        return cache[filename.replace(/^\.\//, "")];
    }, template.helper = function(name, helper) {
        helpers[name] = helper;
    }, "function" == typeof define ? define(function() {
        return template;
    }) : "undefined" != typeof exports ? module.exports = template : this.template = template, 
    template.helper("disposeOrderNum", function(num) {
        var result = "";
        return num && (num += "", result = 32 == num.length ? num.substring(8, 24) : num), 
        result.toUpperCase();
    }), template.helper("disposeTime", function(date, format) {
        date = date ? 1e3 * parseInt(date, 10) : Date.now(), date = new Date(date);
        var year = date.getFullYear(), month = date.getMonth() + 1, cday = date.getDate(), hour = date.getHours(), minute = date.getMinutes();
        month = month > 9 ? month : "0" + month, cday = cday > 9 ? cday : "0" + cday, hour = hour > 9 ? hour : "0" + hour, 
        minute = minute > 9 ? minute : "0" + minute;
        var result;
        return format ? "full" == format ? result = [ year, month, cday ].join("-") + " " + [ hour, minute ].join(":") : "date" == format ? result = [ year, month, cday ].join("-") : "month" == format && (result = month + "月" + cday + "日") : result = month + "月" + cday + "日" + " " + hour + ":" + minute, 
        result;
    });
}();
/*TMODJS:{"version":1,"md5":"93f08a90e066b73a555fd806925c468e"}*/
template("menuleft", function($data) {
    "use strict";
    for (var $utils = this, i = ($utils.$helpers, $data.i), len = $data.len, menu = $data.menu, $escape = $utils.$escape, $out = "", i = 0, len = menu.records.length; len > i; i++) $out += " ", 
    null != menu.records[i].dishList && menu.records[i].dishList.length > 0 && ($out += ' <li id="MenuLeft', 
    $out += $escape(menu.records[i].dishTypeID), $out += '" class="menu-left-list ', 
    0 == i && ($out += " active"), $out += '" data-id="', $out += $escape(menu.records[i].dishTypeID), 
    $out += '" data-index="', $out += $escape(menu.records[i].SortNumber), $out += '" data-foods-number="0">', 
    $out += $escape(menu.records[i].typeName), $out += '<span class="menu-count"></span></li> '), 
    $out += " ";
    return new String($out);
});
/*TMODJS:{"version":1,"md5":"5354e1e81e745feb79252b6be01b5982"}*/
template("menuright", function($data) {
    "use strict";
    for (var $utils = this, i = ($utils.$helpers, $data.i), len = $data.len, menu = $data.menu, $escape = $utils.$escape, j = $data.j, $out = "", i = 0, len = menu.records.length; len > i; i++) {
        if ($out += " ", null != menu.records[i].dishList && menu.records[i].dishList.length > 0) {
            $out += ' <dl class="food-category" data-id="', $out += $escape(menu.records[i].dishTypeID), 
            $out += '"> <dt class="food-category-name" data-id="', $out += $escape(menu.records[i].dishTypeID), 
            $out += '" data-index="', $out += $escape(menu.records[i].SortNumber), $out += '">', 
            $out += $escape(menu.records[i].typeName), $out += "</dt> ";
            for (var j = 0; j < menu.records[i].dishList.length; j++) $out += ' <dd id="food-item-', 
            $out += $escape(menu.records[i].dishList[j].dishID), $out += '" class="food-item food-item-', 
            $out += $escape(menu.records[i].dishList[j].dishID), $out += '" data-count="0" data-ID="', 
            $out += $escape(menu.records[i].dishTypeID), $out += '" data-foodID="', $out += $escape(menu.records[i].dishList[j].dishID), 
            $out += '" data-price="', $out += $escape(menu.records[i].dishList[j].price), $out += '"> ', 
            menu.records[i].dishList[j].defaultImageUrl ? ($out += ' <div class="food-img" osrc="http://7xoxig.com1.z0.glb.clouddn.com/', 
            $out += $escape(menu.records[i].dishList[j].defaultImageUrl.split("/")[4]), $out += '?imageView2/3/w/80" osrc1="http://7xoxig.com1.z0.glb.clouddn.com/nil.jpg" style="height:80px; "></div> ') : $out += ' <div class="food-img" osrc="http://7xoxig.com1.z0.glb.clouddn.com/nil.jpg" style="height:80px; "></div> ', 
            $out += ' <div class="food-info ofh"> <h5 class="food-name">', $out += $escape(menu.records[i].dishList[j].dishName), 
            $out += '</h5> <ul class="food-units"> <li class="food-unit"> <p class="price-wrap"> <span class="c-ee4">￥</span> <span class="f-price-z c-ee4">', 
            $out += $escape(menu.records[i].dishList[j].price), $out += '</span> <span class="unit">/', 
            $out += $escape(menu.records[i].dishList[j].measureUnitName), $out += '</span> </p> <div class="count count-', 
            $out += $escape(menu.records[i].dishList[j].dishID), $out += '"> <a class="minus click minus-', 
            $out += $escape(menu.records[i].dishList[j].dishID), $out += '" data-itemid="', 
            $out += $escape(menu.records[i].dishList[j].dishID), $out += '"></a> <span class="ipt-no-app sum food-', 
            $out += $escape(menu.records[i].dishList[j].dishID), $out += '" data-itemid="', 
            $out += $escape(menu.records[i].dishList[j].dishID), $out += '">0</span> <a class="plus click plus-', 
            $out += $escape(menu.records[i].dishList[j].dishID), $out += '" data-itemid="', 
            $out += $escape(menu.records[i].dishList[j].dishID), $out += '"></a> </div> </li> </ul> </div> </dd> ';
            $out += " </dl> ";
        }
        $out += " ";
    }
    return new String($out);
});
(function (window, $) {
    var Template = window.template;
    var app = window.app || {};
    var Utils = app.Utils;
    var self = app;
    var cartData = {}, menuData = {};
    var winH = $(window).height();
    var $body = $('body');
    self.shopID = $.cookie('shopID');
    self.tableID = $.cookie('tableID');
    self.tableName = $.cookie('tableName');
    self.deliveryMode = $.cookie('deliveryMode');
    self.shopName = $.cookie('shopName');
    self.$el = $('#J_menuel');

    //self.Storage();
    self.load = function () {
        self.$el = $('#J_menuel');
        var $el = self.$el;
        var $page = $el.find('.page-content');
        cartData = self.getCartStorage();
        var menuKey = "menu_" + self.shopID;
        var currentMenuVersion = $.cookie('menuVersion');
        var menu = $.Storage.getStorage(menuKey, 'local');
        var tranMenuData = function (arr) {
            var result = [];
            for (var i = 0, len = arr.length; i < len; i++) {
                var item = arr[i].dishList;
                for (var j = 0; j < item.length; j++) {
                    result.push(item[j]);
                }
            }
            return result;
        }

        if (menu && menu.menuVersion === currentMenuVersion) {
            menuData = tranMenuData(menu.records);
            self.render($page, menu);
            return;
        }
        $.getJSON(Utils.rootUrl + '/api/dish/getShopDish?shopID=' + self.shopID + '&sid=' + app.sessionID + '&timestamp=' + Math.random() + '&callback=?', function (remoteData) {
            if (remoteData.code == 1) {
                var records = remoteData.data;
                menuData = tranMenuData(records);
                var menu = {};
                menu.records = records || [];
                menu.title = self.tableName;
                menu.shopID = self.shopID;
                menu.shopName = self.shopName;
                menu.menuVersion = currentMenuVersion;
                self.render($page, menu);
                var m = {};
                m[menuKey] = menu;

                $.Storage.setStorage({
                    type: 'local',
                    data: m
                });
                //如果菜单有改变,购物要清空,防止数据不正确.
                self.changeCartStorage(0, 0, []);
            }
            else {
                Utils.showTip(remoteData.message);
            }
        });
    };
    self.renderCompleted = function () {
        /*if (categoryID) {
         var cnode = self.$menuContent.find(".food-category[data-id='" +categoryID + "']")[0];
         if (cnode) {
         cnode.scrollIntoView()
         }
         }*/
        var orderRecords = cartData.detailList;

        self.changeCartStorage(0, 0, []);

        if (!orderRecords || !orderRecords.length) {
            return
        }
        for (var i = 0, orderFood; orderFood = orderRecords[i]; i++) {
            var $plusBtn = self.$foodCategory.find(".plus-" + orderFood.dishID);
            if (!$plusBtn[0]) {
                continue
            }
            var $food = $plusBtn.closest(".food-item");
            var num = orderFood.dishNumber;
            var val = orderFood.price * num;
            var food = self.getFood('dishID', orderFood.dishID);
            self.changeFoodCount(num, food, $food);
            self.changeListMark(num, food);
            self.changePriceTotal(val);
            $.extend(food, orderFood)
        }
        self.changeCartStorage(self.total, self.totalPrice);
        var orderFoodIDlast = orderRecords[orderRecords.length - 1].dishID;
        var pTop = self.$menuContent.find(".food-item-" + orderFoodIDlast).position().top;
        self.$foodListWrap.scrollTop(pTop - 36);
    }
    /**
     * 渲染界面
     * @param $container
     * @param data
     */
    self.render = function ($container, data) {
        var $el = self.$el.off('.ejoy');
        $el.find('#shopName_hd').text(self.shopName);
        $el.find('.menu-left-ul').append(template('menuleft', {menu: data}));
        $el.find('.menu-content').append(template('menuright', {menu: data}));
        /* $el.find('#container').append("1");*/
        self.$menu = $el.find('.menu');
        self.$main = $el.find(".main")
        self.$mainLeft = self.$main.find('.main-left');
        self.$mainRight = self.$main.find('.main-right');
        self.$foodCategory = self.$mainRight.find('.food-category');
        self.$activeMenu = self.$mainRight.find('.active-menu');
        self.$foodListWrap = self.$mainRight.find('.food-list-wrap');
        self.$menuContent = self.$mainRight.find('.menu-content');
        self.$menuCategory = self.$mainLeft.find(".menu-left-list");
        self.$payButton = self.$main.find('.payButton');
        self.$mainBottom = self.$main.find('.main-bottom');
        self.$totalPrice = self.$main.find('.main .totalPrice');
        self.$total = self.$main.find('.main .total');
        self.foodImgs = self.$menuContent.find(".food-item .food-img").toArray();
        self.loadImage();
        self.total = 0;
        self.totalPrice = 0;
        self._setCategoryName(0);
        self._addEvent();

        //var rightScroll = new IScroll(".main-right", {click: false, probeType: 1});
        //var leftScroll = new IScroll(".main-left", {click: false, probeType: 1});
        self.renderCompleted();
    };
    self.getImage = function ($img, i) {
        var img = new Image();
        var src = $img.attr('osrc');
        if (src) {
            img.src = src;
            img.onload = function () {
                $img.css('background-image', 'url(' + src + ')').addClass('loaded');
            }
        }
    };
    self.loadImage = function () {
        var foodImgs = self.foodImgs;
        if (!foodImgs.length) {
            return
        }
        var $win = $(window);
        var winTop = $win.scrollTop();
        var topVal = winTop;
        var bottom = winTop + $win.height();
        for (var i = 0, img; img = foodImgs[i]; i++) {
            var $img = $(img);
            if ($img.is(".loaded")) {
                continue
            }
            var oTop = $img.offset().top;
            if (oTop >= topVal && oTop <= bottom) {
                self.getImage($img, i);
                foodImgs.splice(i--, 1)
            }
        }
    };
    /**
     * 设置菜单目录文字
     * @param idx
     * @private
     */
    self._setCategoryName = function (idx) {
        self.$activeMenu.text(self.$foodCategory.eq(idx).find('.food-category-name').text());
    };
    self.getFood = function (key, val) {

        var cartItem = self.getCartFood(key, val);
        if (!cartItem) {
            var food = self.getFoodData(key, val);
            cartItem = {
                dishNumber: food.dishNumber,
                dishName: food.dishName,
                dishID: food.dishID,
                finalPrice: food.price,
                dishTypeID: food.dishTypeID,
                typeName: food.dishTypeName,
                price: food.price
            };
        }
        return cartItem;
    };
    self.getCartFood = function (key, val) {
        var cart = null;
        for (var i = 0, len = cartData.detailList.length; i < len; i++) {
            if (cartData.detailList[i][key] == val) {
                cart = cartData.detailList[i];
                break;
            }
        }
        return cart;
    };
    self.getFoodData = function (key, val) {
        var food = null;
        for (var i = 0, len = menuData.length; i < len; i++) {
            if (menuData[i][key] == val) {
                food = menuData[i];
                break;
            }
        }
        if (!food) return;
        food = $.extend({}, food);
        food.dishNumber = 0;
        return food;
    };

    /**
     * 计算购物相关数值
     * @param $target
     * @param e
     * @returns {*}
     * @private
     */
    self.changeCartFood = function ($target, e) {
        var $food = $target.closest('.food-info');
        var itemID = $target.data('itemid');
        var food = self.getFood('dishID', itemID);
        var dishNumber = food.dishNumber || 0;
        food.dishNumber = dishNumber;
        var count = +dishNumber;
        var minCount = +1;
        var inputCount = parseFloat($target.text());
        inputCount = parseFloat((isNaN(inputCount) ? count : inputCount).toFixed(1));
        inputCount = inputCount < minCount ? minCount : inputCount;
        var num = $target.is(".sum") ? inputCount - count : $target.is(".plus") ? (count < minCount ? minCount - count : 1) : -(count > minCount ? 1 : count);
        var newCount = +dishNumber + num;
        num = newCount < 0 ? -dishNumber : newCount > 99 ? 99 - dishNumber : num;
        num = parseFloat(num.toFixed(1));
        var val = food.price * num;
        if (num > 0) {
            /* self.renderFly($target)*/
        }
        self.changeFoodCount(num, food, $food);
        self.changeListMark(num, food);
        self.changePriceTotal(val, food);
        self.changeCartStorage(self.total, self.totalPrice);
        return food
    };
    /* self.renderFly = function ($target) {
     var offset = $target.offset();
     var halfWidth = 0.5 * $target.width();
     var $fly = $('<div class="fly"><i></i></div>');
     $fly.on("transitionend webkitTransitionEnd", function (e) {
     $(this).remove();
     });
     $fly.css("transform", "translate3d(" + (offset.left - 30 + halfWidth) + "px, 0, 0)").appendTo($body).find("i").css("transform", "translate3d(0, " + (offset.top - winH + 20) + "px, 0)");
     setTimeout(function () {
     $fly.addClass("in")
     }, 50);
     };*/
    self.changeFoodCount = function (num, food, $food, $foodWrap) {
        //var $foodWrap = $foodWrap;
        var $foodWrap = $(".main-right, .food-container");
        food.dishNumber = parseInt(+food.dishNumber + num);
        var count = food.dishNumber;
        $foodWrap.find('.count-' + food.dishID).toggleClass('active', count > 0);
        $foodWrap.find('.food-' + food.dishID).text(count);
        self.total = parseInt(self.total + num);
        self.$total.text(self.total > 99 ? 'N' : self.total);
        self.$mainBottom.toggleClass('none', self.total <= 0);
        self.$main.toggleClass('main-pb', self.total > 0);
        var cartFood = self.getCartFood('dishID', food.dishID);
        var idx = -1;
        for (var i = 0, len = cartData.detailList.length; i < len; i++) {
            var item = cartData.detailList[i];
            if (item.dishID === food.dishID) {
                idx = i;
                break;
            }
        }
        if (cartFood && idx == -1 && count) {

            $.extend(cartFood, food)
        }
        if (count <= 0 && cartFood) {
            cartData.detailList.splice(idx, 1);
        } else {
            if (count > 0 && !cartFood) {
                cartData.detailList.push(food)
            }
        }

        var isFoodActive = !!self.getCartFood('dishID', food.dishID);
        $food.toggleClass("active", isFoodActive);
        var isCategoryActive = !!self.getCartFood('dishTypeID', food.dishTypeID);
        $food.parent().toggleClass("active", isCategoryActive);
    };
    self.changeListMark = function (num, food) {
        var $list = self.$menuCategory.filter('#MenuLeft' + food.dishTypeID);
        var count = parseInt(+$list.data('foods-number') + num);
        $list.data("foods-number", count).toggleClass("number", count > 0).find(".menu-count").text(count >= 100 ? "N" : count)
    };
    self.changePriceTotal = function (val, food) {
        self.totalPrice = parseFloat(+self.totalPrice + val).toFixed(2);
        self.$totalPrice.text("￥" + self.totalPrice);
        //if (minAmount == 0) {
        //return
        //}
        //var needAmount = math.parseNum(minAmount - self.totalPrice);
        //$payButton.toggleClass("dn", needAmount > 0)
    };
    self.changeCartStorage = function (total, totalPrice, detailList) {
        cartData.total = total;
        cartData.totalPrice = totalPrice;
        if (detailList) {
            cartData.detailList = detailList;
        }
        var data = {};
        data['cart_' + self.shopID] = cartData;
        $.Storage.setStorage({
            data: data,
            type: "session"
        });
    };
    self.getCartStorage = function () {
        return $.Storage.getStorage('cart_' + self.shopID, 'session') || {
                total: 0,
                totalPrice: 0,
                shopID: self.shopID,
                tableID: self.tableID,
                shopName: self.tableName,
                detailList: []
            };
    }

    self.createCart = function createCart() {
        location.href = 'pay.html?paymoney=' + self.totalPrice
    };
    self._addEvent = function () {
        var $el = self.$el,
            $mainLeft = self.$mainLeft,
            $mainRight = self.$mainRight,
            $foodCategory = self.$foodCategory,
            $menuContent = self.$menuContent,
            $activeMenu = self.$activeMenu,
            $menuCategory = self.$menuCategory,
            $foodListWrap = self.$foodListWrap;
        $mainLeft.on("click.ejoy", ".menu-left-list", function () {
            var $me = $(this).addClass("active");
            $me.siblings().removeClass("active");
            $foodCategory.eq($me.index())[0].scrollIntoView()
        });
        $el.on("click.ejoy", ".J_back,.J_btn-OK", function () {
            $(".food-container").remove();
        });
        $el.on("click.ejoy", "#fixnav_add", function () {
            window.location.href = 'historyorder.html'
        });
        $el.on("click", "#J_search", function () {
            $(".J_boxcai").show();
        });
        $el.on("click", ".boxcolse", function () {
            $(".J_boxcai").hide();
        });
        $el.on("click.ejoy", ".food-info .minus", function (e) {
            self.changeCartFood($(this), e);
            self.Storage();
            var obj = self.getCartStorage();
            if (obj.detailList.length < 1) {
                $(".J_cart").toggleClass('none', self.total <= 0);
                $(".cartMask").toggleClass('none', self.total <= 0);
                $(".main-bottom .cart").css({"position": "", "top": ""})
            }
            if ($(".J_cart").hasClass("none") == false) {
                var xx = $(".J_cart").height();
                $(".main-bottom .cart").css({"position": "absolute", "top": -(xx + 45)})
            }
            return false
        });
        $el.on("click.ejoy", ".food-info .plus", function (e) {
            self.search()
            var sess =  localStorage.setItem("timp",1);
            var $tar = $(this);
            var count = +$tar.prev().text();
            var food = self.changeCartFood($tar, e);
            var categoryID = $tar.parents('dd').data('id');
            self.Storage();
            if ($(".J_cart").hasClass("none") == false) {
                var xx = $(".J_cart").height();
                $(".main-bottom .cart").css({"position": "absolute", "top": -(xx + 45)})
            }
            return false
        });
        $el.on("click.ejoy", ".J_search_btn", function () {
            var val = $("#J_searchtext").val();
            self.search(val);
        });

        self.$payButton.on('click.ejoy', function (e) {
            e.preventDefault();
            if (self.total != 0) {
                self.createCart();
            } else {
                Utils.showTip("客官，先点个菜吧");
            }
        });
        self.$mainBottom.find('.cart').on('click.ejoy', function (e) {
            e.preventDefault();
            self.Storage();
            if ($(".J_cart").hasClass("none") == true) {
                $(".J_cart").removeClass("none");
                $(".cartMask").removeClass("none");
                var xx = $(".J_cart").height();
                $("#fixnav").hide();
                $(this).css({"position": "absolute", "top": -(xx + 45)})
            }
            else {
                $(".J_cart").addClass("none");
                $(".cartMask").addClass("none");
                $("#fixnav").show();
                $(this).css({"position": "", "top": ""})
            }
            e.preventDefault();
            if (self.total != 0) {
            } else {
                Utils.showTip("客官，先点个菜吧");
            }
        });
        (function () {
            var max = $foodCategory.length - 1;
            var $foodCategroyLast = $foodCategory.eq(max);
            var height = $mainRight.height();
            var lastHeight = $foodCategroyLast.height();
            var pb = height - lastHeight;
            var scrollMenu = Utils.throttle(function (e) {
                self.loadImage();
                var sTop = $foodListWrap.scrollTop();
                for (var i = 0, $category; $category = $foodCategory.eq(i); i++) {
                    if (!$category || !$category.position()) break;
                    var pTop = $category.position().top;
                    var nTop = i < max ? $category.next().position().top : $menuContent.height();
                    if (sTop >= pTop && sTop < nTop) {
                        $activeMenu.text($category.find(".food-category-name").text());
                        var $activeMemuCategory = $menuCategory.eq(i).addClass("active");
                        $activeMemuCategory.siblings().removeClass("active");
                        var activeMemuCategory = $activeMemuCategory[0];
                        activeMemuCategory[activeMemuCategory.scrollIntoViewIfNeeded ? "scrollIntoViewIfNeeded" : "scrollIntoView"]();
                        break
                    }
                }
            }, 50);
            $foodListWrap.on('scroll', scrollMenu);
        })();
    };

    self.Storage = function () {
        var obj = self.getCartStorage();
        if (obj == null) {
            return;
        }
        var detailList = obj.detailList || [];
        var str = '<ul>';
        for (i = 0; i < detailList.length; i++) {
            str += '<li><div class="cartName">' + obj.detailList[i].dishName + '</div> <div class="cartNum">￥' + obj.detailList[i].finalPrice + '</div> <div class="food-info"><div class="count count-' + obj.detailList[i].dishID + ' active"><a class="minus click minus-' + obj.detailList[i].dishID + '" data-itemid="' + obj.detailList[i].dishID + '"></a><span class="ipt-no-app sum food-' + obj.detailList[i].dishID + '"data-itemid="' + obj.detailList[i].dishID + '">' + obj.detailList[i].dishNumber + '</span><a class="plus click plus-' + obj.detailList[i].dishID + '" data-itemid="' + obj.detailList[i].dishID + '"></a></div></div></li>'
        }
        str += '</ul>'
        $(".J_cartinfo").html("");
        $(".J_cartinfo").append(str);

    }
    self.search = function () {
        var locallength = localStorage.getItem('menu_' + self.shopID);
        if (locallength == null) {
            return;
        }
        var obj = JSON.parse(locallength);
        if (obj == null) {
            return;
        }


    }
    self.load();

})(window, $);