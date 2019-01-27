<template>
    <div>
        <Form ref="form" :model="form" :rules="rules">
            <Table :data="data" :columns="columns">
                <template slot-scope="{ row, index }" slot="sku">
                    <FormItem prop="sku" v-if="currentIndex === index">
                        <Input type="text" v-model.trim="form.sku"></Input>
                    </FormItem>
                    <span v-else>{{ row.sku }}</span>
                </template>

                <template slot-scope="{ row, index }" slot="module">
                    <FormItem prop="module" v-if="currentIndex === index">
                        <Input type="text" v-model.trim="form.module"></Input>
                    </FormItem>
                    <span v-else>{{ row.module }}</span>
                </template>

                <template slot-scope="{ row, index }" slot="standard">
                    <FormItem prop="standard" v-if="currentIndex === index">
                        <Input type="text" v-model.trim="form.standard"></Input>
                    </FormItem>
                    <span v-else>{{ row.standard }}</span>
                </template>

                <template slot-scope="{ row, index }" slot="price">
                    <FormItem prop="price" v-if="currentIndex === index">
                        <InputNumber v-model="form.price"
                                     :precision="2"
                                     :min="1">
                        </InputNumber>
                    </FormItem>
                    <span v-else>{{ row.price }}</span>
                </template>

                <template slot-scope="{ row, index }" slot="reserve">
                    <FormItem prop="reserve" v-if="currentIndex === index">
                        <InputNumber type="text" v-model="form.reserve" :min="1"></InputNumber>
                    </FormItem>
                    <span v-else>{{ row.reserve }}</span>
                </template>

                <template slot-scope="{ row, index }" slot="action">
                    <div v-if="currentIndex === index">
                        <Button type="primary" size="small" @click="handleSubmit">保存</Button>
                        <Button type="dashed" size="small" @click="handleCancel">取消</Button>
                    </div>
                    <div v-else>
                        <Button type="warning" size="small" @click="handleEdit(row, index)">编辑</Button>
                    </div>
                </template>
            </Table>
        </Form>
    </div>
</template>
<script>
    export default {
        props: {
            data: {
                type: Array,
                default: []
            }
        },
        data() {
            return {
                currentIndex: -1,
                form: {
                    id: null,
                    sku: null,
                    module: null,
                    standard: null,
                    price: null,
                    reserve: null,
                },
                rules: {
                    sku: {
                        required: true,
                        message: '请输入SKU',
                        trigger: 'blur',
                    },
                    module: {
                        required: true,
                        message: '请输入型号',
                        trigger: 'blur',
                    },
                    standard: {
                        required: true,
                        message: '请输入规格',
                        trigger: 'blur',
                    },
                    price: {
                        required: true,
                        type: 'number',
                        message: '请输入价格',
                        trigger: 'blur',
                    },
                    reserve: {
                        required: true,
                        type: 'number',
                        message: '请输入库存',
                        trigger: 'blur',
                    },
                },
                columns: [
                    {
                        title: '图片',
                        width: 80,
                        render: (h, params) => {
                            const row = params.row;
                            return h('img', {
                                attrs: {
                                    src: row.images,
                                    width: 40,
                                    height: 40
                                }
                            });
                        }
                    },
                    {
                        title: 'sku',
                        slot: 'sku'
                    }, {
                        title: '规格',
                        slot: 'module'
                    }, {
                        title: '型号',
                        slot: 'standard'
                    },
                    {
                        title: '单价',
                        slot: 'price'
                    },
                    {
                        title: '库存',
                        slot: 'reserve'
                    },
                    {
                        title: '操作',
                        slot: 'action'
                    }
                ]
            }
        },
        methods: {
            handleEdit(row, index) {
                this.currentIndex = index;
                // this.form = JSON.parse(JSON.stringify(row));
                this.form = row;
                this.form.price = parseFloat(this.form.price);
            },
            handleCancel() {
                this.currentIndex = -1;
            },
            handleSubmit() {
                let that = this;
                this.$refs.form.validate((valid) => {
                    if (valid) {
                        this.$admin.putProductItem(this.form).then(res => {
                            if (res.code === 200) {
                                that.$Notice.success({
                                    title: res.msg,
                                });
                                that.handleCancel();
                            } else {
                                that.$Notice.error({
                                    title: res.msg,
                                });
                            }
                        });
                    } else {
                        this.$Notice.warning({
                            title: '表单参数有误',
                            desc: '请检查'
                        });
                    }
                });
            }
        }
    }
</script>
