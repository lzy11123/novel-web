<template>
  <el-card  class="border-0">
    <template #header>
      <div class="flex justify-between items-center">
        <span>小说统计</span>
        <div>
          <el-check-tag
            :checked="current == item.value"
            style="margin-right: 8px"
            v-for="(item, index) in options"
            :key="index"
            @click="onChoose(item.value)"
          >
            {{ item.text }}
          </el-check-tag>
        </div>
      </div>
    </template>
    <div ref="el" id="chart" style="width: 100%; height: 300px"></div>
  </el-card>
</template>
<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import * as echarts from "echarts";
import { useResizeObserver } from "@vueuse/core";

import { getStatistics3 } from "~/api/index.js";

const current = ref("week");

const options = [
  {
    text: "近1个月",
    value: "month",
  },
  {
    text: "近1个周",
    value: "week",
  },
  {
    text: "近24小时",
    value: "hour",
  },
];

var myChart = null;
onMounted(() => {
  var chartDom = document.getElementById("chart");

  if (chartDom) {
    myChart = echarts.init(chartDom);
    getData();
  }
});
// 销毁页面 释放echart实例;
onBeforeUnmount(() => {
  if (myChart) echarts.dispose(myChart);
});

const onChoose = (type) => {
  current.value = type;
  getData();
};

function getData() {
  let option = {
    xAxis: {
      type: "category",
      data: [],
    },
    yAxis: {
      type: "value",
    },
    series: [
      {
        data: [],
        type: "bar",
        showBackground: true,
        backgroundStyle: {
          color: "rgba(180, 180, 180, 0.2)",
        },
      },
    ],
  };

  myChart.showLoading();
  getStatistics3(current.value)
    .then((res) => {
      // console.log(res);
      option.xAxis.data = res.x;
      option.series[0].data = res.y;
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