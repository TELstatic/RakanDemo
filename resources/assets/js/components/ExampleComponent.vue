<template>
    <div>
        <xayah
                v-model="images"
                :config="config"
                :urls="urls"
                @callback="callback">
        </xayah>

        <Upload
                ref="upload1"
                :show-upload-list="false"
                :default-file-list="[]"
                :on-success="handleSuccess"
                :format="['jpg','jpeg','png']"
                :max-size="2048"
                :data="headers"
                :on-format-error="handleFormatError"
                :on-exceeded-size="handleMaxSize"
                :before-upload="beforeUpload"
                multiple
                type="drag"
                action="//upload-z1.qiniup.com/"
                style="display: inline-block;width:58px;">
            <div style="width: 58px;height:58px;line-height: 58px;">
                <Icon type="ios-camera" size="20"></Icon>
            </div>
        </Upload>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                images: [],
                urls: {
                    index: '/file/index',    //获取文件地址
                    upload: '//upload-z1.qiniup.com/',   //上传地址
                    create: '/file/create',   //创建目录地址
                    check: '/file/check',    //检查文件唯一
                    policy: '/file/policy',   //获取上传策略地址
                    delete: '/file/batch',   //删除文件或目录地址
                    return: '/rakan/callback/qiniu',   //本地回调地址
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
                },
                headers: {
                    key: null,
                    token: 'JyQ4aZNe87tA2FG-I01lxp7kJArP6opY_renx7MU:9jhwftBs7VIFuRn8YGVUi-xjFb0=:eyJjYWxsYmFja1VybCI6Imh0dHA6XC9cL3Rlc3QucGVjYWRvLnRvcDo4OCIsImNhbGxiYWNrQm9keSI6IntcImZpbGVuYW1lXCI6XCIkKGtleSlcIiwgXCJzaXplXCI6XCIkKGZzaXplKVwiLFwibWltZVR5cGVcIjpcIiQobWltZVR5cGUpLFwid2lkdGhcIjpcIiQoaW1hZ2VJbmZvLndpZHRoKVwiLFwiaGVpZ2h0XCI6XCIkKGltYWdlSW5mby5oZWlnaHQpXCIiLCJzY29wZSI6InRlbHN0YXRpYyIsImRlYWRsaW5lIjoxNTQ1ODg0NTA1fQ==',
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
            },
            handleFormatError() {
            },
            handleMaxSize() {
            },
            beforeUpload(file) {
                this.headers.key = file.name;
            }
        }
    }
</script>
