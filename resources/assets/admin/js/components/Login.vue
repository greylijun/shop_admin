<style>
</style>
<template>
    <div style="text-align: center;">
        <div class="header">
            <div class="logo"><img src="" alt=""></div>
            <div class="app-name">管理后台登录</div>
        </div>
        <div class="content">
            <el-form label-width="80px" :model="form">
                <el-form-item label="用户名">
                    <el-input v-model="form.user"></el-input>
                </el-form-item>
                <el-form-item label="密码">
                    <el-input @keyup.enter.native="submitHandle" type="password" v-model="form.pwd"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="submitHandle" style="margin-left:0px;width: 150px;">登录
                    </el-button>
                </el-form-item>
            </el-form>
        </div>
    </div>
</template>
<script>
    export default {
        mounted() {
        },
        data() {
            return {
                form: {
                    user: '',
                    pwd: ''
                }
            }
        },
        methods: {
            submitHandle() {
                if (this.form.user.length === 0 || this.form.pwd.length === 0) {
                    this.$notify.warning({message: '请完整输入用户名和密码'});
                    return false;
                }
                this.$http
                    .post('/api/login', this.form)
                    .then(response => {
                        let token = response.data.token;
                        if (!token) {
                            this.$notify.error({message: '没有返回token'});
                            return false;
                        }
                        this.$api.setToken(token);
                        window.location.href = '/';

                        // 设置用户等级
                        this.$api.setUserGrade(response.data.grade);
                        let username = this.form.user;
                        this.$api.setUserName(username);
                    })
                    .catch(error => {
                        this.$notify.error({message: this.$error2message(error)});
                    });
            }
        },
        computed: {},
        watch: {}
    }
</script>