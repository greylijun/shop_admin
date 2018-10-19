import axios from 'axios';
import qs from 'qs';
import NProgress from 'nprogress';

export default function (api) {

	let apiUrl = 'api';

	let tokenKey = window.location.protocol + window.location.host + '/' + (api ? `${api}.` : '') + 'token';
	let loginUrl = (api ? `/${api}` : '') + '/login';
	let userNameKey = 'username';
	let userGradeKey = 'usergrade';

	function getToken() {
		return window.localStorage.getItem(tokenKey);
	}

	function setToken(token) {
		return window.localStorage.setItem(tokenKey, token);
	}

	function deleteToken() {
		return window.localStorage.removeItem(tokenKey);
	}

	function getUserName() {
		return window.localStorage.getItem(userNameKey);
	}

	function setUserName(username) {
		return window.localStorage.setItem(userNameKey, username);
	}

	function deleteUserName() {
		return window.localStorage.removeItem(userNameKey);
	}

	function getUserGrade() {
		return window.localStorage.getItem(userGradeKey);
	}

	function setUserGrade(usergrade) {
		return window.localStorage.setItem(userGradeKey, usergrade);
	}

	function deleteUserGrade() {
		return window.localStorage.removeItem(userGradeKey);
	}

	function gotoLogin() {
		window.location.href = loginUrl;
	}

	let instance = axios.create({
		headers: {
			'X-CSRF-TOKEN': getToken() || ''
		}
	});
	instance.interceptors.request.use(config => {
		NProgress.start();
		return config;
	});

	instance.interceptors.response.use(response => {
		NProgress.done();
		return response;
	}, error => {
		NProgress.done();
		// 超时或未登录自动跳转到登录页面
		if (error.response.status === 406 || error.response.status === 401) {
			deleteToken();
			gotoLogin();
			return Promise.reject();
		}
		return Promise.reject(error)
	});

	return {
		getToken() {
			return getToken();
		},
		setToken(token) {
			setToken(token);
		},
		deleteToken() {
			deleteToken();
		},
		getUserName() {
			return getUserName();
		},
		setUserName(username) {
			setUserName(username);
		},
		deleteUserName() {
			deleteUserName();
		},
		getUserGrade() {
			return getUserGrade();
		},
		setUserGrade(usergrade) {
			setUserGrade(usergrade);
		},
		deleteUserGrade() {
			deleteUserGrade();
		},
		gotoLogin() {
			gotoLogin();
		},
		buildUrl(url) {
			return apiUrl + url;
		},
		post(url, data) {
			return instance({
				method: 'post',
				url: apiUrl + url,
				data: qs.stringify(data),
			});
		},
		get(url, params) {
			return instance({
				method: 'get',
				url: apiUrl + url,
				params,
			});
		},
		put(url, data) {
			return instance({
				method: 'put',
				url: apiUrl + url,
				data: qs.stringify(data),
			});
		},
		delete(url, params) {
			return instance({
				method: 'delete',
				url: apiUrl + url,
				params,
			});
		},
		direct(url, params, target) {
			url += (-1 === url.indexOf('?')) ? '?' : '&';
			if (!!params === true) {
				if (typeof params === 'string') {
					url += params;
				}
				if (typeof params === 'object') {
					url += Object.toParams(params);
				}
			}

			if ('_blank' === target) {
				window.open(apiUrl+url + '&_token=' + getToken() || '');
			} else {
				window.location.href = apiUrl+url + '&_token=' + getToken() || '';
			}
		}
	};
}