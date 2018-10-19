export default function (error) {
	let vnode = this.$createElement;
	let msgs = [];

	if (error.response.status === 422) {
		for (let index in error.response.data) {
			msgs.push(vnode('p', error.response.data[index]));
		}
	} else if (error.response.status === 429) {
		msgs.push(vnode('p', '请求次数太多，请1分钟后再试'));
	} else {
		msgs.push(vnode('p', error.response.data.message || '未知错误'));
	}
	return vnode('div', msgs);
}