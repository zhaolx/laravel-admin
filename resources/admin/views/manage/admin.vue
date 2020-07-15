<template>
  <div class="app-container">
    <!-- 搜索 -->
    <div v-if="showSearch" class="filter-container">
      <el-form :inline="true" :model="listQuery" class="form-inline">
        <el-form-item label="">
          <el-input v-model="listQuery.keyword" placeholder="账号/昵称" clearable size="small"/>
        </el-form-item>

        <el-form-item label="">
          <el-select v-model="listQuery.status" placeholder="状态" clearable size="small">
            <el-option label="全部" value="-1"/>
            <el-option label="正常" value="1"/>
            <el-option label="禁用" value="0"/>
          </el-select>
        </el-form-item>
       <!-- <el-form-item label="">
          <el-date-picker v-model="dateTime" :picker-options="pickerOptions" type="daterange" range-separator="至" start-placeholder="开始日期" end-placeholder="结束日期" value-format="yyyy-MM-dd" align="right" clearable size="small"/>
        </el-form-item>
        <el-form-item>
          <el-button v-waves type="primary" icon="el-icon-search" size="small" @click="handleFilter">搜索</el-button>
        </el-form-item>
        <el-form-item>
          <el-button v-waves type="warning" icon="el-icon-refresh" size="small" @click="handleFilterClear">重置</el-button>
        </el-form-item> -->
      </el-form>
    </div>

    <!-- 操作 -->
    <el-row style="margin-bottom: 10px;">
      <el-col :xs="24" :sm="24" :lg="24">
        <el-tooltip content="刷新" placement="top">
          <el-button v-waves type="warning" icon="el-icon-refresh" circle @click="handleFilterClear"/>
        </el-tooltip>
        <el-tooltip content="添加" placement="top">
          <el-button v-waves type="success" icon="el-icon-plus" circle @click="handleCreate"/>
        </el-tooltip>
        <el-tooltip content="搜索" placement="top">
          <el-button v-waves type="primary" icon="el-icon-search" circle @click="showSearch = !showSearch"/>
        </el-tooltip>
        <el-tooltip content="删除" placement="top">
          <el-button v-waves :loading="deleting" :disabled="buttonDisabled" type="danger" icon="el-icon-delete" circle @click="handleDeleteAll()"/>
        </el-tooltip>
        <el-tooltip content="更多" placement="top">
          <el-dropdown trigger="click" placement="bottom" style="margin-left: 10px;" @command="handleCommand">
            <el-button :disabled="buttonDisabled" type="Info" circle>
              <i class="el-icon-more"/>
            </el-button>
            <el-dropdown-menu slot="dropdown" >
              <el-dropdown-item command="1">设为正常</el-dropdown-item>
              <el-dropdown-item command="0">设为禁用</el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
        </el-tooltip>
      </el-col>
    </el-row>

    <!-- 表格 -->
    <el-table
      v-loading="listLoading"
      :key="tableKey"
      :data="list"
      border
      fit
      highlight-current-row
      style="width: 100%;"
      @selection-change="handleSelectionChange">
      <el-table-column type="selection" width="55"/>
      <el-table-column label="ID" align="center" width="65" fixed>
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column label="头像" width="80px" fixed>
        <template slot-scope="scope">
          <span class="link-type" @click="handleImg(scope.row.avatar)"><img :src="scope.row.avatar" width="60" height="60"></span>
        </template>
      </el-table-column>
      <el-table-column label="账号" min-width="100px" align="center">
        <template slot-scope="scope">
          <span class="link-type" @click="handleUpdate(scope.$index,scope.row.id)">{{ scope.row.account }}</span>
        </template>
      </el-table-column>
	  
     <!-- <el-table-column label="手机" width="110px" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.phone }}</span>
        </template>
      </el-table-column>
      <el-table-column label="邮箱" width="150px" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.email }}</span>
        </template>
      </el-table-column> -->
	  
      <el-table-column label="昵称" min-width="110px" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.nickname }}</span>
        </template>
      </el-table-column>
      <el-table-column label="角色" min-width="110px" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.role_name }}</span>
        </template>
      </el-table-column>
      <el-table-column label="状态"  width="150px" align="center">
		  <template slot-scope="scope">
		  	<el-tag v-if="scope.row.status == 1">正常</el-tag>
		  	<el-tag type="warning" v-else>禁用</el-tag>
		  </template>
       
      </el-table-column>
	  
      <!-- <el-table-column label="登录IP" width="120px" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.loginIp }}</span>
        </template>
      </el-table-column>
      <el-table-column label="登录时间" width="160px" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.loginTime|parseTime('{y}-{m}-{d} {h}:{i}:{s}') }}</span>
        </template>
      </el-table-column>
      <el-table-column label="注册IP" width="120px" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.regIp }}</span>
        </template>
      </el-table-column> -->
	  
      <el-table-column label="注册时间" min-width="160px" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.in_time|parseTime('{y}-{m}-{d} {h}:{i}:{s}') }}</span>
        </template>
      </el-table-column>
      <el-table-column label="操作" align="center" width="230px" class-name="small-padding fixed-width" fixed="right">
        <template slot-scope="scope">
          <el-tooltip content="编辑" placement="top">
            <el-button v-waves type="primary" icon="el-icon-edit-outline" circle @click="handleUpdate(scope.$index,scope.row.id)"/>
          </el-tooltip>
          <el-tooltip content="删除" placement="top">
            <el-button v-waves :loading="scope.row.delete" type="danger" icon="el-icon-delete" circle @click="handleDelete(scope.$index,scope.row.id)"/>
          </el-tooltip>
        </template>
      </el-table-column>
    </el-table>

    <!-- 分页 -->
    <div class="pagination-container">
      <el-pagination v-show="total>0" :current-page="listQuery.page" :page-sizes="[10,20,30, 50]" :page-size="listQuery.pageSize" :total="total" background layout="total, sizes, prev, pager, next, jumper" @size-change="handleSizeChange" @current-change="handleCurrentChange"/>
    </div>

    <!-- 表单 -->
    <adminForm ref="fromAdmin" @updateRow="updateRow"/>

  </div>
