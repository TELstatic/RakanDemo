<template>
    <div>
        <bread-crumb title="用户列表"></bread-crumb>
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
                    :title="form.id ?'编辑用户':'添加用户'"
                    width="1000"
                    @on-ok="handleSubmit"
                    @on-cancel="handleReset">
                <Form :model="form" ref="form" :rules="rules" :label-width="80">
                    <FormItem label="昵称" prop="name">
                        <Input v-model="form.name" placeholder="请输入昵称" :disabled="!!form.id"></Input>
                    </FormItem>
                    <FormItem label="邮箱" prop="email">
                        <Input v-model="form.email" placeholder="请输入邮箱" :disabled="!!form.id"></Input>
                    </FormItem>
                    <FormItem label="角色" style="width: 50%" prop="role">
                        <Select v-model="form.role" placeholder="请选择角色">
                            <Option :value="role.value" :key="index" v-for="(role,index) in roles">
                                {{role.label}}
                            </Option>
                        </Select>
                    </FormItem>
                    <FormItem label="密码" prop="password">
                        <Input v-model="form.password" :disabled="!!form.id" placeholder="请输入密码"></Input>
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
                    role: null,
                    email: null,
                    password: null
                },
                currentRow: {},
                rules: {
                    name: {
                        required: true,
                        message: '请输入昵称',
                        trigger: 'blur'
                    },
                    email: {
                        required: true,
                        message: '请输入手机号',
                        trigger: 'blur'
                    },
                    role: {
                        required: true,
                        message: '请选择角色',
                        trigger: 'blur'
                    },
                    password: {
                        required: true,
                        message: '请输入密码',
                        trigger: 'blur'
                    },
                },
                roles: [],
                columns: [
                    {
                        title: '序号',
                        key: 'id'
                    },
                    {
                        title: '昵称',
                        key: 'name'
                    },
                    {
                        title: '邮箱',
                        key: 'email'
                    },
                    {
                        title: '角色',
                        render: (h, params) => {
                            return h('div', [
                                h('Tag', {
                                    props: {
                                        color: 'green'
                                    },
                                }, params.row.roles.map(res => {
                                    return res.name;
                                }).join('.'))
                            ]);
                        }
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
                                }, '编辑')
                            ]);
                        }
                    }
                ]
            }
        },
        mounted() {
            this.getRoles();
            this.getData();
        },
        methods: {
            getRoles() {
                let that = this;
                this.$admin.getRoleSelect().then(res => {
                    that.roles = res;
                });
            },
            getData() {
                let that = this;
                this.$admin.getUsers(this.query).then(res => {
                    that.data = res.data;
                    that.query.total = res.total;
                });
            },
            handleEdit(row) {
                this.currentRow = JSON.parse(JSON.stringify(row));
                this.form = this.currentRow;
                try {
                    this.form.role = this.currentRow.roles[0].name;
                } catch (e) {
                    this.form.role = null;
                }

                this.visable = true;
                this.rules.password.required = false;
            },
            handleCreate() {
                this.clear();
                this.visable = true;
                this.rules.password.required = true;
            },
            handleSubmit() {
                let that = this;
                this.$refs.form.validate((valid) => {
                    if (valid) {
                        if (that.form.id) {
                            that.$admin.putUser(that.form).then(res => {
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
                            that.$admin.postUser(that.form).then(res => {
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
                    email: null,
                    role: null,
                    password: null
                };

                try {
                    this.$refs.form.resetFields();
                } catch (e) {

                }
            },
            recover() {
                this.form = JSON.parse(JSON.stringify(this.currentRow));
                this.form.role = this.currentRow.roles[0].name;
            }
        }
    }
</script>