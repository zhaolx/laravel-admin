<template>
  <div class="app-container">
    <!-- 操作 -->
    <el-row style="margin-bottom: 10px;">
      <el-col :xs="24" :sm="24" :lg="24">
        <el-tooltip content="刷新" placement="top">
          <el-button v-waves type="warning" icon="el-icon-refresh" circle @click="handleFilterClear"/>
        </el-tooltip>
        <el-tooltip content="添加" placement="top">
          <el-button v-waves type="success" icon="el-icon-plus" circle @click="handleCreate"/>
        </el-tooltip>
        <el-tooltip content="删除" placement="top">
          <el-button v-waves :loading="deleting" :disabled="buttonDisabled" type="danger" icon="el-icon-delete" circle @click="handleDeleteAll()"/>
        </el-tooltip>
      </el-col>
    </el-row>

    <!-- 表格 -->
    <tree-table
      v-loading="listLoading"
      :key="tableKey"
      :data="getRulesList"
      :expand-all="expandAll"
      :columns="columns"
      border
      fit
      highlight-current-row
      style="width: 100%;"
      @selection-change="handleSelectionChange">
      <el-table-column label="标识"  align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>
      <el-table-column label="图标" width="80px" align="center">
        <template slot-scope="scope">
			<svg-icon :icon-class="scope.row.icon" class-name="disabled" v-if="scope.row.icon" />
        </template>
      </el-table-column>
	  <el-table-column label="后端路由"  align="center">
	    <template slot-scope="scope">
	      <span>{{ scope.row.path }}</span>
	    </template>
	  </el-table-column>
      <el-table-column label="前端路由" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.path }}</span>
        </template>
      </el-table-column>
      <el-table-column label="组件"  align="center">
        <template slot-scope="scope">
			
          <span>{{ scope.row.component }}</span>
        </template>
      </el-table-column>
	  <el-table-column label="操作权限" width="260px"  align="center">
	    <template slot-scope="scope">
			<block v-for="item in scope.row.button">
				<el-tag size="small"  closable style="margin: 0 6px;" @click="handleUpdate(scope.$index,item.id)" @close="handleDelete(scope.$index,item.id)">{{item.title}}</el-tag>	
			</block>
				
	    </template>
	  </el-table-column>
	  <el-table-column label="显示" width="110px" align="center">
	    <template slot-scope="scope">
			<el-switch
			class="switch"
			  v-model="scope.row.is_show+''"
			  active-text="显示"
			  inactive-text="隐藏"
			  active-value="1"
			  inactive-value="0"	
			  disabled="true"
			  active-color="#13ce66"
			  inactive-color="#dcdcdc">
			</el-switch>
	    </template>
	  </el-table-column>
      <el-table-column label="状态" width="100px" align="center">
        <template slot-scope="scope">
			<el-tag v-if="scope.row.status == 1">正常</el-tag>
			<el-tag type="warning" v-else>禁用</el-tag>
        </template>
      </el-table-column>

      <el-table-column label="操作" align="center" width="120px" class-name="small-padding">
        <template slot-scope="scope">
          <el-tooltip content="编辑" placement="top">
            <el-button v-waves type="primary" icon="el-icon-edit-outline" circle @click="handleUpdate(scope.$index,scope.row.id)"/>
          </el-tooltip>
          <el-tooltip content="删除" placement="top">
            <el-button v-waves :loading="scope.row.delete" type="danger" icon="el-icon-delete" circle @click="handleDelete(scope.$index,scope.row.id)"/>
          </el-tooltip>
        </template>
      </el-table-column>
    </tree-table>

    <!-- 表单 -->
    <rulesForm ref="fromRules" :rule-list="list" @updateRow="updateRow"/>

  </div>
</template>

<script>
import { getList, del, change, delAll, changeAll } from '@/api/rules'
import waves from '@/directive/waves' // 水波纹指令
import { getArrByKey } from '@/utils'
import tree from '@/utils/tree'
import rulesForm from './rules/form'
import treeTable from '@/components/TreeTable'

