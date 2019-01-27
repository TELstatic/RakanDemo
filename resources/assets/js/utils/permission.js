let permissions = [];

if (!sessionStorage.getItem('permissions')) {
    axios.get('/admin/user/permission').then(res => {
        sessionStorage.setItem('permissions', res.data);
    });
}

try {
    permissions = permissions.concat(sessionStorage.getItem('permissions').split(','));
}catch (e) {

}

export default {
    has(val) {
        if (permissions.indexOf(val) == -1) {
            return false;
        }

        return true;
    }
}