<template>
    <div>
        <Breadcrumb title="品牌一览"></Breadcrumb>
        <section class="content container-fluid">
            <Row>
                <Row>
                    <Col span="12">
                        <Button type="success" @click="handleCreate" v-can="$admin.postBrand">新增</Button>
                        <Button type="error" @click="handleDelete" :disabled="deleteStatus" v-can="$admin.delBrands">
                            删除
                        </Button>
                    </Col>
                </Row>
                <Table ref="table"
                       @on-selection-change="selectionChange"
                       :columns="columns"
                       :data="data">
                </Table>
                <Row>
                    <Col span="12">
                        <Page :total="query.total"
                              show-total
                              show-elevator
                              show-sizer
                              @on-change="pageChange"
                              @on-page-size-change="pageSizeChange">
                        </Page>
                    </Col>
                </Row>
            </Row>

            <Modal :transfer="false"
                   :z-index="1"
                   v-model="visable"
                   title="品牌">
                <Form ref="form" :model="form" :rules="rules" :label-width="80">
                    <FormItem label="名称" prop="title">
                        <Input v-model="form.title" size="large" placeholder="请输入名称"></Input>
                    </FormItem>
                    <FormItem label="排序" prop="sort">
                        <Tooltip content="品牌排序,值越小越靠前" placement="right">
                            <Icon type="help-circled"></Icon>
                        </Tooltip>
                        <InputNumber :max="255" :min="0" v-model="form.sort"></InputNumber>
                    </FormItem>
                    <FormItem label="商标" prop="images">
                        <xayah v-model="form.images"
                               :urls="urls"
                               type="string"
                               :config="config">
                        </xayah>
                    </FormItem>
                </Form>
                <div slot="footer">
                    <Button type="primary" v-if="!form.id" @click="handleSubmit">保存</Button>
                    <Button type="warning" v-else @click="handleSubmit">更新</Button>
                    <Button @click="handleReset" style="margin-left: 8px">重置</Button>
                </div>
            </Modal>
        </section>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                urls: {
                    index: '/admin/file/index',    //获取文件地址
                    upload: '//dixfe.oss-cn-hongkong.aliyuncs.com/',   //上传地址
                    create: '/admin/file/create',   //创建目录地址
                    check: '/admin/file/check',    //检查文件唯一
                    policy: '/admin/file/policy',   //获取上传策略地址
                    delete: '/admin/file/batch',   //删除文件或目录地址
                    return: process.env.NODE_ENV === 'development' ? '/rakan/callback/oss' : null,   //本地回调地址
                },
                config: {
                    random: false,
                    size: 0,
                    format: [
                        'jpg', 'png', 'jpeg'
                    ],
                    style: '',
                    key: 'id',
                    gateway: 'oss'
                },
                visable: false,
                query: {
                    page: 1,
                    per_page: 15,
                    total: 0,
                },
                form: {
                    id: null,
                    title: null,
                    sort: 0,
                    images: ''
                },
                rules: {
                    title: [
                        {
                            required: true,
                            min: 2,
                            max: 15,
                            message: '请输入2字及以上,15字以内的品牌名称',
                            trigger: 'blur'
                        },
                    ],
                    sort: [
                        {
                            required: true,
                            type: 'number',
                            message: '请输入0到255内的整数',
                            trigger: 'blur',
                            min: 0,
                            max: 255,
                            transform: value => value ? Number("" + value) : value
                        },
                    ],
                    images: [
                        {
                            required: true,
                        },
                    ]
                },
                columns: [
                    {
                        type: 'selection',
                        width: 60,
                        align: 'center'
                    },
                    {
                        title: '品牌LOGO',
                        render: (h, params) => {
                            const img = params.row.images;

                            return h('img', {
                                attrs: {
                                    src: img,
                                    width: 50,
                                    height: 50
                                }
                            });
                        }
                    },
                    {
                        title: '名称',
                        key: 'title'
                    },
                    {
                        title: '排序',
                        key: 'sort'
                    },
                    {
                        title: '操作',
                        align: 'center',
                        render: (h, params) => {
                            const row = params.row;
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'warning',
                                        size: 'small',
                                    },
                                    style: {
                                        marginRight: '2px',
                                        display: this.$admin.putBrand(true) ? '' : 'none'
                                    },
                                    on: {
                                        click: () => {
                                            this.handleEdit(row)
                                        }
                                    }
                                }, '编辑')
                            ]);
                        }
                    }
                ],
                data: [],
                selection: []
            }
        },
        mounted() {
            this.getData();
        },
        computed: {
            deleteStatus() {
                return !this.selection.length;
            }
        },
        methods: {
            getData() {
                this.visable = false;
                this.selection = [];

                let that = this;
                this.$admin.getBrands(this.query).then(function (res) {
                    that.data = res.data;
                    that.query.total = res.total;
                });
            },
            handleEdit(row) {
                this.form = JSON.parse(JSON.stringify(row));
                this.visable = true;
            },
            handleCreate() {
                try {
                    this.$refs.form.resetFields();
                } catch (e) {

                }
                this.clear();
                this.visable = true;
            },
            selectionChange(selection) {
                this.selection = selection;
            },
            handleDelete() {
                let that = this;
                let form = {
                    ids: _(this.selection).map('id').filter().flatMap().value(),
                };

                this.$admin.delBrands(form).then(function (res) {
                    if (res.code === 200) {
                        that.$Notice.success({
                            title: res.msg
                        });

                        that.getData();
                    } else {
                        that.$Notice.error({
                            title: res.msg
                        });
                    }
                });
            },
            pageChange(val) {
                this.query.page = val;
                this.getData();
            },
            pageSizeChange(val) {
                this.query.page = 1;
                this.query.per_page = val;
                this.getData();
            },
            handleSubmit() {
                let that = this;
                this.$refs.form.validate((valid) => {
                    if (valid) {
                        if (this.form.id) {
                            this.$admin.putBrand(this.form).then(function (res) {
                                if (res.code === 200) {
                                    that.$Notice.success({
                                        title: res.msg
                                    });

                                    that.getData();
                                } else {
                                    that.$Notice.error({
                                        title: res.msg
                                    });
                                }
                            });
                        } else {
                            this.$admin.postBrand(this.form).then(function (res) {
                                if (res.code === 200) {
                                    that.$Notice.success({
                                        title: res.msg
                                    });

                                    that.getData();
                                } else {
                                    that.$Notice.error({
                                        title: res.msg
                                    });
                                }
                            });
                        }
                    } else {
                        this.$Notice.warning({
                            title: '品牌参数有误',
                            desc: '请检查品牌参数'
                        });
                    }
                });
            },
            clear() {
                this.form = {
                    id: null,
                    title: null,
                    sort: 0,
                    images: ''
                };
            },
            handleReset() {
                this.clear();
                this.$refs.form.resetFields();
            }
        }
    }
</script>