export default {
  name: 'Rules',
  components: { rulesForm, treeTable },
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
      selectedRows: null,
      listLoading: true,
      expandAll: true,
      columns: [
        {
          text: '名称',
          value: 'title',
		  with:'200px'
        }
      ],
      listQuery: {
        status: '-1',
        title: '',
		pageSize:10000,
      },
      buttonDisabled: true,
      deleting: false
    }
  },
  computed: {
    getRulesList() {
      return tree.listToTreeMulti(this.list, 0, 'id', 'parent_id', 'children', { 'delete': false })
    }
  },
  watch: {
  },
  created() {
    this.fetchList()
  },
  methods: {
    fetchList() {
      this.listLoading = true
      this.$request({
		url: '/api/system/menuList',
		method: 'post',
		data: this.listQuery
	  }).then(response => {
        this.list = response.data.list
        this.listLoading = false
      })
    },
    handleFilterClear() {
      this.listQuery = {
        status: '-1',
        title: '',
		pageSize:10000,
      }
      this.fetchList()
    },
    handleSelectionChange(val) {
      if (val.length > 0) {
        this.buttonDisabled = false
      } else {
        this.buttonDisabled = true
      }
      this.selectedRows = val
    },
    handleCreate() {
      this.$refs.fromRules.handleCreate()
    },
    handleUpdate(index, id) {
      this.$refs.fromRules.handleUpdate(id)
    },
    handleModifyStatus(index, id, status) {
      const statusObj = { 'status': 1 - status }
      this.list = tree.upadteArr(this.list, 'id', id, statusObj)
      change(id, 'status', 1 - status).then(response => {})
    },
    updateRow(temp) {
      this.fetchList()
    },
    handleDelete(index, id) {
      const _this = this
      this.$confirm('此操作将永久删除该记录, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        const delObj = { 'delete': true }
        _this.list = tree.upadteArr(_this.list, 'id', id, delObj)
        this.$request({
        		url: '/api/system/delMenu',
        		method: 'post',
        		data:{id}
        }).then(response => {
          if (response.code === 1) {
            _this.$notify.success('删除成功')
            _this.fetchList()
          } else {
            _this.$notify.error(response.msg)
          }
          const delObj = { 'delete': false }
          _this.list = tree.upadteArr(_this.list, 'id', id, delObj)
        }).catch((error) => {
          const delObj = { 'delete': false }
          _this.list = tree.upadteArr(_this.list, 'id', id, delObj)
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
          const idstr = ids.join(',')
          this.$request({
          		url: '/api/system/batchDelMenu',
          		method: 'post',
          		data:{ids_arr:ids}
          }).then(response => {
            if (response.code === 1) {
              _this.$message.success('删除成功')
              _this.fetchList()
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
            _this.$message.success(response.msg)
            _this.fetchList()
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
<style lang="scss" >
	.text-red{
		color: red;
		cursor: pointer;
	}
	.text-green{
		color: green;
		cursor: pointer;
	}
	/* switch按钮样式 */
	.switch .el-switch__label {
	    position: absolute;
	    display: none;
	    color: #fff !important;
	}
	/*打开时文字位置设置*/
	.switch .el-switch__label--right {
	    z-index: 1;
	}
	/* 调整打开时文字的显示位子 */
	.switch .el-switch__label--right span{
	    margin-right: 9px;
	}
	/*关闭时文字位置设置*/
	.switch .el-switch__label--left {
	    z-index: 1;
	}
	/* 调整关闭时文字的显示位子 */
	.switch .el-switch__label--left span{
	    margin-left: 9px;
	}
	/*显示文字*/
	.switch .el-switch__label.is-active {
	    display: block;
	}
	/* 调整按钮的宽度 */
	.switch.el-switch .el-switch__core,
	.el-switch .el-switch__label {
	     width: 54px !important;
	     margin: 0;
	}
	.el-switch__label *{
		font-size: 10px !important;
	}
	
	

</style>
