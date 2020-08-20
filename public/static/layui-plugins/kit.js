"use strict"
layui.define(['layer', 'form'], function (exprots) {
   const form = layui.form
   let kit = {
      successCode: 0,
      // msg弹窗默认消失时间
      msgTime: 1000,
      asyncOff: function () {
         $.ajaxSetup({
            async: false
         });
      },
      reloadMenu: function (father = '') {
         if (typeof parent.tabListObj != 'undefined') {
            parent.tabListObj.render(null, false)
            return true
         } else if (father) {
            if (typeof father.tabListObj != 'undefined') {
               father.tabListObj.render(null, false)
               return true
            }
         } else {
            father = father ? father.parent : parent.parent
            kit.reloadMenu(father)
         }
      },
      dateTime: function (timestamp, format) {
         return timestamp ? layui.util.toDateString(timestamp * 1000, format) : timestamp;
      },
      toDate: function (timestamp) {
         return kit.dateTime(timestamp, 'yyyy-MM-dd')
      },
      toDateTime: function (timestamp) {
         return kit.dateTime(timestamp, 'yyyy-MM-dd HH:mm:ss')
      },
      // 绿色勾
      msg: function (content, options, callback) {
         content !== '' && layer.msg(content, options, callback)
      },
      success: function (content, callback) {
         kit.msg(content !== '' ? content : '操作成功', { icon: 1, time: kit.msgTime }, callback)
      },
      // 红色叉
      error: function (content, callback) {
         kit.msg(content !== '' ? content : '操作失败', { icon: 2, time: kit.msgTime }, callback)
      },
      // 黄色问号
      question: function (content, callback) {
         kit.msg(content ? content : '', { icon: 3, time: kit.msgTime }, callback)
      },
      // 灰色锁
      lock: function (content, callback) {
         kit.msg(content ? content : '', { icon: 4, time: kit.msgTime }, callback)
      },
      // 红色哭脸
      cry: function (content, callback) {
         kit.msg(content ? content : '', { icon: 5, time: kit.msgTime }, callback)
      },
      // 绿色笑脸
      laugh: function (content, callback) {
         kit.msg(content ? content : '', { icon: 6, time: kit.msgTime }, callback)
      },
      // 黄色感叹号
      sign: function (content, callback) {
         layer.msg(content ? content : '', { icon: 7, time: kit.msgTime }, callback)
      },
      closeLayer: function (name) {
         typeof window.submitCallback == 'function' && window.submitCallback()
         parent.layer.close(parent.layer.getFrameIndex(name))
      },
      load : function () {
         return layer.load(2, { shade: 0.2 })
      },
      /**
       * ajax()函数二次封装
       * @param {string} url 地址
       * @param {string} type 类型
       * @param {object} params 参数
       * @param {object} options { load: true, msg: true }
       * @returns {*|never|{always, promise, state, then}}
       */
      ajax: function (url, type, params, options = {}) {
         let deferred = $.Deferred()
         let loadIndex
         options = Object.assign({ load: true, msg: true }, options)
         $.ajax({
            url: url,
            type: type || "get",
            data: params || {},
            dataType: "json",
            beforeSend: function () {
               if (options.load) {
                  loadIndex = kit.load()
               }
            },
            success: function (data) {
               if (data.code === kit.successCode) {
                  options.msg ? kit.success(data.msg, () => { deferred.resolve(data) }) : deferred.resolve(data)
               } else {
                  options.msg ? kit.error(data.msg, () => { deferred.reject(data) }) : deferred.reject(data)
               }
            },
            complete: function () {
               if (options.load) {
                  layer.close(loadIndex)
               }
            },
            error: function () {
               layer.close(loadIndex)
               kit.sign('服务器错误')
               deferred.reject("kit.ajax error: 服务器错误")
            }
         })
         return deferred.promise()
      },
      get: function (url, params, options) {
         return kit.ajax(url, 'get', params, options)
      },
      post: function (url, params, options) {
         return kit.ajax(url, 'post', params, options)
      },
      put: function (url, params, options) {
         return kit.ajax(url, 'put', params, options)
      },
      delete: function (url, params, options) {
         return kit.ajax(url, 'delete', params, options)
      },
      /**
       * confirm()函数二次封装
       * @param content
       * @param yesFunction
      */
      confirm: function (title, url, params, obj = null, callback) {
         layer.confirm(title, { icon: 3, title: '提示' }, function (index) {
            kit.post(url, params).done((res) => {
               obj && obj.del()
               layer.close(index)
               if (typeof callback == 'function') {
                  callback()
               }
            }).fail(() => {
               layer.close(index)
            })
         })
      },
      batchConfirm: function (title, url, params, tableIns, callback) {
         let ids = kit.tableBatchCheck(tableIns)
         if (ids) {
            params.ids = ids
         } else {
            return kit.sign('请选择数据')
         }
         kit.confirm(title, url, params, null, callback)
      },
      tableBatchCheck: function (tableIns) {
         let checkStatus = layui.table.checkStatus(tableIns.config.id)
         if (checkStatus.data.length) {
            let ids = [];
            checkStatus.data.forEach(value => {
               ids.push(value.id)
            })
            return ids.join(',')
         } else {
            return false
         }
      },
      open: function (title = '', url = '404.html', options = {}, submitCallback, pageCallback) {
         options = Object.assign({
            btn: [],
            btnCallback: {},
            width: 0.8,
            height: 0.8,
            shadow: false,
         }, options)
         options.width = options.width > 1 ? options.width : $(window).width() * options.width
         options.height = options.height > 1 ? options.height : $(window).height() * options.height
         options.btn.length === 0 && (options.btn = ['确定', '取消'])
         options.shade === false ? options.shade = false : options.shade = 0.4
         let layerOption = {
            title: title,
            btn: options.btn,
            content: url,
            type: 2,
            maxmin: true,
            shade: options.shadow,
            area: [options.width + 'px', options.height + 'px'],
            zIndex: layer.zIndex,
            fix: false,
            shadeClose: false,
            yes: (index) => {
               let body = layer.getChildFrame('body', index)
               body.contents().find("#submit").click()
            }, btn2: () => {
               layer.closeAll()
            },
            success: function (layero, index) {
               // 页面初始化成功
               var iframeWin = window[layero.find('iframe')[0]['name']]
               typeof iframeWin.initFrame == 'function' && iframeWin.initFrame()
               if (typeof pageCallback == "function") {
                  pageCallback(iframeWin);
               }
               if (typeof submitCallback == "function") {
                  iframeWin.submitCallback = () => {
                     submitCallback()
                  };
               }
            }, error: function (layero, index) {
               // 页面初始化失败
               console.log('Open Error')
            }
         }
         layerOption = Object.assign(layerOption, options.btnCallback);
         layer.open(layerOption)
      },
      edit: function (title = '', url = '404.html', submitCallback, pageCallback) {
         let options = { btn: [], btnCallback: {}, width: 0.8, height: 0.8, shadow: false }
         kit.open(title, url, options, submitCallback, pageCallback)
      },
      show: function (title = '', url = '404.html', submitCallback, pageCallback) {
         let options = { btn: false, shadow: 0.4, width: 0.8, height: 0.8 }
         kit.open(title, url, options, submitCallback, pageCallback)
      },
      // 表单操作
      formSwitch: function (url, params, obj, callback = (res) => { }) {
         kit.post(url, params).done((res) => {
            callback(res)
         }).fail((error) => {
            obj.elem.checked = !obj.elem.checked;
            form.render();
         })
      },
      /**
       * 初始化下拉框
       * @param {object} element
       * @param {string} url
       * @param {object} params
       * @param {object} parseData { value: 'id', title: 'name' }
       * @param {function} callback
       * @param {object} callbackParams
       */
      formSelect: function (element, url, params, parseData = {}, callback, callbackParams) {
         kit.post(url, params, { msg: false }).done((res) => {
            let html = kit.makeOption(res.data, parseData)
            element.insertAdjacentHTML('beforeend', html)
            if (typeof callback == 'function') {
               callback(callbackParams)
            }
            form.render()
         })
      },
      makeOption: function (data, parseData = {}) {
         parseData = Object.assign({ value: 'id', title: 'name' }, parseData)
         let html = ''
         for (let item of Object.values(data)) {
            if (parseData.selected == item[parseData.value]) {
               html += `<option value="${item[parseData.value]}" selected>${item[parseData.title]}</option>`
            } else {
               html += `<option value="${item[parseData.value]}">${item[parseData.title]}</option>`
            }
         }
         return html
      },
      /**
       * 主要用于对ECharts视图自动适应宽度
       * @param {object} EChartsElement
       */
      EChartsResize: function (element) {
         element = element || []
         window.addEventListener("resize", function () {
            for (let i = 0; i < element.length; i++) {
               element[i].resize()
            }
         })
      }
   }
   exprots("kit", kit)
})
