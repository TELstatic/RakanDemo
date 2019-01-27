<template>
    <div>
        <bread-crumb title="商品一览"></bread-crumb>
        <section class="content container-fluid">
            <Row>
                <Col span="4">
                    <Col span="6" style="text-align: right;">
                        关键词:
                    </Col>
                    <Col span="14">
                        <Input type="text" v-model="query.keyword" placeholder="标题|SPU|SKU"/>
                    </Col>
                </Col>

                <Col span="4">
                    <Col span="6" style="text-align: right">
                        分类:
                    </Col>
                    <Col span="14">
                        <Cascader :data="categories"
                                  v-model="query.categories"
                                  placeholder="请选择API分类">
                        </Cascader>
                    </Col>
                </Col>

                <Col span="6">
                    <Button type="info" @click="handleSearch">查询</Button>
                    <Button type="default" @click="handleReload">全部</Button>
                </Col>
            </Row>

            <Button type="success" @click="handleUp" :disabled="upStatus" v-can="$admin.putProductActive">上架</Button>
            <Button type="warning" @click="handleDown" :disabled="downStatus" v-can="$admin.putProductActive">下架
            </Button>

            <Table border ref="table" :loading="loading" :columns="columns" :data="data"
                   @on-selection-change="selectionChange"></Table>

            <Page :total="query.total"
                  show-total
                  show-elevator
                  show-sizer
                  @on-change="pageChange"
                  @on-page-size-change="pageSizeChange">
            </Page>
        </section>
    </div>
</template>
<script>
    import BreadCrumb from '../BreadCrumb'
    import ProductExpand from './expand';

    export default {
        components: {
            BreadCrumb, ProductExpand
        },
        data() {
            return {
                loading: true,
                query: {
                    keyword: null,
                    page: 1,
                    per_page: 15,
                    total: 0,
                    categories: [],
                    if_open: null,
                },
                openStatus: [
                    {
                        label: '下架中',
                        color: 'error'
                    },
                    {
                        label: '上架中',
                        color: 'success'
                    }
                ],
                columns: [
                    {
                        type: 'selection',
                        width: 60,
                        align: 'center'
                    },
                    {
                        type: 'index',
                        width: 60,
                        align: 'center'
                    },
                    {
                        title: '标题',
                        width: 250,
                        key: 'title'
                    },
                    {
                        title: 'SPU',
                        width: 80,
                        key: 'spu'
                    },
                    {
                        title: '品牌',
                        width: 80,
                        key: 'brand_name'
                    },
                    {
                        title: '分类',
                        render: (h, params) => {
                            return h('div', {}, params.row.categories_name.join('/'));
                        }
                    },
                    {
                        title: '上下架',
                        key: 'if_open',
                        align: 'center',
                        render: (h, params) => {
                            const row = params.row;

                            let tag = h('Tag', {
                                props: {
                                    color: this.openStatus[row.if_open].color
                                }
                            }, this.openStatus[row.if_open].label);

                            return h('div', [tag]);
                        }
                    },
                    {
                        title: '操作',
                        align: 'center',
                        render: (h, params) => {
                            const row = params.row;

                            let action1 = h('Button', {
                                props: {
                                    type: 'default',
                                    size: 'small',
                                },
                                style: {
                                    marginRight: '2px'
                                },
                                on: {
                                    click: () => {
                                        this.handleReview(row);
                                    }
                                }
                            }, '预览');

                            let action2 = h('Button', {
                                props: {
                                    type: 'warning',
                                    size: 'small',
                                },
                                style: {
                                    marginRight: '2px'
                                },
                                on: {
                                    click: () => {
                                        this.handleEdit(row);
                                    }
                                }
                            }, '编辑');

                            return h('div', [
                                action1, action2
                            ]);
                        }
                    },
                    {
                        title: '#',
                        type: 'expand',
                        width: 50,
                        render: (h, params) => {
                            const items = params.row.items;

                            return h(ProductExpand, {
                                props: {
                                    data: items
                                }
                            });
                        }
                    },
                ],
                data: [],
                selection: [],
                categories: [],
            }
        },
        computed: {
            upStatus() {
                let res = _(this.selection).map().filter(function (o) {
                    return o.if_open === 0;
                }).flatMap().value().length;
                return !res;
            },
            downStatus() {
                let res = _(this.selection).map().filter(function (o) {
                    return o.if_open === 1;
                }).flatMap().value().length;
                return !res;
            },
        },
        mounted() {
            this.getData();
            this.getCategories();
        },
        methods: {
            getData() {
                let that = this;
                this.selection = [];
                this.loading = true;
                this.$admin.getProducts(this.query).then(res => {
                    that.data = res.data;
                    that.query.total = res.total;
                    that.loading = false;
                });
            },
            getCategories() {
                let that = this;
                this.$admin.getCategories().then(function (res) {
                    that.categories = res;
                });
            },
            handleSearch() {
                this.getData();
            },
            handleReload() {
                this.query = {
                    keyword: null,
                    page: 1,
                    per_page: 15,
                    total: 0,
                    categories: [],
                    if_open: null,
                };

                this.getData();
            },
            handleReview(row) {
                window.open('/admin/product/review/' + row.id, 'view_window');
            },
            handleEdit(row) {
                window.open('/admin/product/edit/' + row.id, 'edit_window');
            },
            handleUp() {
                let that = this;

                let res = _(this.selection).map().filter(function (o) {
                    return o.if_open === 0;
                }).flatMap('id').value();

                let form = {
                    ids: res,
                    status: 1,
                };

                this.$admin.putProductActive(form).then(res => {
                    if (res.code === 200) {
                        that.$Notice.success({
                            title: res.msg,
                        });
                        that.getData();
                    } else {
                        that.$Notice.error({
                            title: res.msg,
                        });
                    }
                });
            },
            handleDown() {
                let that = this;

                let res = _(this.selection).map().filter(function (o) {
                    return o.if_open === 1;
                }).flatMap('id').value();

                let form = {
                    ids: res,
                    status: 0,
                };

                this.$admin.putProductActive(form).then(res => {
                    if (res.code === 200) {
                        that.$Notice.success({
                            title: res.msg,
                        });
                        that.getData();
                    } else {
                        that.$Notice.error({
                            title: res.msg,
                        });
                    }
                });
            },
            pageChange(page) {
                this.query.page = page;
                this.getData();
            },
            pageSizeChange(per_page) {
                this.query.page = 1;
                this.query.per_page = per_page;
                this.getData();
            },
            selectionChange(selection) {
                this.selection = selection;
            }
        }
    }
</script>