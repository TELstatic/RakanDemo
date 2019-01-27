import axios from 'axios';
import {Notice} from 'iview';

axios.defaults.baseURL = '/';
axios.defaults.timeout = 10000;

axios.interceptors.response.use(response => {
    console.group('%cSuccess', 'color:green;font-size:18px;', response.config.url);
    console.log('Code::', response.status);
    console.log('Data::', response.data);
    console.groupEnd();

    return response;
}, error => {
    console.group('%cError', 'color:red;font-size:18px;', error.response.config.url);
    console.log('Code::', error.response.status);
    console.log('Data::', error.response.data);
    console.groupEnd();

    error.desc = null;
    if (error && error.response) {
        switch (error.response.status) {
            case 400:
                error.message = '错误请求';
                break;
            case 401:
                error.message = '未授权，请重新登录';
                break;
            case 403:
                error.message = '拒绝访问';
                break;
            case 404:
                error.message = '请求错误,未找到该资源';
                break;
            case 405:
                error.message = '请求方法未允许';
                break;
            case 408:
                error.message = '请求超时';
                break;
            case 422:
                error.message = '表单验证错误';
                error.desc = error.response.data.errors[Object.keys(error.response.data.errors)[0]];
                break;
            default:
            case 500:
                error.message = '服务器端出错';
                break;
            case 501:
                error.message = '网络未实现';
                break;
            case 502:
                error.message = '网络错误';
                break;
            case 503:
                error.message = '服务不可用';
                break;
            case 504:
                error.message = '网络超时';
                break;
            case 505:
                error.message = 'http版本不支持该请求';
                break;
        }
    } else {
        error.message = "连接到服务器失败"
    }

    Notice.open({
        title: error.message,
        desc: error.desc,
        duration: 3
    });

    return Promise.resolve(error.response);
});


export default {
    get(url, param) {
        console.group('%cGET', 'color:blue;font-size:18px;', url);
        console.log('Url::', location.host + url);
        console.log('Params::', param);
        console.groupEnd();

        return new Promise((resolve, reject) => {
            axios({
                method: 'get',
                url,
                params: param
            }).then(res => {
                resolve(res.data)
            })
        })
    },
    post(url, param) {
        console.group('%cPOST', 'color:blue;font-size:18px;', url);
        console.log('Params::', param);
        console.groupEnd();
        return new Promise((resolve, reject) => {
            axios({
                method: 'post',
                url,
                data: param
            }).then(res => {
                resolve(res.data)
            })
        })
    },
    put(url, param) {
        console.group('%cPUT', 'color:blue;font-size:18px;', url);
        console.log('Params::', param);
        console.groupEnd();
        return new Promise((resolve, reject) => {
            axios({
                method: 'put',
                url,
                data: param
            }).then(res => {
                resolve(res.data)
            })
        })
    },
    del(url, param) {
        console.group('%cDELETE', 'color:blue;font-size:18px;', url);
        console.log('Params::', param);
        console.groupEnd();
        return new Promise((resolve, reject) => {
            axios({
                method: 'delete',
                url,
                data: param
            }).then(res => {
                resolve(res.data)
            })
        })
    }
}