<template>
    <div>
        <bread-crumb title="添加商品"></bread-crumb>
        <section class="content container-fluid">
            <Form ref="form" :model="form" :rules="rules" :label-width="100">
                <FormItem label="标题" prop="title">
                    <Input v-model="form.title" placeholder="请输入商品标题" name="title" style="width:500px"></Input>
                </FormItem>
                <FormItem label="副标题">
                    <Input v-model="form.subtitle" placeholder="请输入商品副标题" name="subtitle" style="width:500px"></Input>
                </FormItem>
                <FormItem label="是否上架" prop="if_open">
                    <RadioGroup v-model="form.if_open">
                        <Radio :label="1">立即上架</Radio>
                        <Radio :label="0">存入仓库</Radio>
                    </RadioGroup>
                </FormItem>
                <FormItem label="品牌">
                    <Select
                            v-model="form.brand"
                            @on-change="brandChange"
                            style="width:100px"
                            placeholder="请选择品牌">
                        <Option v-for="item in brands" :value="item.value" :key="item.value">
                            {{ item.label }}
                        </Option>
                    </Select>
                </FormItem>
                <FormItem label="API分类">
                    <Cascader :data="categories"
                              v-model="form.categories"
                              @on-change="categoryChange"
                              style="width:300px"
                              placeholder="请选择API分类">
                    </Cascader>
                </FormItem>
                <FormItem label="商品图片">
                    <xayah v-model="form.images"
                           :urls="urls"
                           :max="5"
                           :config="config">
                    </xayah>
                </FormItem>
                <FormItem label="货号/SPU">
                    <Input v-model="form.spu" placeholder="请输入货号/SPU" name="spu" style="width:300px"></Input>
                </FormItem>
                <FormItem label="价格">
                    <InputNumber v-model="form.price"
                                 :precision="2"
                                 placeholder="请输入价格"
                                 :min="1"></InputNumber>
                </FormItem>
                <div>
                    <Row>
                        <Col span="24">
                            <FormItem style="height: 10px;" label="规格参数">
                                <Row :gutter="10">　
                                    <Col span="2">
                                        型号
                                    </Col>
                                    <Col span="2">
                                        规格
                                    </Col>
                                    <Col span="2">
                                        价格
                                    </Col>
                                    <Col span="2">
                                        库存
                                    </Col>
                                    <Col span="3">
                                        货号
                                    </Col>
                                    <Col span="3">
                                        缩略图
                                    </Col>
                                    <Col span="3">
                                        操作
                                    </Col>
                                </Row>
                            </FormItem>
                        </Col>
                    </Row>
                    <FormItem
                            v-for="(item, index) in form.items"
                            :key="index"
                            :label="'单品 ' + (index+1) ">
                        <Row :gutter="10">
                            <Col span="2">
                                <Poptip trigger="focus">
                                    <Input type="text" v-model="item.module"
                                           placeholder="请输入型号"></Input>
                                    <div slot="content">{{ formatText(item.module) }}</div>
                                </Poptip>
                            </Col>
                            <Col span="2">
                                <Poptip trigger="focus">
                                    <Input type="text" v-model="item.standard"
                                           placeholder="请输入规格"></Input>
                                    <div slot="content">{{ formatText(item.standard) }}</div>
                                </Poptip>
                            </Col>
                            <Col span="2">
                                <Poptip trigger="focus">
                                    <InputNumber v-model="item.price"
                                                 :precision="2"
                                                 placeholder="请输入价格"
                                                 :min="1"></InputNumber>
                                    <div slot="content">{{ formatText(item.price) }}</div>
                                </Poptip>
                            </Col>
                            <Col span="2">
                                <Poptip trigger="focus">
                                    <InputNumber v-model="item.reserve"
                                                 placeholder="请输入库存"
                                                 :min="1"></InputNumber>
                                    <div slot="content">{{ formatText(item.reserve) }}</div>
                                </Poptip>
                            </Col>
                            <Col span="2">
                                <Poptip trigger="focus">
                                    <Input type="text" v-model="item.sku"
                                           placeholder="请输入货号"></Input>
                                    <div slot="content">{{ formatText(item.sku) }}</div>
                                </Poptip>
                            </Col>
                            <Col span="3">
                                <xayah v-model="item.images"
                                       :urls="urls"
                                       :max="1"
                                       type="string"
                                       :config="config">
                                </xayah>
                            </Col>
                            <Col span="3">
                                <Button :disabled="!index" @click="handleRemove(index)">删除</Button>
                            </Col>
                        </Row>
                    </FormItem>
                    <FormItem>
                        <Row>
                            <Col span="12">
                                <Button type="dashed" long @click="handleAdd" icon="plus-round">新增单品</Button>
                            </Col>
                        </Row>
                    </FormItem>
                </div>
                <FormItem label="商品详情">
                    <xayah :value="[]"
                           :urls="urls"
                           id="editorImage"
                           :max="10"
                           :config="config"
                           style="display: none"
                           @callback="callback">
                    </xayah>
                    <quill-editor
                            v-model="form.content"
                            ref="myQuillEditor"
                            :options="editorOption">
                    </quill-editor>
                </FormItem>
                <FormItem>
                    <Button type="primary" @click="handleSubmit">创建</Button>
                    <Button @click="handleReset" style="margin-left: 8px">重置</Button>
                </FormItem>
            </Form>
        </section>
        <BackTop></BackTop>
    </div>
