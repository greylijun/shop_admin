<template>
    <div>
        <div class="box-inner">
            <div class="box-content">
                <div class="toolbar form-inline">
                    <div class="input-group">
                        <input type="text" class="form-control" style="height: 32px;" placeholder="搜索 名称"
                               v-model="filter.search" @change="changeSearchHandel">
                        <span class="input-group-btn">
							<button class="btn btn-default" type="button" style="height: 32px;">
								<span class="glyphicon glyphicon-search"></span>
                        </button>
                        </span>
                    </div>
                    <div class="input-group">
                        <el-button type="primary" size="small" @click="handleAddType">添加</el-button>
                        <el-button type="danger" size="small" @click="handleDelType">删除</el-button>
                    </div>
                </div>
            </div>
            <div class="main-table">
                <el-table
                        :data="tableData.data"
                        style="width: 100%"
                        border
                        stripe
                        tooltip-effect="dark"
                        @selection-change="handleSelectionChange">
                    <el-table-column
                            type="selection"
                            prop="id"
                            width="55">
                    </el-table-column>
                    <el-table-column
                            prop="name"
                            label="名称">
                        <template slot-scope="scope">
                            <a @click.prevent="modifyHandle(scope.row)">{{scope.row.name}}</a>
                        </template>
                    </el-table-column>
                </el-table>
                <el-pagination background class="pagination-fenye" v-show="tableData.total>0" layout="prev, pager, next"
                               :page-size="tableData.per_page" :total="tableData.total"
                               @current-change="pageClickHandle">
                </el-pagination>
            </div>
            <el-dialog :title="dialogTitle"
                       :visible.sync="dialogVisible">
                <el-form :model="form">
                    <el-form-item label="名称" label-width="180px">
                        <el-input v-model="form.name"></el-input>
                    </el-form-item>
                </el-form>
                <div slot="footer" class="dialog-footer">
                    <el-button @click="dialogVisible = false">取消</el-button>
                    <el-button type="primary" @click="handleSubmit">保存</el-button>
                </div>
            </el-dialog>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.loadTypeList();
        },
        data() {
            return {
                filter: {
                    search: '',
                    page: ''
                },
                tableData: {
                    data: [],
                    total: 0,
                    per_page: 0,
                },
                dialogTitle: '',
                dialogVisible: false,
                editId: undefined,
                form: {
                    name: ''
                }
            }
        },
        methods: {
            handleSelectionChange(data) {
                this.multipleSelection = data.map(item => {
                    return item.id
                });
                if (0 === this.multipleSelection.length) {
                    this.multipleSelection = undefined;
                }
            },
            // 搜索
            changeSearchHandel() {
                this.loadTypeList();
            },
            // 添加
            handleAddType() {
                this.form = {
                    name: ''
                };
                this.dialogTitle = '添加';
                this.dialogVisible = true;
            },
            // 编辑
            modifyHandle(row) {
                this.loadTypeList();
                this.dialogTitle = '编辑';
                this.dialogVisible = true;
                this.editId = row.id;
                this.$api
                    .get('/type/' + row.id)
                    .then(response => {
                        this.form = response.data;
                    })
                    .catch(error => {
                        this.$notify.error({message: this.$error2message(error)});
                    });
            },
            // 删除
            handleDelType() {
                if (undefined === this.multipleSelection) {
                    this.$notify.warning({
                        message: '请先选择'
                    });
                    return false;
                }
                this.$confirm('此操作将永久删除该条记录, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    multipleSelectioncancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$api.delete('/type', {
                        id: this.multipleSelection.join(',')
                    })
                        .then(response => {
                            if (0 < response.data) {
                                this.loadTypeList();
                                this.multipleSelection = undefined;
                            }
                        })
                        .catch(error => {
                            this.$notify.error({message: this.$error2message(error)});
                        });
                });
            },
            // 提交
            handleSubmit() {
                let vm = this;
                let _ = {
                    checkData(data) {
                        let forms = [
                            {key: 'name', label: '名称'},
                        ];
                        let emptyForm = forms.find(({key, label}) => {
                            return '' === data[key];
                        });
                        if (undefined !== emptyForm) {
                            let {key, label} = emptyForm;
                            vm.$notify.warning({message: '请输入' + label});
                            return false;
                        }
                        else
                            return true;
                    },
                    addHandle(data) {
                        vm.$api
                            .post('/type', data)
                            .then(response => {
                                if (response.data) {
                                    vm.dialogVisible = false;
                                    vm.$notify.success({message: '添加成功'});
                                    vm.loadTypeList();
                                }
                            })
                            .catch(error => {
                                vm.$notify.error({message: this.$error2message(error)});
                            })
                    },
                    modifyHandle(id, data) {
                        vm.$api
                            .put('/type/' + id, data)
                            .then(response => {
                                if (response.data) {
                                    vm.dialogVisible = false;
                                    vm.$notify.success({message: '修改成功'});
                                    vm.loadTypeList();
                                }
                            })
                            .catch(error => {
                                vm.$notify.error({message: vm.$error2message(error)});
                            })
                    }
                };
                if (false === _.checkData(this.form))
                    return false;
                if (undefined === this.editId) {
                    _.addHandle(this.form);
                }
                else {
                    _.modifyHandle(this.editId, this.form);
                }
            },
            // 分页
            pageClickHandle(page) {
                this.filter.page = page;
                this.loadTypeList();
            },
            // 载入类型列表
            loadTypeList() {
                this.$api.get('/type', this.filter)
                    .then(response => {
                        this.tableData = response.data;
                    })
                    .catch(error => {
                        this.$notify.error({message: this.$error2message(error)});
                    })
            }
        }
    }
</script>

<style scoped>

</style>