<template>
    <div>
        <xayah
                v-model="images"
                :config="config"
                :urls="urls"
                @callback="callback">
        </xayah>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                images: [],
                urls: {
                    index: '/file/admin/index',    //获取文件地址
                    upload: '//arabeila.oss-cn-shanghai.aliyuncs.com/',   //上传地址
                    create: '/file/admin/create',   //创建目录地址
                    check: '/file/admin/check',    //检查文件唯一
                    policy: '/file/admin/policy',   //获取上传策略地址
                    delete: '/file/admin/batch',   //删除文件或目录地址
                    return: process.env.NODE_ENV === "development" ? '/rakan/callback/oss' : null,//本地回调地址
                },
                config: {
                    id: 'editorImage',
                    max: 5,
                    random: false,
                    size: 0,
                    format: [
                        'jpg', 'png', 'jpeg'
                    ],
                    style: '',
                    key: 'id',
                    gateway:'oss'
                }
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods: {
            callback(val) {
                console.log(val);
            },
            handleSuccess(res, file) {
                console.log(res, file)
            }
        }
    }
</script>
