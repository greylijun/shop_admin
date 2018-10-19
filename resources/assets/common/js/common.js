/**
 * 数字使用千分符
 * @param point 小数点后面保留位，四舍五入
 * @returns {string}
 */
Number.prototype.format = function (point) {
	return (parseFloat(this).toFixed(point || 0) + '').replace(/\d{1,3}(?=(\d{3})+(\.\d*)?$)/g, '$&,');
};

/**
 * 手动触发 window.resize() 事件
 */
window.triggerWindowResize = function () {
	if (document.createEvent) {
		let event = document.createEvent("HTMLEvents");
		event.initEvent("resize", true, true);
		window.dispatchEvent(event);
	} else if (document.createEventObject) {
		window.fireEvent("onresize");
	}
};

/**
 * map对象转为 url 参数格式，例如：a=1&b=2&c=3
 * @param obj
 * @returns {string}
 */
Object.toParams = function (obj) {
	return Object.keys(obj).map(key => `${key}=${encodeURIComponent(obj[key])}`).join('&');
};

/**
 * 获取图片原始大小
 * @param img
 * @returns [宽,高]
 */
window.getImgNaturalDimensions = function (img) {
	let nWidth, nHeight;
	if (img.naturalWidth) { // 现代浏览器
		nWidth = img.naturalWidth;
		nHeight = img.naturalHeight;
	} else { // IE6/7/8
		let image = new Image();
		image.src = img.src;
		image.onload = function () {
			nWidth = image.width;
			nHeight = image.height;
		}
	}
	return [nWidth, nHeight];
};