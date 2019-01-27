<template>
    <div>
        <bread-crumb title="角色管理"></bread-crumb>
        <section class="content container-fluid">
            <Button type="success" @click="handleCreate"> 添加</Button>
            <Table :columns="columns" :data="data"></Table>

            <Page :total="query.total"
                  show-total
                  show-sizer
                  show-elevator
                  @on-change="handlePageChange"
                  @on-page-size-change="handlePageSizeChange">
            </Page>

            <Modal
                    v-model="visable"
                    :title="form.id ?'编辑角色':'添加角色'"
                    width="1000"
                    @on-ok="handleSubmit"
                    @on-cancel="handleReset">
                <Form :model="form" ref="form" :rules="rules" :label-width="80">
                    <FormItem label="角色名" prop="name">
                        <Input v-model="form.name" placeholder="请输入角色名"></Input>
                    </FormItem>

                    <FormItem label="权限" prop="permissions">
                        <div>
                            <div v-for="(item,index) in permissions" :key="index">
                                <CheckboxGroup v-model="form.permissions">
                                    <Tooltip :content="item.class.desc.join('')">
                                        <span @click="handleCheck(item)">{{item.class.title}}</span>
                                    </Tooltip>
                                    <Checkbox v-for="(action,index1) in item.actions"
                                              :key="index1"
                                              :label="action.auth">
                                        <Tooltip :content="action.doc.desc.join('')">
                                            {{action.doc.title}}
                                        </Tooltip>
                                    </Checkbox>
                                </CheckboxGroup>
                            </div>
                        </div>
                    </FormItem>

                </Form>
                <div slot="footer">
                    <Button :type="form.id ?'warning':'primary'" @click="handleSubmit">
                        {{form.id ? '修改':'添加'}}
                    </Button>
                    <Button type="dashed" @click="handleReset">重置</Button>
                </div>
            </Modal>
        </section>
    </div>
</template>
<script>
    import BreadCrumb from "../BreadCrumb";

    export default {
        components: {BreadCrumb},
        data() {
            return {
                visable: false,
                query: {
                    page: 1,
                    per_page: 15,
                    total: 0
                },
                data: [],
                form: {
                    id: null,
                    name: null,
                    permissions: []
                },
                permissions: [],
                currentRow: {},
                rules: {
                    name: {
                        required: true,
                        message: '请输入角色名',
                        trigger: 'blur'
                    },
                    permissions: {
                        required: true,
                        type: 'array',
                        message: '请选择权限',
                        trigger: 'blur'
                    },
                },
                roles: [],
                columns: [
                    {
                        title: '角色名',
                        key: 'name'
                    },
                    {
                        title: '操作',
                        width: 300,
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'warning',
                                        size: 'small'
                                    },
                                    style: {
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {
                                            this.handleEdit(params.row);
                                        }
                                    }
                                }, '编辑'),
                                h('Button', {
                                    props: {
                                        type: 'error',
                                        size: 'small',
                                        disabled: params.row.id === 1 || params.row.name === '超级管理员'
                                    },
                                    style: {
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {
                                            this.handleDelete(params.row);
                                        }
                                    }
                                }, '删除')
                            ]);
                        }
                    }
                ]
            }
        },
        mounted() {
            this.getPermissions();
            this.getData();
        },
        methods: {
            getPermissions() {
                let that = this;
                this.$admin.getPermissions().then(res => {
                    that.permissions = _.merge(res.Admin, []);
                })
            },
            getData() {
                let that = this;
                this.$admin.getRoles(this.query).then(res => {
                    that.data = res.data;
                    that.query.total = res.total;
                });
            },
            handleEdit(row) {
                this.currentRow = JSON.parse(JSON.stringify(row));
                this.form = this.currentRow;
                this.form.permissions = _(this.currentRow.permissions).map().filter().flatMap('name').value();

                this.visable = true;
            },
            handleCreate() {
                this.clear();
                this.visable = true;
            },
            handleCheck(item) {
                if (!item.class.check) {
                    let res = _(item.actions).map().filter().flatMap('auth').value();
                    this.form.permissions = this.form.permissions.concat(res);
                    item.class.check = true;
                } else {
                    let res = _(item.actions).map().filter().flatMap('auth').value();
                    this.form.permissions = _.xor(this.form.permissions, res);
                    item.class.check = false;
                }
            },
            handleSubmit() {
                let that = this;
                this.$refs.form.validate((valid) => {
                    if (valid) {
                        if (that.form.id) {
                            that.$admin.putRole(that.form).then(res => {
                                if (res.code === 200) {
                                    that.$Notice.success({
                                        title: res.msg
                                    });
                                    that.clear();
                                    that.getData();
                                } else {
                                    that.$Notice.error({
                                        title: res.msg
                                    });
                                }
                            });
                        } else {
                            that.$admin.postRole(that.form).then(res => {
                                if (res.code === 200) {
                                    that.$Notice.success({
                                        title: res.msg
                                    });
                                    that.clear();
                                    that.getData();
                                } else {
                                    that.$Notice.error({
                                        title: res.msg
                                    });
                                }
                            });
                        }
                    } else {
                        that.$Notice.warning({
                            title: '表单验证错误',
                            desc: '请检查'
                        });
                    }
                });
            },
            handleDelete(row) {
                let that = this;
                let form = {};
                form.id = row.id;

                this.$admin.delRole(form).then(res => {
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
            handlePageChange(val) {
                this.query.page = val;
                this.getData();
            },
            handlePageSizeChange(val) {
                this.query.page = 1;
                this.query.per_page = val;
                this.getData();
            },
            handleReset() {
                if (this.form.id) {
                    this.recover();
                } else {
                    this.clear();
                }
            },
            clear() {
                this.visable = false;

                this.form = {
                    id: null,
                    name: null,
                    permissions: []
                };

                try {
                    this.$refs.form.resetFields();
                } catch (e) {

                }

            },
            recover() {
                this.form = JSON.parse(JSON.stringify(this.currentRow));
                this.form.permissions = _(this.currentRow.permissions).map().filter().flatMap('name').value();
            }
        }
    }
</script>