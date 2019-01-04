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
                    index: '/file/web/index',    //获取文件地址
                    upload: '//upload-z1.qiniup.com/',   //上传地址
                    create: '/file/web/create',   //创建目录地址
                    check: '/file/web/check',    //检查文件唯一
                    policy: '/file/web/policy',   //获取上传策略地址
                    delete: '/file/web/batch',   //删除文件或目录地址
                    return: process.env.NODE_ENV === "development" ? '/rakan/callback/qiniu' : null,//本地回调地址
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
                    gateway:'qiniu'
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
