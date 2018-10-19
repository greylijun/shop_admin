<style>
    .navg {
        margin-right: 4px;
        width: 24px;
        height: 18px;
        padding-left: 4px;
    }
</style>
<template>
    <div class="nav-menu box-inner">
        <el-menu
                default-active="2"
                class="el-menu-vertical-demo"
                @open="handleOpen"
                @close="handleClose"
                :router=true>
            <el-menu-item index="/home">
                <i class="glyphicon glyphicon-eye-open navg"></i>
                <span slot="title">总览</span>
            </el-menu-item>
            <el-menu-item index="/good">
                <i class="el-icon-menu"></i>
                <span slot="title">商品列表</span>
            </el-menu-item>
            <el-menu-item index="/type">
                <i class="el-icon-menu"></i>
                <span slot="title">类型管理</span>
            </el-menu-item>
        </el-menu>
    </div>
</template>
<script>
    export default {
        mounted() {
        },
        data() {
            return {
                num: 0,
                isShow: true
            }
        },
        computed: {},
        methods: {
            handleOpen(key, keyPath) {
                console.log(key, keyPath);
            },
            handleClose(key, keyPath) {
                console.log(key, keyPath);
            },
        }
    }
</script>