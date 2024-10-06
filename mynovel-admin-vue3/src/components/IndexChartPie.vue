<template>
  <!-- shadow="never" -->
  <el-card class="border-0">
    <template #header>
      <div class="flex justify-between items-center">
        <span>分类占比</span>
      </div>
    </template>
    <div ref="el" id="chartPie" style="width: 100%; height: 300px"></div>
  </el-card>
</template>
<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import * as echarts from "echarts";
import { useResizeObserver } from "@vueuse/core";

import { getStatistics2 } from "~/api/index.js";


var myChart = null;
onMounted(() => {
  var chartDom = document.getElementById("chartPie");

  if (chartDom) {
    myChart = echarts.init(chartDom);
    getData();
  }
});
// 销毁页面 释放echart实例;
onBeforeUnmount(() => {
  if (myChart) echarts.dispose(myChart);
});


function getData() {
  let option = {
    tooltip: {
      trigger: "item",
      formatter: "{a} <br/>{b} : {c} ({d}%)",
    },
    series: [
      {
        name: "文章占比",
        type: "pie",
        radius: [20, 140],
        center: ["50%", "50%"],
        roseType: "area",
        itemStyle: {
          borderRadius: 5,
        },
        data: [
        //   { value: 30, name: "rose 1" },
        ],
      },
    ],
  };
  myChart.showLoading();
  getStatistics2()
    .then((res) => {
      option.series[0].data = res;
      option && myChart.setOption(option);
    })
    .finally(() => {
      myChart.hideLoading();
    });
}

// 监听页面缩放大小
const el = ref(null);
useResizeObserver(el, (entries) => {
  // console.log(entries)
  // 让echart实现兼容
  myChart.resize();
});
</script>