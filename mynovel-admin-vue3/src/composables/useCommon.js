import { computed, reactive, ref } from 'vue'
import { Toast } from '~/composables/until';

// 列表数据，分页，搜索
export function useInitTable(opt = {}) {
    // loading 状态
    const loading = ref(false)

    let searchForm = null;
    let onResetSearchForm = null;
    if (opt.searchForm) {
        // 搜索表单数据
        searchForm = reactive({ ...opt.searchForm });
        // 搜索重置
        onResetSearchForm = () => {
            for (const key in searchForm) {
                searchForm[key] = opt.searchForm[key]
            }
            getData();
        }
    }


    const tableData = ref([])

    // 分页
    const currentPage = ref(1)
    const total = ref(0)
    const limit = ref(10)

    // 获取数据
    function getData(p = null) {

        if (typeof p == 'number') {
            currentPage.value = p;
        }

        loading.value = true;
        opt.getList(currentPage.value, searchForm).then(res => {
            // console.log(res);
            // tableData.value = res.list;
            if (opt.onGetListSuccess && typeof opt.onGetListSuccess == 'function') {
                opt.onGetListSuccess(res);
            } else {
                tableData.value = res.list;
                total.value = res.totalCount;
            }
        }).finally(() => {
            loading.value = false;
        })
    }

    getData();
    // 分页
    const onChangePage = (page) => {
        getData(page)
    }

    // 刷新
    const onRefresh = () => {
        // location.reload();
        getData()
    }


    // 删除
    const onDelete = (id) => {
        // console.log(id)
        loading.value = true;
        opt.delete(id).then(res => {
            Toast('删除成功');
            getData();
        }).finally(() => {
            loading.value = false;
        })
    }


    // 修改状态
    const onStatusChange = (status, row) => {
        // console.log(status)
        // row.statusLoading = true;
        loading.value = true
        opt.changeStatus(row.id, status).then(res => {
            row.status = status;
            Toast('状态修改成功');
        }).finally(() => {
            // row.statusLoading = false;
            loading.value = false
        })
    }




    // 多选选中
    const multipleTableRef = ref(null);
    const MultiIds = ref([]);
    const handleSelectionChange = (e) => {
        // console.log(e);
        MultiIds.value = e.map(o => o.id)
    }

    // 批量删除
    const onMultiDelete = () => {
        loading.value = true;
        opt.delete(MultiIds.value).then(res => {
            Toast('删除成功');
            // 清空选中
            if (multipleTableRef.value) {
                multipleTableRef.value.clearSelection()
            }
            getData();
        }).finally(() => {
            loading.value = false;
        });
    }

    // 批量上架、下架
    const onMultiStatusChange = (status) => {
        loading.value = true;
        opt.changeStatus(MultiIds.value, status).then(res => {
            Toast(status ? '上架成功' : '下架成功');
            if (multipleTableRef.value) {
                multipleTableRef.value.clearSelection()
            }
            getData();
        }).finally(() => {
            loading.value = false;
        })
    }


    return {
        loading,
        searchForm,
        onResetSearchForm,
        onRefresh,
        tableData,
        currentPage,
        total,
        limit,
        getData,
        onChangePage,
        onDelete,
        onStatusChange,
        multipleTableRef,
        handleSelectionChange,
        onMultiDelete,
        onMultiStatusChange,
        MultiIds
    }
}

// 新增、修改
export function useInitForm(opt = {}) {


    // 表单内容
    const formDrawer = ref(null);
    const formRef = ref(null);
    const form = reactive({});
    const editId = ref(0)
    const drawerTitle = computed(() => {
        return editId.value ? '修改' : '新增'
    })
    const rules = opt.rules || {};

    // 提交
    const onSubmit = () => {
        formRef.value.validate((valid) => {
            // console.log(valid);

            if (!valid) return;
            formDrawer.value.showLoading();

            // 提交之前拦截
            let body = {}
            if (opt.beforeSubmit && typeof opt.beforeSubmit == 'function') {
                body = opt.beforeSubmit({ ...form })
            } else {
                body = form;
            }

            // 提交新增、修改
            const fun = editId.value ? opt.update(editId.value, body) : opt.create(body)

            fun.then(res => {
                // console.log(res);

                Toast(drawerTitle.value + '成功')
                // 修改刷新当前，新增刷新第一页
                opt.getData(editId.value ? false : 1);
                formDrawer.value.close();
            }).finally(() => {
                formDrawer.value.hideLoading();
            })
        })
    }

    // 重置表单
    const onResetForm = (row = false) => {
        if (formRef.value) formRef.value.clearValidate();

        if (row) {
            for (const key in opt.form) {
                form[key] = row[key]
            }
        }

    }

    // 新增打开弹框
    const onCreate = () => {
        editId.value = 0;
        onResetForm(opt.form);
        formDrawer.value.open();
    }

    // 修改打开弹框
    const onEdit = (item) => {
        editId.value = item.id;
        onResetForm(item)
        formDrawer.value.open();
    }

    return {
        formDrawer,
        formRef,
        form,
        editId,
        drawerTitle,
        rules,
        onSubmit,
        onResetForm,
        onCreate,
        onEdit
    }

}