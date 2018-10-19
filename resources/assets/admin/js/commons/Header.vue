<style>
    .header {
        top: 0;
        width: 100%;
        height: 70px;
        z-index: 9;
        background-color: blue;
    }

    .header .logo img {
        float: left;
        height: 50px;
        margin: 12px 10px 0 10px;
    }

    .header .logo p {
        float: left;
        font-size: 26px;
        margin-right: 10px;
        color: #fff;
        padding-left: 50px;
        height: 70px;
        line-height: 70px;
        text-shadow: 1px 1px 1px #333;
        font-family: "Hiragino Sans GB", "Microsoft YaHei", "SimHei", "WenQuanYi Micro Hei";
    }

    .header .toolbar {
        float: right;
    }

    .header .toolbar ul {
        display: inline-block;
        padding: 0 20px;
        height: 35px;
        margin: 20px 20px 0 0;
        list-style: none;
        border: 2px solid #2e6eb4;
        border-radius: 6px;
        box-shadow: 2px 0 #185fac;
    }

    .header .toolbar ul li {
        float: left;
        height: 35px;
        line-height: 35px;
        text-align: center;
    }

    .header .toolbar ul li + li {
        margin-left: 20px;
    }

    .header .toolbar ul li a {
        color: #fff;
        cursor: pointer;
    }

    .header .toolbar ul li a span {
        margin-right: 3px;
    }
</style>
<template>
    <div>
        <div class="header">
            <div class="logo">
                <img src="" alt="">
                <p>Ice Yellow后台管理系统</p>
            </div>
            <div class="toolbar">
                <ul>
                    <li>
                        <a href="#" @click.prevent="changePsdHandle">
                            <span class="glyphicon glyphicon-user"></span>
                            <span>{{ userName.user }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" @click.prevent="logoutHandle"><span class="glyphicon glyphicon-log-out"></span>退出系统</a>
                    </li>
                </ul>
            </div>
        </div>
        <el-dialog :title="form.title" :visible.sync="form.visible" :close-on-click-modal=false>
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-2 control-label">原密码</label>
                    <div class="col-sm-8">
                        <input type="password" class="form-control" placeholder="*" v-model="form.data.password">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">新密码</label>
                    <div class="col-sm-8">
                        <input type="password" class="form-control" placeholder="*" v-model="form.data.new_password">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">确认密码</label>
                    <div class="col-sm-8">
                        <input type="password" class="form-control" placeholder="*" v-model="form.data.rep_password">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-8">
                        <button type="button" class="btn btn-primary btn-block" @click="saveFormHandle">
                            提交
                        </button>
                    </div>
                </div>
            </div>
        </el-dialog>
    </div>
</template>
<script>
    export default {
        mounted() {
        },
        data() {
            return {
                userName: {
                    user: this.$api.getUserName()
                },
                form: {
                    title: '修改密码',
                    visible: false,
                    data: {
                        password: '',// 原密码
                        new_password: '',// 新密码
                        rep_password: '',// 确认密码
                    },
                }
            }
        },
        methods: {
            changePsdHandle() {
                this.form.visible = true;
            },
            saveFormHandle() {
                if (this.form.data.rep_password != (this.form.data.new_password)) {
                    this.$notify.warning({message: '前后输入的密码不一致'});
                    return false;
                }

                this.$api.post('/user/change_pwd', {
                    password: this.form.data.password,// 原密码
                    new_password: this.form.data.new_password,// 新密码
                    rep_password: this.form.data.rep_password,// 确认密码
                })
                    .then(response => {
                        if (true === response.data) {
                            this.form.visible = false;
                            this.$api.deleteToken();
                            this.$api.gotoLogin();
                        }
                    })
                    .catch(error => {
                        this.$notify.error({message: this.$error2message(error)});
                    });
            },
            logoutHandle() {
                if (confirm('确定登出吗？')) {
                    this.$api
                        .get('/logout')
                        .then(() => {
                            this.$api.deleteToken();
                            this.$api.gotoLogin();
                        })
                        .catch(error => {
                            // 注销失败
                            this.$notify.error({message: this.$error2message(error)});
                        });
                }
            }
        },
        computed: {},
        watch: {}
    }
</script>