</template>

<script>
    import BreadCrumb from "../BreadCrumb";

    export default {
        components: {BreadCrumb},
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
                value: [
                    20,
                    50
                ],
                editorOption: {
                    theme: 'snow',
                    placeholder: '选中文本编辑器在添加图片！填写商品介绍；文字（非参数类）+图片（商品详情图/海报，宽度不超过980px）',
                },
                brands: [],
                form: {
                    title: null,
                    subtitle: null,
                    spu: null,
                    price: null,
                    brand: null,
                    brand_name: null,
                    if_open: 1,
                    categories: [],
                    categories_name: [],
                    images: [],
                    content: null,
                    items: [
                        {
                            sku: null,
                            module: null,
                            standard: null,
                            price: null,
                            reserve: null,
                            images: null,
                        }
                    ]
                },
                rules: {
                    title: {
                        required: true,
                        message: '请输入商品标题',
                        trigger: 'blur',
                    },
                    subtitle: {
                        required: true,
                        message: '请输入商品副标题',
                        trigger: 'blur',
                    },
                    spu: {
                        required: true,
                        message: '请输入商品SPU',
                        trigger: 'blur',
                    },
                    price: {
                        required: true,
                        message: '请输入商品价格',
                        trigger: 'blur',
                    },
                    brand: {
                        required: true,
                        message: '请选择品牌',
                        trigger: 'blur',
                    },
                    brand_name: [],
                    if_open: {
                        required: true,
                        message: '请选择上下架状态',
                        trigger: 'blur',
                    },
                    categories: {
                        required: true,
                        message: '请选择分类',
                        trigger: 'blur',
                    },
                    categories_name: [],
                    images: {
                        required: true,
                        message: '请选择图片',
                        trigger: 'blur',
                    },
                    content: {
                        required: true,
                        message: '请输入详情',
                        trigger: 'blur',
                    },
                    items: {
                        required: true,
                        type: 'object',
                        fields: {
                            sku: {
                                type: "string",
                                required: true
                            },
                            module: {
                                type: "string",
                                required: true
                            },
                            standard: {
                                type: "string",
                                required: true
                            },
                            price: {
                                type: "numeric",
                                required: true
                            },
                            reserve: {
                                type: "numeric",
                                required: true
                            },
                            images: {
                                type: "string",
                                required: true
                            },
                        }
                    }
                },
                categories: [],
                order: 0
            };
        },
        mounted() {
            this.getCategories();
            this.getBrands();
            this.initEditor();
        },
        methods: {
            formatText(val) {
                if (!this.val) {
                    return 'Enter value';
                }
                return val;
            },
            callback(val) {
                let that = this;
                val.forEach(function (value, index, array) {
                    that.$refs.myQuillEditor.quill.insertEmbed(++that.order, 'image', value.url);
                });
            },
            initEditor() {
                this.$refs.myQuillEditor.quill.getModule('toolbar').addHandler('image', this.openImage);
                this.$refs.myQuillEditor.quill.getModule('toolbar').addHandler('video', this.openVideo);
            },
            openImage() {
                document.getElementById('editorImage').click();
            },
            openVideo() {
                this.$Notice.warning({
                    title: '视频上传暂不支持',
                    desc: '敬请期待'
                });
            },
            categoryChange(value, selection) {
                let res = _(selection).map().filter().flatMap('label').value();
                this.form.categories_name = res;
            },
            brandChange(val) {
                let res = _(this.brands).map().filter(function (o) {
                    return o.value === val;
                }).flatMap('label').value();

                this.form.brand_name = res[0];
            },
            getCategories() {
                let that = this;
                this.$admin.getCategories().then(function (res) {
                    that.categories = res;
                });
            },
            getBrands() {
                let that = this;
                this.$admin.getBrandSelect().then(function (res) {
                    that.brands = res;
                });
            },
            handleSubmit() {
                let that = this;
                this.$refs.form.validate((valid) => {
                    if (valid) {
                        this.$admin.postProduct(this.form).then(function (res) {
                            if (res.code === 200) {
                                that.$Notice.success({
                                    title: res.msg
                                });
                                that.clear();
                            } else {
                                that.$Notice.error({
                                    title: res.msg
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
            },
            handleReset() {
                try {
                    this.clear();
                    this.$refs.form.resetFields();
                } catch (e) {

                }
            },
            handleAdd() {
                this.form.items.push({
                    sku: null,
                    module: null,
                    standard: null,
                    price: null,
                    reserve: null,
                    images: null,
                });
            },
            handleRemove(index) {
                this.form.items.splice(index, 1);
            },
            clear() {
                this.form = {
                    title: null,
                    subtitle: null,
                    video_url: null,
                    spu: null,
                    price: null,
                    if_open: 1,
                    categories: [],
                    categories_name: [],
                    images: [],
                    content: null,
                    items: [
                        {
                            sku: null,
                            module: null,
                            standard: null,
                            price: null,
                            reserve: null,
                            images: null,
                        }
                    ]
                };
            }
        }
    }
</script>
