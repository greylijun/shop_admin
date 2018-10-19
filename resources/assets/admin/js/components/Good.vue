<style scoped>
    .avatar-uploader .el-upload {
        border: 1px dashed #d9d9d9;
        border-radius: 6px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .avatar-uploader .el-upload:hover {
        border-color: #409EFF;
    }

    .avatar-uploader-icon {
        font-size: 40px;
        color: #8c939d;
        width: 220px;
        height: 220px;
        line-height: 220px;
        text-align: center;
    }

    .avatar {
        width: 220px;
        height: 220px;
        display: block;
    }

    .el-button--text {
        color: #606266;
    }
</style>
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
                        <el-button type="primary" size="small" @click="handleAddGood">添加</el-button>
                        <el-button type="danger" size="small" @click="handleDelGood">删除</el-button>
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
                            label="名称"
                            width="180">
                        <template slot-scope="scope">
                            <a @click.prevent="modifyHandle(scope.row)">{{scope.row.name}}</a>
                        </template>
                    </el-table-column>
                    <el-table-column
                            prop="price"
                            label="价格"
                            width="180">
                    </el-table-column>
                    <el-table-column
                            prop="type_id"
                            label="类型">
                    </el-table-column>
                    <el-table-column
                            prop="introduction"
                            :show-overflow-tooltip=true
                            label="简介">
                    </el-table-column>
                    <el-table-column
                            prop="inventory"
                            label="库存">
                    </el-table-column>
                    <el-table-column
                            prop="detail"
                            :show-overflow-tooltip=true
                            label="详情">
                    </el-table-column>
                    <el-table-column
                            label="操作">
                        <template slot-scope="scope">
                            <el-button
                                    size="mini"
                                    @click="handViewPic(scope.$index, scope.row)">查看图片
                            </el-button>
                        </template>
                    </el-table-column>
                </el-table>
                <el-pagination background class="pagination-fenye" v-show="tableData.total>0" layout="prev, pager, next"
                               :page-size="tableData.per_page" :total="tableData.total"
                               @current-change="pageClickHandle">
                </el-pagination>
            </div>
            <!-- 添加 -->
            <el-dialog
                    :title="addGood.dialogTitle"
                    :visible.sync="addGood.dialogFormVisible">
                <el-form :model="addGood.form">
                    <el-form-item label="名称" :label-width="addGood.formLabelWidth">
                        <el-input v-model="addGood.form.name"></el-input>
                    </el-form-item>
                    <el-form-item label="价格" :label-width="addGood.formLabelWidth">
                        <el-input v-model="addGood.form.price"></el-input>
                    </el-form-item>
                    <el-form-item label="类型" :label-width="addGood.formLabelWidth">
                        <el-select v-model="addGood.form.type_id" placeholder="请选择活动区域">
                            <el-option v-for="item in typeLists" :key="item.id" :label="item.name" :value="item.id">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="简介" :label-width="addGood.formLabelWidth">
                        <el-input v-model="addGood.form.introduction"></el-input>
                    </el-form-item>
                    <el-form-item label="库存" :label-width="addGood.formLabelWidth">
                        <el-input v-model="addGood.form.inventory"></el-input>
                    </el-form-item>
                    <el-form-item label="详情" :label-width="addGood.formLabelWidth">
                        <el-input v-model="addGood.form.detail" type="textarea" :rows="2"></el-input>
                    </el-form-item>

                </el-form>
                <div slot="footer" class="dialog-footer">
                    <el-button @click="addGood.dialogFormVisible = false">取消</el-button>
                    <el-button type="primary" @click="handleSubmit">保存</el-button>
                </div>
            </el-dialog>
            <!-- 图片展示 -->
            <el-dialog
                    title="图片列表"
                    :visible.sync="imageList.dialogVisible"
                    width="80%">
                <el-row style="height: 500px; overflow:auto; " :gutter="12">
                    <div v-for="item in imageList.data">
                        <el-col :span="6" style="position:relative;">
                            <el-card>
                                <img style="width: 220px;height:220px;" :src="item.url">
                            </el-card>
                            <el-button type="text" @click="setMain(item.id,item.is_main)">
                                <i :class="`${item.is_main ? 'el-icon-star-on':'el-icon-star-off'}`"
                                   style="position:absolute;top: 15px;right: 200px;font-size: 30px;">
                                </i>
                            </el-button>
                            <el-button type="text" @click="removePic(item.id)">
                                <i class="el-icon-error"
                                   style="position:absolute;top:2px;right:10px;font-size:20px;">
                                </i>
                            </el-button>
                        </el-col>
                    </div>
                    <el-col :span="6">
                        <el-card>
                            <el-upload
                                    class="avatar-uploader"
                                    :action="`/api/upload/image?good_id=${activeImageId}`"
                                    :show-file-list="false"
                                    :headers="{'X-CSRF-TOKEN':this.$api.getToken()}"
                                    :on-success="handleAvatarSuccess"
                                    :before-upload="beforeAvatarUpload">
                                <img v-if="imageUrl" :src="imageUrl" class="avatar">
                                <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                            </el-upload>
                        </el-card>
                    </el-col>
                </el-row>
            </el-dialog>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.loadGoodLists();
        },
        data() {
            return {
                tableData: {
                    data: [],
                    total: 0,
                    per_page: 0
                },
                filter: {
                    search: '',
                    page: ''
                },
                imageList: {
                    dialogVisible: false,
                    data: []
                },
                addGood: {
                    dialogFormVisible: false,
                    formLabelWidth: '120px',
                    form: {
                        name: '',
                        type_id: '',
                        price: '',
                        introduction: '',
                        inventory: '',
                        detail: ''
                    },
                    dialogTitle: ''
                },
                editId: undefined,
                // 类型
                typeLists: [],
                // 被查看图片id
                activeImageId: '',
                imageUrl: '',
            }
        },
        methods: {
            loadGoodLists() {
                this.$api.get('/good', this.filter)
                    .then(response => {
                        this.tableData = response.data;
                    })
                    .catch(error => {
                        this.$notify.error({message: this.$error2message(error)});
                    });
            },
            handleSelectionChange(data) {
                this.multipleSelection = data.map(item => {
                    return item.id
                });
                if (0 === this.multipleSelection.length) {
                    this.multipleSelection = undefined;
                }
            },
            // 分页查询
            pageClickHandle(page) {
                this.filter.page = page;
                this.loadGoodLists();
            },
            // 搜索
            changeSearchHandel() {
                this.loadGoodLists();
            },
            // 查看图片
            handViewPic(index, row) {
                this.activeImageId = row.id;
                this.loadImageList();
            },
            // 添加商品
            handleAddGood() {
                this.addGood.dialogTitle = "添加";
                this.addGood.dialogFormVisible = true;
                // 清空文本框
                this.addGood.form = {
                    name: '',
                    type: '',
                    price: '',
                    introduction: '',
                    inventory: '',
                    detail: ''
                };
                this.loadTypeList();
            },
            // 删除商品
            handleDelGood() {
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
                    this.$api.delete('/good', {
                        id: this.multipleSelection.join(',')
                    })
                        .then(response => {
                            if (0 < response.data) {
                                this.loadGoodLists();
                                this.multipleSelection = undefined;
                            }
                        })
                        .catch(error => {
                            this.$notify.error({message: this.$error2message(error)});
                        });
                });
            },
            // 编辑
            modifyHandle(row) {
                this.loadTypeList();
                this.addGood.dialogTitle = '详情';
                this.addGood.dialogFormVisible = true;
                this.editId = row.id;
                this.$api
                    .get('/good/' + row.id)
                    .then(response => {
                        this.addGood.form = response.data;
                    })
                    .catch(error => {
                        this.$notify.error({message: this.$error2message(error)});
                    });
            },
            // 提交
            handleSubmit() {
                let vm = this;
                let _ = {
                    checkData(data) {
                        let forms = [
                            {key: 'name', label: '名称'},
                            {key: 'price', label: '价格'},
                            {key: 'type_id', label: '类型'},
                            {key: 'introduction', label: '简介'},
                            {key: 'inventory', label: '库存'},
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
                            .post('/good', data)
                            .then(response => {
                                if (response.data) {
                                    vm.addGood.dialogFormVisible = false;
                                    vm.$notify.success({message: '添加成功'});
                                    vm.loadGoodLists();
                                }
                            })
                            .catch(error => {
                                vm.$notify.error({message: vm.$error2message(error)});
                            })
                    },
                    modifyHandle(id, data) {
                        vm.$api
                            .put('/good/' + id, data)
                            .then(response => {
                                if (response.data) {
                                    vm.addGood.dialogFormVisible = false;
                                    vm.$notify.success({message: '修改成功'});
                                    vm.loadGoodLists();
                                }
                            })
                            .catch(error => {
                                vm.$notify.error({message: vm.$error2message(error)});
                            })
                    }
                };
                if (false === _.checkData(this.addGood.form))
                    return false;
                if (undefined === this.editId) {
                    _.addHandle(this.addGood.form);
                }
                else {
                    _.modifyHandle(this.editId, this.addGood.form);
                }
            },
            // 载入图片列表
            loadImageList() {
                this.$api.get('/good/image_list', {
                    good_id: this.activeImageId
                })
                    .then(response => {
                        if (response.data.length) {
                            this.imageList.dialogVisible = true;
                            this.imageList.data = response.data;
                        } else {
                            this.$message({message: '当前不存在图片', type: 'warning'});
                        }

                    })
                    .catch(error => {
                        this.$notify.error({message: this.$error2message(error)});
                    });
            },
            // 载入类型列表
            loadTypeList() {
                this.$api.get('/type/lists')
                    .then(response => {
                        this.typeLists = response.data;
                    })
                    .catch(error => {
                        this.$notify.error({message: this.$error2message(error)});
                    })
            },
            // 移除图片
            removePic(imageId) {
                this.$confirm('此操作将永久删除该图片, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    multipleSelectioncancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$api.delete('/image', {id: imageId})
                        .then(response => {
                            if (0 < response.data) {
                                this.loadImageList();
                            }
                        })
                        .catch(error => {
                            this.$notify.error({message: this.$error2message(error)});
                        })
                });
            },
            // 设为主图
            setMain(imageId, isMain) {
                let status = isMain ? 0 : 1;
                let message = status ? '该图片已设为主图' : '该图片已取消主图';
                this.$api.post('/image/set_main', {
                    id: imageId,
                    status: status
                })
                    .then(response => {
                        if (0 < response.data) {
                            this.loadImageList();
                            this.$message.success({message: message, duration: '2000'})
                        }
                    })
                    .catch(error => {
                        this.$notify.error({message: this.$error2message(error)});
                    })

            },
            handleAvatarSuccess(res, file) {
                this.loadImageList();
            },
            beforeAvatarUpload(file) {
                const isJPG = file.type === 'image/jpeg';
                const isLt2M = file.size / 1024 / 1024 < 2;

                if (!isJPG) {
                    this.$message.error('上传头像图片只能是 JPG 格式!');
                }
                if (!isLt2M) {
                    this.$message.error('上传头像图片大小不能超过 2MB!');
                }
                return isJPG && isLt2M;
            }
        }
    }
</script>