</template>

<script>
import waves from '@/directive/waves'
import { parseTime, pickerOptions, getArrByKey } from '@/utils'
import adminForm from './admin/form'
import openWindow from '@/utils/openWindow'

export default {
  name: 'Admin',
  components: { adminForm },
  directives: {
    waves
  },
  filters: {
    statusFilter(status) {
      const statusMap = {
        0: '禁用',
        1: '正常'
      }
      return statusMap[status]
    }
  },
  data() {
    return {
      tableKey: 0,
      list: null,
      total: null,
      selectedRows: null,
      listLoading: true,
      showSearch: false,
      listQuery: {
        page: 1,
        pageSize: 10,
        status: '-1',
        keyword: '',
        phone: '',
        realName: '',
        startTime: '',
        endTime: ''
      },
      buttonDisabled: true,
      deleting: false,
      dateTime: '',
      pickerOptions: pickerOptions,
      currentIndex: -1,
	  roleList:[],
    }
  },
  watch: {
    dateTime: function(val) {
      this.listQuery.startTime = val[0]
      this.listQuery.endTime = val[1]
    }
  },
  created() {
    this.fetchList();
    this.getRoles();
  },
  methods: {
    fetchList() {
      this.listLoading = true;
	  let _this = this;
      this.$request({
      		url: '/api/system/userList',
      		method: 'post',
      		data:this.listQuery
      }).then(response => {
        this.list = response.data.list
        this.total = response.data.total
        this.listLoading = false
      })
    },
	getRoles() {
	 this.$request({
	 		url: '/api/system/roleList',
	 		method: 'post',
	 		data:{status:1,page:1,pageSize:10000}
	 }).then(response => {
	    this.roleList = response.data.list
	  })
	},
    handleFilter() {
      this.listQuery.page = 1
      this.fetchList()
    },
    handleFilterClear() {
      this.listQuery = {
        page: 1,
        pageSize: 10,
        status: '-1',
        keyword: '',
        phone: '',
        realName: '',
        startTime: '',
        endTime: ''
      }
      this.dateTime = ''
      this.fetchList()
    },
    handleSizeChange(val) {
      this.listQuery.pageSize = val
      this.fetchList()
    },
    handleCurrentChange(val) {
      this.listQuery.page = val
      this.fetchList()
    },
    handleModifyStatus(index, id, status) {
      this.list[index]['status'] = 1 - status
      change(id, 'status', 1 - status).then(response => {})
    },
    handleSelectionChange(val) {
      if (val.length > 0) {
        this.buttonDisabled = false
      } else {
        this.buttonDisabled = true
      }
      this.selectedRows = val
    },
    handleImg(img) {
      openWindow(img, '图片预览', '500', '400')
    },
    handleCreate() {
      this.$refs.fromAdmin.handleCreate()
    },
    handleUpdate(index, id) {
      this.currentIndex = index
      this.$refs.fromAdmin.handleUpdate(id)
    },
    updateRow(temp) {
      if (this.currentIndex >= 0 && temp.id > 0) {
        this.list.splice(this.currentIndex, 1, temp)
      } else {
        if (this.list.length >= 10) {
          this.list.pop()
        }
        this.list.unshift(temp)
        this.total = this.total + 1
      }
      this.currentIndex = -1
    },
    handleDelete(index, id) {
      const _this = this
      this.$confirm('此操作将永久删除该记录, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        _this.$set(_this.list[index], 'delete', true)
       _this.$request({
       		url: '/api/system/deleteUser',
       		method: 'post',
       		data:{id}
       }).then(response => {
          if (response.code === 1) {
            _this.list.splice(index, 1)
            _this.total = _this.total - 1
            _this.$notify.success('删除成功')
          } else {
            _this.$notify.error(response.msg)
          }
          _this.$set(_this.list[index], 'delete', false)
        }).catch((error) => {
          _this.$set(_this.list[index], 'delete', false)
        })
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消删除'
        })
      })
    },
    handleDeleteAll() {
      const _this = this
      if (this.selectedRows.length > 0) {
        this.$confirm('此操作将永久删除该记录, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          _this.deleting = true
          const ids = getArrByKey(_this.selectedRows, 'id')
         
          _this.$request({
          		url: '/api/system/deleteUser',
          		method: 'post',
          		data:{ids_arr:ids}
          }).then(response => {
            if (response.code === 1) {
              const delindex = []
              _this.list.forEach(function(item, index, input) {
                if (ids.indexOf(item.id) > -1) {
                  delindex.push(index)
                }
              })
              for (let i = delindex.length - 1; i >= 0; i--) {
                _this.list.splice(delindex[i], 1)
              }
              _this.total = _this.total - delindex.length
              _this.$message.success('删除成功')
            } else {
              _this.$message.error(response.msg)
            }
            _this.deleting = false
          }).catch((error) => {
            _this.deleting = false
          })
        }).catch(() => {
          this.$message({
            type: 'info',
            message: '已取消删除'
          })
        })
      } else {
        _this.$message.error('请选择要删除的数据')
      }
    },
    handleCommand(command) {
      const _this = this
      if (this.selectedRows.length > 0) {
        const ids = getArrByKey(this.selectedRows, 'id')
        const idstr = ids.join(',')
        changeAll({ val: idstr, field: 'status', value: command }).then(response => {
          if (response.status === 1) {
            _this.list.forEach(function(item, index, input) {
              if (ids.indexOf(item.id) > -1) {
                _this.list[index]['status'] = command
              }
            })
            _this.$message.success(response.msg)
          } else {
            _this.$message.error(response.msg)
          }
        }).catch((error) => {
        })
      } else {
        _this.$message.error('请选择要操作的数据')
      }
    }
  }
}
</script>
<style rel="stylesheet/scss" lang="scss">
	.text-red{
		color: red;
		cursor: pointer;
	}
	.text-green{
		color: green;
		cursor: pointer;
	}
</style>
