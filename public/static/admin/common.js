window.config = {}
window.config.defaultIcon = 'layui-icon-circle'
window.config.defaultTitle = '默认标题'
window.config.tabMenuName = 'admin-TabMenu'
window.config.powerItemName = 'admin-PowerList'
window.config.localUserKey = 'adminUser'
window.config.layIdName = 'admin-LayId'
window.config.uploadImageUrl = '/admin/file/image'
window.config.loginPage = './auth/login'
window.config.logoutPage = './auth/logout'

if(document.cookie.indexOf('admin_auth') === -1){
   window.location = window.config.loginPage
}

layui.config({
   base: '/static/layui-plugins/' //假设这是你存放拓展模块的根目录
})


function getUrlParam(name) {
   let reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)")
   let r = window.location.search.substr(1).match(reg)
   if (r != null) return decodeURI(r[2]); return null
}
function getUserInfo(field) {
   let info = JSON.parse(localStorage.getItem(window.config.localUserKey))
   if (!info) {
      window.location.href = window.config.loginPage
      return false;
   }
   return info[field] ? info[field] : ''
}
function initPower() {
   let powerItem = document.querySelectorAll('.power-item')
   let powerList = localStorage.getItem(window.config.powerItemName)
   powerList = powerList ? JSON.parse(powerList) : []
   for (let value of Object.values(powerItem)) {
      if (powerList.includes(value.getAttribute('power'))) {
         value.classList.remove('power-item')
      }
   }
}
window.onload = () => {
   initPower()
}
