layui.define(['jquery'], function (exports) {
  var jQuery = layui.jquery;
  !function (menuTarget, newMenu, menuList, menuItem) {
    var menuObject = function (menuTarget, newMenu) {
      this.init(menuTarget, newMenu)
    };
    menuObject.prototype = {
      init: function (menuTarget, newMenu) {
        this.ele = menuTarget, this.defaults = {
          menu: [{
            text: "菜单一", callback: function () {
            }
          }, {
            text: "菜单二", callback: function () {
            }
          }], target: function (menuTarget) {
          }, width: 100, itemHeight: 28, bgColor: "#fff", color: "#333", fontSize: 14, hoverBgColor: "#f5f5f5"
        }, this.opts = menuList.extend(!0, {}, this.defaults, newMenu), this.random = (new Date).getTime() + parseInt(1e3 * Math.random()), this.eventBind()
      }, renderMenu: function () {
        var menuTarget = this, newMenu = "#uiContextMenu_" + this.random;
        if (!(menuList(newMenu).length > 0)) {
          var menuTarget = this, menuItem = '<ul class="ul-context-menu" id="uiContextMenu_' + this.random + '">';
          menuList.each(this.opts.menu, function (menuTarget, newMenu) {
            menuItem += '<li class="ui-context-menu-item"><a href="javascript:void(0);">'
            newMenu.icon && (menuItem += '<icon class="icon ' + newMenu.icon + '"></icon>')
            menuItem += '<span>' + newMenu.text + '</span></a></li>'
          }), menuItem += "</ul>", menuList("body").append(menuItem).find(".ul-context-menu").hide(), this.initStyle(newMenu), menuList(newMenu).on("click", ".ui-context-menu-item", function (newMenu) {
            menuTarget.menuItemClick(menuList(this)), newMenu.stopPropagation()
          })
        }
      }, initStyle: function (menuTarget) {
        var newMenu = this.opts;
        menuList(menuTarget).css({ width: newMenu.width, backgroundColor: newMenu.bgColor }).find(".ui-context-menu-item a").css({
          color: newMenu.color,
          fontSize: newMenu.fontSize,
          height: newMenu.itemHeight,
          lineHeight: newMenu.itemHeight + "px"
        }).hover(function () {
          menuList(this).css({ backgroundColor: newMenu.hoverBgColor })
        }, function () {
          menuList(this).css({ backgroundColor: newMenu.bgColor })
        })
      }, menuItemClick: function (menuTarget) {
        var newMenu = this, menuList = menuTarget.index();
        menuTarget.parent(".ul-context-menu").hide(), newMenu.opts.menu[menuList].callback && "function" == typeof newMenu.opts.menu[menuList].callback && newMenu.opts.menu[menuList].callback()
      }, setPosition: function (menuTarget) {
        menuList("#uiContextMenu_" + this.random).css({ left: menuTarget.clientX + 2, top: menuTarget.clientY + 2 }).show()
      }, eventBind: function () {
        var menuTarget = this;
        this.ele.on("contextmenu", function (newMenu) {
          newMenu.preventDefault(), menuTarget.renderMenu(), menuTarget.setPosition(newMenu), menuTarget.opts.target && "function" == typeof menuTarget.opts.target && menuTarget.opts.target(menuList(this))
        }), menuList(newMenu).on("click", function () {
          menuList(".ul-context-menu").hide()
        })
      }
    }, menuList.fn.contextMenu = function (menuTarget) {
      return new menuObject(this, menuTarget), this
    }
  }(window, document, jQuery);
  exports('contextMenu', function (obj) {

  });
});

