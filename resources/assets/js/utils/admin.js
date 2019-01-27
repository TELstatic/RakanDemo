import req from './request';
import permission from './permission';

let baseUrl = 'admin';

export default {
    //获取用户列表
    getUsers(form) {
        let url = baseUrl + '/user/index';

        return req.get(url, form);
    },
    //添加用户
    postUser(form) {
        let url = baseUrl + '/user';

        if (typeof (arguments[0]) === "boolean") {
            return permission.has(url);
        }

        return req.post(url, form);
    },
    //更新用户
    putUser(form) {
        let url = baseUrl + '/user/update/{id}';

        if (typeof (arguments[0]) === "boolean") {
            return permission.has(url);
        }

        return req.put(url.replace('{id}', form.id), form);
    },
    //获取角色下拉选择
    getRoleSelect() {
        let url = baseUrl + '/user/role';

        return req.get(url);
    },
    //获取角色
    getRoles() {
        let url = baseUrl + '/role/index';

        return req.get(url);
    },
    //添加角色
    postRole(form) {
        let url = baseUrl + '/role';

        if (typeof (arguments[0]) === "boolean") {
            return permission.has(url);
        }

        return req.post(url, form);
    },
    //更新角色
    putRole(form) {
        let url = baseUrl + '/role/update/{id}';

        if (typeof (arguments[0]) === "boolean") {
            return permission.has(url);
        }

        return req.put(url.replace('{id}', form.id), form);
    },
    //删除角色
    delRole(form) {
        let url = baseUrl + '/role/{id}';

        if (typeof (arguments[0]) === "boolean") {
            return permission.has(url);
        }

        return req.put(url.replace('{id}', form.id), form);
    },
    //获取权限
    getPermissions() {
        let url = baseUrl + '/permission/index';

        return req.get(url);
    },
    //获取 API 分类
    getCategories() {
        let url = baseUrl + '/category/tree';
        return req.get(url);
    },
    //获取品牌
    getBrandSelect() {
        let url = baseUrl + '/brand/select';
        return req.get(url);
    },
    //获取品牌列表
    getBrands(form) {
        let url = baseUrl + '/brand/index';
        return req.get(url, form);
    },
    //添加品牌
    postBrand(form) {
        let url = baseUrl + '/brand';

        if (typeof (arguments[0]) === "boolean") {
            return permission.has(url);
        }

        return req.post(url, form);
    },
    //更新品牌
    putBrand(form) {
        let url = baseUrl + '/brand/update/{id}';

        if (typeof (arguments[0]) === "boolean") {
            return permission.has(url);
        }

        return req.put(url.replace('{id}', form.id), form);
    },
    //批量删除品牌
    delBrands(form) {
        let url = baseUrl + '/brand/batch';

        if (typeof (arguments[0]) === "boolean") {
            return permission.has(url);
        }

        return req.del(url, form);
    },
    //获取商品列表
    getProducts(form) {
        let url = baseUrl + '/product/index';
        return req.get(url, form);
    },
    //添加商品
    postProduct(form) {
        let url = baseUrl + '/product';

        if (typeof (arguments[0]) === "boolean") {
            return permission.has(url);
        }

        return req.post(url, form);
    },
    //更新商品
    putProduct(form) {
        let url = baseUrl + '/product/update/{id}';

        if (typeof (arguments[0]) === "boolean") {
            return permission.has(url);
        }

        return req.put(url.replace('{id}', form.id), form);
    },
    //更新单品
    putProductItem(form) {
        let url = baseUrl + '/product/item';

        if (typeof (arguments[0]) === "boolean") {
            return permission.has(url);
        }

        return req.put(url, form);
    },
    //上下架商品
    putProductActive(form) {
        let url = baseUrl + '/product/active';

        if (typeof (arguments[0]) === "boolean") {
            return permission.has(url);
        }

        return req.put(url, form);
    },
    //删除商品
    delProducts(form) {
        let url = baseUrl + '/product/batch';

        if (typeof (arguments[0]) === "boolean") {
            return permission.has(url);
        }

        return req.del(url, form);
    },

}