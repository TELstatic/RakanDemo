<template>
    <section class="content-header">
        <h1>
            {{title}}
            <small>{{subtitle}}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin"><i class="fa fa-dashboard"></i> 控制面板</a></li>
            <li class="active">{{title}}</li>
        </ol>
    </section>
</template>
<script>
    export default {
        props: {
            title: {
                type: String,
                required: true
            },
            subtitle: {
                type: String,
                default: null
            }
        },
        data() {
            return {}
        }
    }
</script>